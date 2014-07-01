<?php

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection(array(
	'driver'    => 'mysql',
	'host'      => 'localhost',
	'database'  => 'license_dev',
	'username'  => 'license_dev',
	'password'  => 'license_dev',
	'charset'   => 'utf8',
	'collation' => 'utf8_unicode_ci',
	'prefix'    => '',
));

$capsule->setAsGlobal();
$capsule->bootEloquent();