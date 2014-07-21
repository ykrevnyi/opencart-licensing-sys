<?php namespace License\Services\Module;


use License\Services\Module\TypeInterface;


class RegularPaymentType implements TypeInterface {
	
	/**
	 * Define if module price is regular (every year) or static (one time purchase)
	 *
	 * @return bool
	 */
	public function isRegular() {
		return true;
	}

}