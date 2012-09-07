<?php

require_once dirname(__FILE__).'/../includes.php';

class RandomUtil 
{
	/**
	* Will generate a random token.<p>
	*
	*Author: Peter Mugane Kionga-Kamau<br>
	*http://www.pmkmedia.com<br>
	*http://www.codewalkers.com/c/a/User-Management-Code/random-string-generator-key-generator/<br>
	*Description: string str_makerand(int $minlength, int $maxlength, bool $useupper, bool $usespecial, bool $usenumbers)<br>
	*returns a randomly generated string of length between $minlength and $maxlength inclusively.<p>
	*
	*	Notes:<br>
	*	- If $useupper is true uppercase characters will be used; if false they will be excluded.<br>
	*	- If $usespecial is true special characters will be used; if false they will be excluded.<br>
	*	- If $usenumbers is true numerical characters will be used; if false they will be excluded.<br>
	*	- If $minlength is equal to $maxlength a string of length $maxlength will be returned.<br>
	*	- Not all special characters are included since they could cause parse errors with queries.<p>
	*
	*	Modify at will.<br>
	*
	* @param long $minlength how smallest amount of characters the token should be
	* @param long $maxlength how biggest amount of characters the token should be
	* @param bool $useupper if upper case charaters should be used
	* @param bool $usespecial if special characters should be used
	* @param bool $usenumbers if numbers should be used
	* @return string randomly generated users token
	*/
	public static function generateRandomToken ( $minlength, $maxlength, $useupper, $usespecial, $usenumbers )
	{
		$key = "";
		$charset = "abcdefghijklmnopqrstuvwxyz";
		if ($useupper)   $charset .= "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		if ($usenumbers) $charset .= "0123456789";
		if ($usespecial) $charset .= "~@#$%^*()_+-={}|]["; // Note: using all special characters this reads: "~!@#$%^&*()_+`-={}|\\]?[\":;'><,./";
		if ($minlength > $maxlength) $length = mt_rand ($maxlength, $minlength);
		else $length = mt_rand ($minlength, $maxlength);
		for ($i=0; $i<$length; $i++) $key .= $charset[(mt_rand(0,(strlen($charset)-1)))];


		return md5( $key );
	}
}

?>