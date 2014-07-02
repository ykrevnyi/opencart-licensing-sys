<?php namespace License\Controllers;


use View;


class BaseController extends \Controller {


	protected $layout = 'default';


	protected function beforeRender()
	{
		$this->layout->header = View::make('header');
		$this->layout->footer = View::make('footer');
	}
	

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}

		// Append header and footer
		$this->beforeRender();
	}

}
