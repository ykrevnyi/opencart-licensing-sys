<?php namespace License\Controllers;


use License\Repositories\ModuleRepository;
use License\Output\ModuleFormFormater;
use License\Models\Module;
use Input;
use View;


class ModulesController extends BaseController {


	private $repo;


	function __construct() {
		$this->repo = new ModuleRepository;
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		// Parse domain
		$domain = $this->parseDomain();

		$modules = $this->repo->all($domain);
		$callback = \Input::get('callback', '[<b>SPECIFY CALLBACK]</b>');

		return $callback . '(' . json_encode($modules) . ')';
	}


	/**
	 * Simply return module .zip archive
	 * and try to create demo key
	 *
	 * At that point we will not return any errors to the customer.
	 * All we want to do is - give away a module files. This is our goal.
	 *
	 * @return mixed
	 */
	public function get()
	{
		// Parse user needle data
		$domain = Input::get('domain', '');
		$module_code = Input::get('module_code', '');

		// If module code is `self` -> we just want to perform `self update`
		// We don't want to store any keys or chek module existence
		if ($module_code != 'self')
		{
			if (empty($module_code) OR ! count($this->repo->find($module_code, $domain)))
			{
				echo "You should specify a module code"; die();
			}

			// Attempt to create key
			try {
				$keyauth = new \License\Services\KeyAuth;
				$keyauth->trial($domain, $module_code);
			}
			catch (\License\Exceptions\KeyExiststException $e) {}

			// Increment module download
			$this->repo->incrementDownload($module_code);
		}

		// Give module
		$module_location = $this->repo->getModuleLocation($module_code);
		
		if (file_exists($module_location)) {
		    return $this->triggerDownload($module_location, $module_code);
		} else {
		    return NULL;
		}
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// 
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($module_code)
	{
		$formater = new ModuleFormFormater;

		$domain = $this->parseDomain();
		$module = (object) $this->repo->find($module_code, $domain);
		$module = $formater->format($module);

		if (Input::has('jsonp'))
		{
			$callback = \Input::get('callback', '[<b>SPECIFY CALLBACK]</b>');

			return $callback . '(' . json_encode($module) . ')';
		}

		return View::make('modules.show')
				->with('module', $module);
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


	/**
	 * Parse domain name
	 *
	 * @return mixed
	 */
	private function parseDomain()
	{
		// Parse referer host
		$domain = NULL;

		if (isset($_SERVER['HTTP_REFERER']))
		{
			$parse = parse_url($_SERVER['HTTP_REFERER']);
			$domain = $parse['host'];
		}

		return $domain;
	}


	/**
	 * Send headers to start file download
	 *
	 * @return Response
	 */
	public function triggerDownload($module_location, $module_code)
	{
		header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
	    header("Cache-Control: public"); // needed for i.e.
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: Binary");
	    header("Content-Length:".filesize($module_location));
	    header("Content-Disposition: attachment; filename=" . $module_code . '.zip');
	    readfile($module_location);

	    die();
	}


}
