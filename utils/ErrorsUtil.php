<?php

require_once dirname(__FILE__).'/../includes.php';

/**
 * Set of utility tools used for handling errors
 *
 * @author JosephBurchett
 */
class ErrorsUtil 
{
	/**
	 * Shortcut for throwing an exception.
	 * 
	 * @param int    $code    Error code for the type of exception thrown
	 * @param String $message Message explaining the error 
	 * 
	 * @return Exception will throw an exception 
	 */
	public static function error( $message, $code )
	{
		throw new BREException( $message, $code );
	}
}

//EOF ErrorsUtil.php