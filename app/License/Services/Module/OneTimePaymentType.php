<?php namespace License\Services\Module;


use License\Services\Module\TypeInterface;


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