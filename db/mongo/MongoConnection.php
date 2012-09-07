<?php

require_once dirname(__FILE__).'/../../includes.php';

/**
* Creates a database connection to mongodb database<p>
*
* You are given the option to set the database connection information via a
* xml setting file, or you can just set them all manually. Will set the needed
* credentials for the database connection to work. Typically you want to use 
* this deep inside the base model class of some sort.<p>
*
* Example:<p>
*
* If you want to create a database object using Config Object<br>
* $connect = new MongoConnection();<br>
*	$connect->getCredentialsFromConfig( ConfigUtil::getConfig() );<br>
*	$connect->open();<br>
*	$connect->setCollection( "myCollectionName" );
* 
* The config object will read from the config xml file
* or<p>
*
* If you want to create a database object manually<br>
* $connect = new MongoConnection();<br>
* $connect->host     = "localhost";<br>
* $connect->name     = "myCollectionName";<br>
* $connect->username = "myUsername";<br>
* $connect->password = "myPassword";<br>
* $connect->port     = 99999;<br>
* $connect->open();<p>
* $connect->setCollection( "myCollectionName" );
*
* @author Joseph Burchett
*/
class MongoConnection
{
	/**
	* Host name of the database.<p>
	* @access public
	* @var    string
	*/
	public $host;

	/**
	* Username for the database.<p>
	* @access public
	* @var    string
	*/
	public $username;

	/**
	* Pasword for the database.<p>
	* @access public
	* @var    string
	*/
	public $password;

	/**
	* Name of the database.<p>
	* @access public
	* @var    string
	*/
	public $name;
	
	/**
	* port database is running on.<p>
	* @access public
	* @var    string
	*/
	public $port;


	/**
	* The mongo object that contains all the info about the database.<p>
	* @access public
	* @var    Mongo 
	*/
	protected $_mongo;
	
	/**
	* Database object containing all the information about the database you connected to.<p>
	* @access public
	* @var    MongoDB 
	*/
	protected $_mongoDatabase;
	
	/**
	* Database object containing all the information about the database collection you connected to.<p>
	* @access public
	* @var    MongoCollection 
	*/
	protected $_mongoCollection;
	
	/**
	* The iterator used for gathering data when viewing data from a collection.<p>
	* @access public
	* @var    MongoCursor 
	*/
	protected $_mongoCursor;
	
	
	/**
	* Total number of records effected by query execution.
	* @see getCount(), execute()
	* @access protected
	* @var long
	*/
	protected $_count;

	/**
	* Id of last record effected/added in a database, it returns a MongoId object
	* @see getID(), execute()
	* @access protected
	* @var long
	*/
	protected $_id;

	/**
	* Holds an array of objects returned from the database.
	* @see getRecords(), execute()
	* @access protected
	* @var Array
	*/
	protected $_documents;


  /**
   * This will just set the default values for all the properties 
   */
	public function __construct()
	{
		$this->_count     = 0;
		$this->_id        = "";
		$this->_documents = array();
		
		$this->host     = "";
		$this->port     = "";
		$this->username = "";
		$this->password = "";
		$this->name     = "";
		$this->port     = 21701;
		
		$this->_mongo           = null;
		$this->_mongoDatabase   = null;
		$this->_mongoCursor     = null;
		$this->_mongoCollection = null;
	}

	/****************************************************************************
	* validate **/
	/**
	* Used to validate all the proper parameters when creating database object.<p>
	*
	* @see open()
	*/
	protected function validateCredentials()
	{
		if($this->host      == "") { throw new BREException("Must supply a database host",    ServiceCodes::DATABASE_ERROR, null); }
		if($this->username  == "") { throw new BREException("Username must be supplied",      ServiceCodes::DATABASE_ERROR, null); }
		if($this->password  == "" && $this->_UsePassword == true) { throw new BREException("Password must be supplied", ServiceCodes::DATABASE_ERROR, null); }
		if($this->name      == "") { throw new BREException("database name must be supplied", ServiceCodes::DATABASE_ERROR, null); }
		if($this->port      == "") { throw new BREException("database port must be supplied", ServiceCodes::DATABASE_ERROR, null); }
	}

	/****************************************************************************
	* open **/
	/**
	* This will open a new connection to the database.<p>
	*
	* This will attempt to open a new connection, by first validating the credentials
  * then trying to connect to the database.
	*/
	public function open()
	{
		$this->validateCredentials();

		try
		{
			$this->_mongo = new Mongo("mongodb://{$this->username}:{$this->password}@{$this->host}:{$this->port}/{$this->name}", array( "persist" => "x" ) );
			
		}
		catch( BREException $e )
		{
			throw new BREException("A connection to the database could not be established, it could be that the username/password is wrong, an incorrect host was specified or the database server is down.", ServiceCodes::DATABASE_ERROR, null);
		}
		
		$this->setDatabase();
	}
	
	/**
	 * Lets you pass a config object which will set all of the values from that object
	 * 
	 * @param Config $config 
	 */
	public function getCredentialsFromConfig( $config )
	{
		$this->host     = $config->mongoHost;
		$this->port     = $config->mongoPort;
		$this->username = $config->mongoUsername;
		$this->password = $config->mongoPassword;
		$this->name     = $config->mongoDatabase;
	}
	
  /**
   * Will attempt to use the name for the database, if can't find it will throw
   * an error
   * 
   * @param String $name of the database 
   */
	public function setDatabase( $name = "" )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongo, "Mongo Object isn't valid, have you 'open' a  new connection to the database yet?" );
				
		if( ValidatorsUtil::isNullOrEmpty( $name, "Mongo Database must have a name", true ) )
		{
			if( !ValidatorsUtil::isNullOrEmpty( $this->name, "Mongo Database must have a name" ) )
			{
				$name = $this->name;
			}
		}
		
		$this->_mongoDatabase = $this->_mongo->selectDB( $name );
	}
	
  /**
   * Lets you set the collection you want to pull documents from
   * 
   * @param String $name name of the collection 
   */
	public function setCollection( $name )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoDatabase, "Mongo database isn't valid, have you 'open' a new connection to the database yet?" );
		ValidatorsUtil::isNullOrEmpty( $name, "Mongo collection must have a name" );
		
		$this->_mongoCollection = $this->_mongoDatabase->selectCollection( $name );
	}
	
  /**
   * @param array $indexes associative array of all the index you want to set
   * @return boolean lets you know if it set the index 
   */
	public function createIndex( $indexes = array() )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		return $this->_mongoCollection->ensureIndex( $indexes );
	}
	
  
  /**
   * Inserts a new object into the collection
   * 
   * @param Object $value lets you add an object to database
   */
	public function insert( $value )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		ValidatorsUtil::isNull( $value, "Must have a valid object to insert in collection" );
		
		$this->_mongoCollection->insert( $value, true );
	
		
		$this->_count = 0;
	}
	
  /**
   * Will add or update an existing object with a matching _id
   * 
   * @param type $value object to save 
   */
	public function save( $value )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		ValidatorsUtil::isNull( $value, "Must have a valid object to insert in collection" );
		
		$this->_mongoCollection->save( $value );
		
		$this->_count = 0;
	}
	
  /**
   * Will only return back one object
   * 
   * @param Array $criteria the search criteria
   * @return Object Returns back just one object
   */
	public function viewOne( $criteria = array() )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		$document = $this->_mongoCollection->findOne( $criteria );
		
		if ( $document != null ) $this->_count = 1;
		else                     $this->_count = 0;
		
		$this->_documents = $document;
		
		return $document;
	}
	
  /**
   * Shortcut for updating a particular value in a document
   * 
   * @param Array $criteria the set of conditions to be met for update
   * @param Array $fields the fields you wish to update
   * @param boolean $safe Will determine if the update is lazy or not
   * @param boolean $upsert If field doesn't exist add it on the fly
   * @param boolean $multiple update multiple matching quieries
   */
	public function updateWithSet( $criteria = array(), $fields = array(), $safe = true, $upsert = true, $multiple = true )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		$this->_mongoCollection->update( $criteria, array( '$set' => $fields ), array( "safe" => $safe, "upsert" => $upsert, "multiple" => $multiple ) );
		
		$this->_count = 0;
		$this->_id    = "";
	}
	
  /**
   * Will update a document
   * 
   * @param Array $criteria the set of conditions to be met for update
   * @param Array $fields the fields you wish to update
   * @param boolean $safe Will determine if the update is lazy or not
   * @param boolean $upsert If field doesn't exist add it on the fly
   * @param boolean $multiple update multiple matching quieries
   */
	public function update( $criteria = array(), $fields = array(), $safe = true, $upsert = true, $multiple = true )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		try
		{
			$this->_mongoCollection->update( $criteria, $fields, array( "safe" => $safe, "upsert" => $upsert, "multiple" => $multiple ) );
		}
		catch(MongoException $e )
		{
			Logger::log($e);
		}
		
		$this->_count = 0;
		$this->_id    = "";
	}
	
  /**
   * Lets you view data
   * 
   * @param Array $criteria the conditions for the data to be viewed
   * @param Array $sort what values you want to sort by
   * @param int $limit how many documents to return back
   * @param Array $fields what fields you want to return back
   * @return Array all the documents back as associative arrays 
   */
	public function view ( $criteria = array(), $sort = array(), $limit = 100, $fields = array() )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		
		$this->_mongoCursor = $this->_mongoCollection->find( $criteria, $fields );
		
		if( is_array( $sort ) && count( $sort ) != 0 ) $this->_mongoCursor->sort( $sort );
		
		if( is_numeric( $limit ) ) $this->_mongoCursor->limit( $limit );
		
		$counter = 0;
		
		$this->_documents = array();
		
		foreach( $this->_mongoCursor as $document )
		{
			
			$this->_documents[$counter] = $document;
			$counter++;
		}
		
		if( $counter == 0 ) $this->_documents = array();
		
		$this->_count = $counter;
		$this->_id    = "";
		
		return $this->_documents;
	}
	
  /**
   * Gets back the number of documents viewed, if nothing viewed then it's zero
   * 
   * @param Array $criteria the condition for gretting how many back
   * @return int 
   */
	public function count( $criteria = array() )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		$documentCount = 0;
	
		if( count ( $criteria ) != 0 )
			$documentCount = $this->_mongoCollection->count( $criteria );
		else
			$documentCount = $this->_mongoCollection->count();
		
		return $documentCount;
	}
	
  /**
   * Lets you remove documents based on a criteria
   * 
   * @param Array $criteria the conditions for removing documents
   */
	public function remove( $criteria = array() )
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		$this->_mongoCollection->remove( $criteria );
		
		$this->_count = 0;
		$this->_id    = "";
	}
	

	/**
	* Getting the number of rows affected from the database.<p>
	*
	* If data is being pulled from database it will return back the amount of
	* records that came back.  Any other type of query to the database may return
	* just the amount of records affected at that time.
	*
	* @return int how many documents viewed
	*/
	public function getCount()
	{
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		return $this->_count;
	}

	/**
	* Getting all the records pulled from the database.<p>
	*
	* @return Array an array of objects
	*/
	public function getDocuments()
	{ 
		ValidatorsUtil::isNullOrEmpty( $this->_mongoCollection, "Mongo collection isn't valid, have you set a collection?" );
		
		return $this->_documents;
	}
}

?>