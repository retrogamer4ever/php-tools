<?php

require_once dirname(__FILE__).'/../includes.php';

class Model
{		
	/**
	 * @var MongoConnection 
	 */
	protected $_mongo;
	
	public function __construct( $collection )
	{
		$this->_mongo = new MongoConnection();
		
		$this->_mongo->getCredentialsFromConfig( ConfigUtil::getConfig() );
	
		$this->_mongo->open();
	
		$this->_mongo->setCollection( $collection );	
	}
}

?>