<?php

require_once dirname(__FILE__).'/../includes.php';



/**
* Creates a log file on the system to keep track of any issues.<p>
*
* Note: Will need to first set the location where the log file will be
* created or it will throw an BREException when trying to create a new log.<p>
*
* Example:
* $logger = new Logger();
* $logger->setLocation("");
* $logger->message(11000, "Test"); <p>
*
* Will create new log file with the error code 11000, and message 'test'
* in whatever directory you are currently in.<p>
*
* @author Joseph Burchett
*/
class Logger
{
	/**
	* Contains information about the location of where the log file is created.
	* @see    getLocation()
	* @access protected
	* @var    string
	*/
	protected $m_FullPath;

	/**
	* Contains the name of the log file stored on the server.
	* @see    getFileName()
	* @access protected
	* @var    string
	*/
  protected $m_FileName;


	/****************************************************************************
	* constructor **/
	/**
	* Creates a log file on the system to keep track of any issues.<p>
	*
	*	Note: Will need to first set the location where the log file will be
	* created or it will throw an BREException when trying to create a new log.<p>
	*
	* Example:<br>
	* $logger = new Logger();<br>
	* $logger->setLocation("");<br>
	* $logger->message(11000, "Test"); <p>
	*
	* Will create new log file with the error code 11000, and message 'test'
	* in whatever directory you are currently in.<p>
	*
	* @author Joseph Burchett
	*/
	public function __construct($location)
	{
		$this->setLocation($location);
		$this->m_FileName  = "";
	}

	/****************************************************************************
	* getFileName **/
	/**
	* Getting the file name of the created log file.<p>
	*
	* @return string file name of log file.
	*/
	public function getFileName()
	{
		return $this->m_FileName;
	}

	/****************************************************************************
	* getLocation **/
	/**
	* Getting the full location path of the new log file
	*
	* @return string location.
	*/
	public function getfullPath()
	{
		return $this->m_FullPath;
	}

	/****************************************************************************
	* message **/
	/**
	* Log Message that will be written to the location specified.<p>
	*
	* Will first create a special log message with the date and time in it,
	* After this is done will check if file exists in the "location" you
	* specified. If can't find file, will throw an BREException.
	*
	* @param int    $code    code error code related to "message"
	* @param string $message describing the cause of the error
	*/
	public function message($code, $message)
	{
		$logMsg = date("m-d-y h:m:s")." Code:".$code." Log: ".$message."\r\n";

		if(file_exists($this->m_FullPath))
		{
			$handler = fopen($this->m_FullPath, "a");
			fwrite($handler, $logMsg);
			fclose($handler);
		}
		else
			ErrorsUtil::error( "Can't find log file at ".$this->m_FullPath,	ServiceCodes::LOGGER_ERROR );
	}
	
	public static function log( $message, $code = -1 )
	{
		$logsLocation = ConfigUtil::getLogsPath();
		$logger = new Logger( $logsLocation );
		$logger->message( $code, print_r( $message, true ) );
	}

	
	/****************************************************************************
	* setLocation **/
	/**
	* Sets the location of the log and creates it if one doesn't exist.
	*
	* After setting the new location it will look to see if for that location
	* on the file system already has a log with the current date, then it will
	* do nothing.  If it can not find one with current date, will then create it.<p>
	*
	*@param string $location location of the log file.
	*/
	protected function setLocation($location)
	{ 
		$this->m_FileName = "log_".date( "m-d-y" ).".txt";

		$fullPath = $location."/".$this->m_FileName;
		
		$this->m_FullPath = $fullPath;

		if( !file_exists( $fullPath ) )
		{
			$handler = fopen( $fullPath, "w" );
			fclose($handler);
		}
	}
}

?>