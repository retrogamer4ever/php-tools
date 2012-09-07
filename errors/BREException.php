<?php

require_once dirname(__FILE__).'/../includes.php';

/**
 * Exception to be thrown when ever an error happens with in the system
 */
class BREException extends Exception
{
	/**
	 * This object contains all the information about an error, for easy logging
	 * 
	 * @access public
	 * @var Error 
	 */
	public $error;
	
	public function getCommon()
	{
		$common = new Common();
		
		$common->data   = $this->error;
		$common->code   = $this->error->code;
		$common->status = $this->error->message;
		
		return $common;
	}
	
	public function __construct( $message, $code )
	{
		parent::__construct( $message, $code, null );
		
		$this->error = new Error();
	
		$this->error->code    = $this->code;
		$this->error->file    = $this->file;
		$this->error->line    = $this->line;
		$this->error->message = $this->message;
		$this->error->trace   = $this->getTraceAsString();
		
    //If errors happen need to let devs know!
	//EmailUtil::email( "errorsEmail", "Error Code:".$this->error->code, $this->error->__toString() );
	}
}

?>