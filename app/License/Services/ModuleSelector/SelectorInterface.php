<?php namespace License\Services\ModuleSelector;


interface SelectorInterface {

	/**
	 * Simply return query that will select needle module fields
	 *
	 * @return mixed
	 */
	public function make();

}