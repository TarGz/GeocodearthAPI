<?php

include('proxy.class.php');

class brightkitenotesProxy extends proxy {
	
	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5){
		
		parent::__construct($cacheDir, $timeout, $cacheFileCount);
		
	}
	
	public function customWriteCache(){
		$filename = "http://brightkite.com/objects.rss?filters=notes";
		$fo = fopen($filename,"r");
		$content = stream_get_contents($fo);	
		self::writeCache($content);		
		self::readCache();
	}
	
	public function customReadCache(){

		//OverRide before reading	
		self::readCache();
		//OverRide after reading
		
	}
}

?>