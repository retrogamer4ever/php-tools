<?php

require_once dirname(__FILE__).'/../includes.php';



/**
 * Description of DateUtil
 *
 * @author retrogamer4ever
 */
class DateUtil 
{
	public static function addToLocalDateString( $hours = 0, $minutes = 0, $seconds = 0 )
	{
		return date('Y-m-d g:h:i:s', mktime( date("h") + (int)$hours, date("i") + (int)$minutes, date("s") + (int)$seconds, date("m"), date("d"), date("Y") ) );
	}
	
	public static function addToLocalDateUnixTimeStamp( $days = 0, $hours = 0, $minutes = 0, $seconds = 0 )
	{
		return mktime( date("h") + (int)$hours, date("i") + (int)$minutes, date("s") + (int)$seconds, date("m"), date("d") + (int)$days, date("Y") );
	}
	
	public static function addToLocalDateUnixTimeStampShortcut( $value, $what )
	{	
		$unixTimeStamp = 0;
		
		switch ( $what )
		{
			case "days":
				$unixTimeStamp = 0;
				break;
			case "hours":
				$unixTimeStamp = 0;
				break;
			case "minutes":
					$unixTimeStamp = 60 * $value;
				break;
			case "seconds":
				$unixTimeStamp = 0;
				break;
		}
		
		return $unixTimeStamp;
	}
	
	//http://www.php.net/manual/en/datetime.formats.date.php
	public static function getUnixTimeStamp()
	{	
		return time();
	}
	
	public static function differenceInSeconds( $date1UnixTimeStamp = 0, $date2UnixTimeStamp = 0 )
	{
		$difference = $date1UnixTimeStamp - $date2UnixTimeStamp;

		if( $difference <= 0 ) $difference = 0;

		return $difference;
	}
	
	public static function getLocalDateString()
	{
		return date('Y-m-d g:h:i:s');
	}
}

?>