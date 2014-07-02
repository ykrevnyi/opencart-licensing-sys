<?php namespace License\Controllers;


use License\Repositories\TransactionRepository;
use License\Repositories\ModuleRepository;
use License\Repositories\KeyRepository;
use License\Services\Interkassa;
use License\Services\KeyAuth;
use License\Models\Module;
use Cookie;
use Input;
use View;


class PayController extends BaseController {


	private $moduleRepo;
	private $transactionRepo;
	private $interkassa;
	private $keyauth;


	function __construct()
	{
		$this->keyauth = new KeyAuth;
		$this->interkassa = new Interkassa;
		$this->keyRepo = new KeyRepository;
		$this->moduleRepo = new ModuleRepository;
		$this->transactionRepo = new TransactionRepository;
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

		// Get module code
		$module_code = $data['ik_pm_no'];

		// ///////////////////////////////////////////////
		// ///////////////////////////////////////////////
		// ///////////////////////////////////////////////
		// ///////////////////////////////////////////////

		// Get targer payment description
		file_put_contents($_SERVER["DOCUMENT_ROOT"] . '/app/hello.txt', 'test');

		$payment_description = htmlspecialchars_decode(Input::get('ik_desc'));
		// Get customer email
		preg_match("/(.+)\:\:(.+)/i", $payment_description, $matches);
		
		if (isset($matches[1]) AND isset($matches[2]))
		{
			$domain = $matches[1];
			$email = $matches[2];
		}
		else
		{
			throw new \Exception("Cant get domain and email");
		}

		// ///////////////////////////////////////////////
		// ///////////////////////////////////////////////
		// ///////////////////////////////////////////////
		// ///////////////////////////////////////////////

		if ($this->interkassa->validate($data))
		{
			$transaction = $this->transactionRepo->create($data);

			// Check target module info with transaction info
			if ($this->validateModuleTransaction($data['ik_pm_no'], $data))
			{
				// Remove old key
				$this->keyRepo->remove($domain, $module_code);

				// Create new key
				$this->keyauth->transaction_id = $transaction->id;
				$this->keyauth->module_code = $module_code;
				$this->keyauth->domain = $domain;
				$this->keyauth->key_time = 60 * 60 * 24 * 31;

				$key = $this->keyauth->make();
			}
		}

		return 'hello';
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
			throw new \Exception("Error in pay controller::create()");
		}

		return true;
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
