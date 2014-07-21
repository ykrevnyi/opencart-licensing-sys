<?php namespace License\Services\ModuleType;


use License\Services\ModuleType\TypeInterface;


class OneTimePaymentType implements TypeInterface {


	/**
	 * Define if module price is regular (every year) or static (one time purchase)
	 *
	 * @return bool
	 */
	public function isRegular() {
		return false;
	}

}