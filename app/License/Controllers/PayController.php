<?php namespace License\Controllers;


use License\Repositories\ModuleRepository;
use License\Services\Interkassa;
use License\Models\Module;
use Cookie;
use Input;
use View;


class PayController extends BaseController {


	private $moduleRepo;


	function __construct()
	{
		$this->interkassa = new Interkassa;
		$this->moduleRepo = new ModuleRepository;
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Parse all the customer params
		$params = $this->parseCustomerInfo();
		$module = $this->moduleRepo->find($params['module_code']);

		$this->layout->content = View::make('pay.index')
			->with('domain', $params['domain'])
			->with('email', $params['email'])
			->with('module', $module);
		
	}


	/**
	 * Process payment
	 *
	 * @return mixed
	 */
	public function create()
	{
		$data = Input::all();

		if ($this->interkassa->validate($data))
		{
			$transaction = $this->transactionRepo->create($data);

			// Check target module info with transaction info
			if ($this->validateModuleTransaction($data['ik_pm_no'], $data))
			{
				$this->keyRepo->remove($domain, $module_code);
			}

		}
	}


	/**
	 * Check if module was bought with needle price and currency
	 *
	 * @return mixed
	 */
	private function validateModuleTransaction($module_code, $data)
	{
		$module = $this->moduleRepo->find($data['ik_pm_no']);

		if ( ! $module OR $module->price < $data['ik_am'] OR $data['ik_cur'] != 'USD')
		{
			throw new Exception("Error in pay controller::create()");
		}
	}


	/**
	 * Parse customer user params
	 *
	 * @return mixed
	 */
	public function parseCustomerInfo()
	{
		// Parse domain
		$domain = NULL;
		if (isset($_SERVER['HTTP_REFERER']))
		{
			$parse = parse_url($_SERVER['HTTP_REFERER']);
			$domain = $parse['host'];
		}

		// Parse email
		$email = Input::get('email', '');

		// Parse module code
		$module_code = Input::get('module_code', 'undefined');

		return array(
			'domain' 		=> $domain,
			'email' 		=> $email,
			'module_code' 	=> $module_code
		);
	}



}
