<?php namespace License\Exceptions;


class BaseException extends \Exception {
	
	protected $_params;

    
    public function __construct($message = "", $code = 0 , Exception $previous = NULL, $params = NULL)
    {
        $this->_params = $params;

        parent::__construct($message, $code, $previous);
    }


    public function getParams()
    {
        return $this->_params;
    }
    
}