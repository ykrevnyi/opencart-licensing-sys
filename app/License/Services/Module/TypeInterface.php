<?php namespace License\Services\Module;


interface TypeInterface {

	/**
	 * Define if module price is regular (every year) or static (one time purchase)
	 *
	 * @return bool
	 */
	public function isRegular();

}