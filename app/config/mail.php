<?php

return array(

	'driver' => 'smtp',
 
    'host' => 'smtp.gmail.com',
 
    'port' => 587,
 
    'from' => array('address' => 'yuriikrevnyi@gmail.com', 'name' => 'Awesome Laravel 4 Auth App'),
 
    'encryption' => 'tls',
 
    'username' => 'yuriikrevnyi@gmail.com',
 
    'password' => 'qazqazqaz12',
 
    'sendmail' => '/usr/sbin/sendmail -bs',
 
    'pretend' => false,

);
