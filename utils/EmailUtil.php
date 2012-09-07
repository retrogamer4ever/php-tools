<?php

require_once dirname(__FILE__).'/../includes.php';

class EmailUtil 
{
	public static function email( $to, $subject, $message )
	{
		ValidatorsUtil::isNullOrEmpty( $to ,     "Email" );
		ValidatorsUtil::isNullOrEmpty( $subject, "Email subject" );
		ValidatorsUtil::isNullOrEmpty( $message, "Email message" );

		//This will allow us to send html text
		$header = "MIME-Version: 1.0\n"."Content-type: text/html; charset=iso-8859-1";
		
		
		mail( $to, $subject, $message, $header );
	}
}

?>