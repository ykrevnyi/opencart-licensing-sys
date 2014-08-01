<?php namespace License\Controllers;


use License\Repositories\ModuleRepository;
use License\Output\ModuleFormFormater;
use License\Models\Module;
use Response;
use Input;
use View;


class AdminModulesController extends BaseController {


	private $repo;


	function __construct() {
		$domain = 'lic.dev';

		$this->repo = new ModuleRepository($domain);
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$modules = $this->repo->all();

		return View::make('admin.modules.list', ['modules' => $modules] );
	}

	public function getDownload($code)
	{
		$module_location = public_path() . "/modules/" . $code . "/" . $code . ".zip";

		header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
	    header("Cache-Control: public"); // needed for i.e.
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: Binary");
	    header("Content-Length:".filesize($module_location));
	    header("Content-Disposition: attachment; filename=" . $code . '.zip');
	    readfile($module_location);
	}

	public function editModule($code)
	{
		$module = $this->repo->find($code);
		return View::make('admin.modules.edit', ['module' => $module] );
	}

	public function updateModule($code)
	{
		
	}

}
