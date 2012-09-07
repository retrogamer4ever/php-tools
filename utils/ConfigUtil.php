<?php

require_once dirname(__FILE__).'/../includes.php';

/**
 * Set of utility tools used for accesing data from the config file
 *
 * @author JosephBurchett
 */
class ConfigUtil 
{
	/**
	 * gets config object from config xml
	 */
	static public function getConfig()
	{
		$xml = null;
		
		if( file_exists( dirname( __FILE__ )."/../config.xml" ) ) 
		{
			
			$xml = simplexml_load_file( dirname( __FILE__ )."/../config.xml", "Config" );
			
			if ( $xml == null )
			{
				ErrorsUtil::error( "Failed to convert to Config object", -1 );
			}	
		}
		else
		{
			ErrorsUtil::error( "Can't find config", -2 );
		}
		
		return $xml;
	}
	
	/**
	 * get port mongo database is running on
	 */
	static public function getMongoPort()
	{
		$config = self::getConfig();
		
		return (string)$config->mongoPort;
	}
	
	/**
	 * get username mongo database uses
	 */
	static public function getMongoUsername()
	{
		$config = self::getConfig();
		
		return (string)$config->mongoUsername;
	}
	
	/**
	 * get password mongo database uses
	 */
	static public function getMongoPassword()
	{
		$config = self::getConfig();
		
		return (string)$config->mongoPassword;
	}
	
	/**
	 * get database name for mongo
	 */
	static public function getMongoDbName()
	{
		$config = self::getConfig();
		
		return (string)$config->mongoDatabase;
	}
	
	 /**
	 * get database name for mongo
	 */
	static public function getMongoHost()
	{
		$config = self::getConfig();
		
		return (string)$config->mongoHost;
	}
}

//EOF ConfigUtil.php