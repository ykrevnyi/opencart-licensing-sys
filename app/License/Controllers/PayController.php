<?php namespace License\Controllers;


use License\Repositories\TransactionRepository;
use License\Repositories\ModuleRepository;
use License\Repositories\KeyRepository;
use License\Services\Interkassa;
use License\Services\KeyAuth;
use License\Models\Module;
use Cookie;
use Event;
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
		$module = $this->moduleRepo->find($params['module_code'], $params['domain']);
		
		$this->layout->content = View::make('pay.index')
			->with('domain', $params['domain'])
			->with('email', $params['email'])
			->with('module_type', $params['module_type'])
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

		// Get customer target data (email, domain, module type)
		$customerInfo = $this->parseCustomerTargetInfo($data);

		// Create new transactions and keys
		if ($this->interkassa->validate($data))
		{
			$transaction = $this->transactionRepo->create($data);

			// Check target module info with transaction info
			if ($this->validateModuleTransaction($data['ik_pm_no'], $customerInfo, $data))
			{
				// Remove old key
				$this->keyRepo->remove($customerInfo['domain'], $module_code);

				// Create new key
				$this->keyauth->transaction_id = $transaction->id;
				$this->keyauth->module_code = $module_code;
				$this->keyauth->module_type = $customerInfo['module_type'];
				$this->keyauth->domain = $customerInfo['domain'];
				$this->keyauth->key_time = 60 * 60 * 24 * 31;

				$key = $this->keyauth->make();

				// Send email with needle info to the customer
				Event::fire('email.license.created', array(
					'key' => $key,
					'info' => $customerInfo,
					'module' => $this->moduleRepo->find(
						$data['ik_pm_no'],
						$customerInfo['domain']
					)
				));
			}
		}

		return 'hello';
	}


	/**
	 * Check if module was bought with needle price and currency
	 *
	 * @return mixed
	 */
	private function validateModuleTransaction($module_code, $customerInfo, $data)
	{
		$module = $this->moduleRepo->find($data['ik_pm_no'], $customerInfo['domain']);

		// Get price of needle type
		$module_real_price = NULL;
		foreach ($module['types'] as $type)
		{
			if ($type['id'] == $customerInfo['module_type'])
			{
				$module_real_price = $type['price'];
			}
		}

		// Check if transaction was good and without any type of fraud
		if ( ! $module OR $module_real_price < $data['ik_am'] OR $data['ik_cur'] != 'USD')
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
	private function parseCustomerInfo()
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

		// Parse module type
		$module_type = Input::get('module_type', '');

		return array(
			'domain' 		=> $domain,
			'email' 		=> $email,
			'module_code' 	=> $module_code,
			'module_type'	=> $module_type
		);
	}


	/**
	 * Parse customer targe info such as email, domain and module type
	 * in order to store this info in our database
	 *
	 * @return mixed
	 */
	private function parseCustomerTargetInfo()
	{
		// Get Interkassa description field
		$payment_description = htmlspecialchars_decode(Input::get('ik_desc'));

		// And here we will parse needle data
		preg_match("/^(.+)\:\:(.+)\:\:(.+)$/i", $payment_description, $matches);
		
		if (isset($matches[1]) AND isset($matches[2]) AND isset($matches[3]))
		{
			return array(
				'email' => $matches[1],
				'domain' => $matches[2],
				'module_type' => $matches[3]
			);
		}
		
		throw new \Exception("Cant get domain and email");
	}



}
