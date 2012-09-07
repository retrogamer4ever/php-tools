<?php

class FileUtil 
{
	public static function copy( $source, $destination )
	{
		$src = $source;
		$des = $destination;
		

		try
		{
			$data = file_get_contents( $src );
			
			$handle = fopen( $des, "w" );
			
			if ( $handle == false )
			{
				Logger::log( "failed to open $destination so we can copy over file contents of $source to it" );
				return false;
			}
			
			fwrite( $handle, $data );
			fclose( $handle );
		}
		catch( Exception $e )
		{
			return false;
		}
		
		return true;
	}
}

?>