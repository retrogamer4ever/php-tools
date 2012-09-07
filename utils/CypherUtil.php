<?php


	class CypherUtil
	{
		public static function encrypt( $input, $secureKey )
		{
			//$secureKey = hash('sha256', $secureKey, TRUE);
			
			return base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, $secureKey, $input, MCRYPT_MODE_ECB ) );
		}
		
		public static function decrypt( $input, $secureKey ) 
		{
			//$secureKey = hash('sha256', $secureKey, TRUE);
			
			return trim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, $secureKey, base64_decode( $input ), MCRYPT_MODE_ECB ) );
		}
	}

?>