<?php

require_once dirname(__FILE__).'/thirdparty/Zend/Loader.php';



/**
 * by including this file, it will allow for any classes to be lazy loaded in
 * meaning do not need to include everything the zend loader will do all the 
 * heavy lifting for you ;-) Just do a require_once in any of the files you make
 * 
 * Will only work with files reading .php not fileName.class.php	
 *
 * @param String class Name of the class that will try and be loaded 
 */
function __autoload( $class )
{
	//This way we don't include the same class more then once, php throws an 
	//exception if we including  same file more then once.
	if( class_exists( $class ) == true )
	{
		return;
	}
	
	//Zend loader looks through all of the folders specified for class to load
	//Add more folders you wish to be automatically included
	Zend_Loader::loadClass( $class,
	array
	(
		dirname(__FILE__).'/utils',
		dirname(__FILE__).'/errors',
		dirname(__FILE__).'/data',
		dirname(__FILE__).'/models',
		dirname(__FILE__).'/logs',
		dirname(__FILE__).'/db/mongo',
		dirname(__FILE__).'/thirdparty'
	));
	
	
}

?>