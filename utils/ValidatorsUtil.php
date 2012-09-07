<?php

/**
* Contains a bunch of static data validation functions.<p>
*
* @author Joseph Burchett
*/
class ValidatorsUtil
{
	 //TODO: will toss amf error if not all the needed parameters are set
	
	/****************************************************************************
	* isEmail **/
	/**
	* Checks if value passed is in a valid email format.<p>
	*
	* @param  object  $value     any type of value that needs to be checked.
	* @param  string  $message   name of value being passed, used in error message.
	* @param  bool    $letReturn will determine if it throws an PHException or returns true.
	* @return bool will either return false if not and on success will return true if allowed.
	*/
	public function isEmail( $value, $letReturn = false )
	{
		if( !EmailAddressValidatorUtil::check_email_address( $value ) )
			if( !$letReturn )
				ErrorsUtil::error( "Email is not in a valid email format.", ServiceCodes::INVALID_EMAIL_ERROR, null);
			else
				return false;
		else
			return true;

	}
	
	
	/*	 * **************************************************************************
	 * isClass * */

	/**
	 * Checks if object is an instance of class specified.<p>
	 *
	 * @param  object  $value     any type of value that needs to be checked.
	 * @param  String  $class     class vaue is being validated as
	 * @param  string  $message   name of value being passed, used in error message.
	 * @param  bool    $letReturn will determine if it throws an PummelHeadException or returns true.
	 * @return bool  will either return false if not and on success will return true if allowed.
	 */
	public static function isClass($value, $class, $message, $letReturn = false)
	{
		if ( !is_a( $value, $class ) )
			if ( !$letReturn )
				ErrorsUtil::error( "$message must be an instance of $class.", ServiceCodes::SERVICE_ERROR );
			else
				return false;
		else
			return true;
	}

    /****************************************************************************
	* isNullOrEmpty **/
	/**
	* Checks if a value is null or empty.<p>
	*
	* @param  object  $value     any type of value that needs to be checked.
	* @param  string  $message   name of value being passed, used in error message.
	* @param  bool    $letReturn will determine if it throws an PummelHeadException or returns true.
	* @return bool  will either return false if not and on success will return true if allowed.
	*/
	public static function isNullOrEmpty($value, $message, $letReturn = false, $code = ServiceCodes::SERVICE_ERROR )
	{
		if( $value == null || trim( $value ) == "" )
			if( !$letReturn )
				throw new PHException( "$message can not be blank or null.", $code, null );
			else
				return true;
		else
			return false;
	}
	
	/****************************************************************************
	* isNull **/
	/**
	* Checks if a value is null.<p>
	*
	* @param  object  $value     any type of value that needs to be checked.
	* @param  string  $message   name of value being passed, used in error message.
	* @param  bool    $letReturn will determine if it throws an PummelHeadException or returns true.
	* @return bool  will either return false if not and on success will return true if allowed.
	*/
	public static function isNull($value, $message, $letReturn = false)
	{
		if( $value == null )
			if( !$letReturn )
				throw new PHException( "$message", ServiceCodes::SERVICE_ERROR, null );
			else
				return true;
		else
			return false;
	}

	/****************************************************************************
	* isString **/
	/**
	* Checks if value is a string.<p>
	*
	* @param  object  $value     any type of value that needs to be checked.
	* @param  string  $message   name of value being passed, used in error message.
	* @param  bool    $letReturn will determine if it throws an PummelHeadException or returns true.
	* @return bool  will either return false if not and on success will return true if allowed.
	*/
	public static function isString( $value, $message, $letReturn = false )
	{
		if(!is_string( $value ) )
			if(!$letReturn)
				throw new PHException ( "$message must be text.", ServiceCodes::SERVICE_ERROR, null );
			else
				return false;
		else
			return true;
	}

	/****************************************************************************
	* isNumber **/
	/**
	* Checks if the value is a number (int, decimal, long, etc).<p>
	*
	* @param  object  $value     any type of value that needs to be checked.
	* @param  string  $message   name of value being passed, used in error message.
	* @param  bool    $letReturn will determine if it throws an PummelHeadException or returns true.
	* @return bool  will either return false if not and on success will return true if allowed.
	*/
	public static function isNumber($value, $message, $letReturn = false)
	{
		if(!is_numeric($value))
			if(!$letReturn)
				throw new PHException("$message must be a number.", ServiceCodes::SERVICE_ERROR, null);
			else
				return false;
		else
			return true;
	}

	/****************************************************************************
	* isBoolean **/
	/**
	* Checks if a value is a boolean.<p>
	*
	* @param  object  $value     any type of value that needs to be checked.
	* @param  string  $message   name of value being passed, used in error message.
	* @param  bool    $letReturn will determine if it throws an PummelHeadException or returns true.
	* @return bool  will either return false if not and on success will return true if allowed.
	*/
	public static function isBoolean($value, $message, $letReturn = false)
	{
		if(!is_bool($value))
			if(!$letReturn)
				throw new PHException("$message must be true or false.", ServiceCodes::SERVICE_ERROR, null);
			else
				return false;
		else
			return true;
	}
	 
}

?>