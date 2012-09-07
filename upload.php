<?php

require_once dirname(__FILE__).'/includes.php';

//Temp location file is stored
$tempLocation   = $_FILES['Filedata']['tmp_name'];
$assetsLocation = "locationOfWhere to uplaod file";

$fileName      = $_REQUEST['fileName'];
$directoryName = $_REQUEST['directoryName'];

$completeUploadPath = $assetsLocation.$directoryName."/".$fileName;

try
{
	//First we need to do some clean up, to make sure we don't get duplicates
	if( @file_exists( $completeUploadPath ) )
	{
		//This will delete the file, adding the @ sign will supress any warnings (just in case)
		@unlink( $completeUploadPath );
	}
	
	//moves file from temp location to our custom location
	if( @move_uploaded_file( $tempLocation, $completeUploadPath ) )
	{
		echo "success";
	}
	else
	{
		echo "failed to upload";

		Logger::log( "Failed to upload to $completeUploadPath" );
	}
}
catch( BREException $e )
{
	Logger::log( "In file ".$e->error->file." at line ".$e->error->line." ".$e->error->message." ".$e->error->trace, $e->error->code );
	
	echo "failed to upload";
}	

?>