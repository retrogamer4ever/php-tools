<?php

/**
 * This class represents an error in the system that will be used in the log
 */
class Error 
{
	/**
	 * Short version of the actual error generated
	 * 
	 * @access public
	 * @var String
	 */
	public $message;
	
	/**
	 * Code generated that represents the error generated
	 * 
	 * @access public
	 * @var int
	 */
	public $code;
	
	/**
	 * The line and file name were the error actually happened
	 * 
	 * @access public
	 * @var line;
	 */
	public $line;
	
	/**
	 * File name of where the error actually happened
	 * 
	 * @access public
	 * @var String
	 */
	public $file;
	
	/**
	 * This is a complete dump of the stack, gives you info about everything
	 * 
	 * @access public
	 * @var String
	 */
	public $trace;
	
	public function __construct()
	{
		
		$this->message   = "";
		$this->code      = -1;
		$this->line      = -1;
		$this->file      = "";
		$this->trace     = ""; 
	}
  
  
  public function __toString() 
  {
    return "In file ".$this->file." at line ".$this->line." ".$this->message." ".$this->trace;
  }
}

//EOF Error.php