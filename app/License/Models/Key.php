<?php namespace License\Models;

class Key extends \Eloquent
{

	protected $fillable = array('key',
		'domain',
		'module_code',
		'module_type',
		'transaction_id',
		'active',
		'expired_at',
		'create_at'
	);
	
}