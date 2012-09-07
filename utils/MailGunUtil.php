<?php
require_once dirname(__FILE__).'/../includes.php';

class MailGunUtil
{
  //http://documentation.mailgun.net/api-sending.html
  //http://davidwalsh.name/execute-http-post-php-curl
  public static function email( $to, $subject, $message, $apiKey, $apiUrl, $domainName, $replyEmailAddress )
  {
    $url = $apiUrl."/".$domainName."/messages";
    
    $fields = array( 'from'=>urlencode( $replayEmailAddress ),
                     'to'=>urlencode( $to ),
                     'subject'=>urlencode( $subject ),
                     'html'=>urlencode( $message )
                   );

   
    $fields_string = "";
    
    //url-ify the data for the POST
    foreach( $fields as $key=>$value ) { $fields_string .= $key.'='.$value.'&'; }
    
    rtrim( $fields_string,'&' );

    
    
    //open connection
    $ch = curl_init();

    
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_USERPWD, "api:".$apiKey );
    curl_setopt( $ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC );
    
    //set the url, number of POST vars, POST data
    curl_setopt( $ch,CURLOPT_URL, $url );
    curl_setopt( $ch,CURLOPT_POST, count( $fields ) );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, $fields_string );
      
    //execute post
    $result = curl_exec( $ch );
    
    //close connection
    curl_close( $ch );
    
    return $result;
  }
}

?>
