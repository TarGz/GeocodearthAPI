<?php

include('proxy.class.php');

class twittearthProxy extends proxy {
	
	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5){
		
		parent::__construct($cacheDir, $timeout, $cacheFileCount);
		
	}
	
	public function customWriteCache(){
		$filename = "http://twitter.com/statuses/public_timeline.xml";

		$header[] = "Content-type: text/xml";
		$header[] = "Content-length: ".strlen($post_data);

		$ch = curl_init( $filename ); 
		//http://twittearth.com/twitter_proxy.php?urlpage=http://twitter.com/statuses/public_timeline.xml?5.221112145110965
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//curl_setopt($ch, CURLOPT_USERPWD, "TarGz2:pulppulp");

		if ( strlen($post_data)>0 ){
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}

		$response = curl_exec($ch);     
		//$content = stream_get_contents($response);
		//echo $response;	
		self::writeCache($response);		
		self::readCache();
	}
	
	public function customReadCache(){

		//OverRide before reading	
		self::readCache();
		//OverRide after reading
		
	}
}

?>