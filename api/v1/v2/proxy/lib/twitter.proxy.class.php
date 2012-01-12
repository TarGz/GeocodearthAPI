<?php

include('proxy.class.php');
include ('geoloc.php5');

class twitterProxy extends proxy {
	
	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5){
		
		parent::__construct($cacheDir, $timeout, $cacheFileCount);
		
	}
	
	public function customWriteCache(){
		
		//$content = date("H:i:s");
		$content = self::load();
		//echo $content;
		self::writeCache($content);		
		//echo $content;
		self::readCache();
	}
	
	public function customReadCache(){

		//OverRide before reading	
		self::readCache();
		//OverRide after reading
		
	}
	
	public function load(){	
		$filename = "http://twitter.com/statuses/public_timeline.xml";
		$str1 = '<rss xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:media="http://search.yahoo.com/mrss">';
		//echo $str1;
		$str=$str1."<channel>\n";
	 	$str=$str."<title>Twitter public timeline in GeoRss</title>\n";
	    $str=$str."<link>http://www.geocodearth.com/api/proxy/twitter_proxy.php</link>\n";
	    $str=$str."<description>See what's going on in the Twiter Universe</description>\n";
	    $str=$str."<language>en</language>\n";
	    $str=$str."<ttl>40</ttl>\n";

		$post_data = $HTTP_RAW_POST_DATA;
		$header[] = "Content-type: text/xml";
		$header[] = "Content-length: ".strlen($post_data);
		$ch = curl_init( $filename );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//curl_setopt($ch, CURLOPT_USERPWD, "Twittearth:egh5umpv");
		if ( strlen($post_data)>0 ){
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}
		$response = curl_exec($ch);     
		if (curl_errno($ch)) {
		    print curl_error($ch);
		} else {
		    curl_close($ch);
			$xml = simplexml_load_string ($response);
			foreach ($xml->status as $status) {
				foreach ($status->user as $user) {
					$loc = utf8_encode($user->location);
					if($loc != ""){
				  		$a = @yahoo_geo($loc);
					  	if($a['Latitude'] != "" && $a['Longitude'] != ""){
				  		//echo "<pre>"; print_r($a); echo "</pre>";
					  		$str=$str."<item>\n";
				      		$str=$str."<title>".$status->text."</title>\n";
				      		$str=$str."<description>".$status->text."</description>\n";
				      		$str=$str."<geo:lat>".$a['Latitude']."</geo:lat>\n";
				      		$str=$str."<geo:long>".$a['Longitude']."</geo:long>\n";
					  		$str=$str."<georss:point>".$a['Latitude']." ".$a['Longitude']."</georss:point>\n";
				      		$str=$str."<georss:featuretypetag>".$a['Address']."</georss:featuretypetag>\n";
				      		$str=$str."<georss:featurename>".$a['Country']." ".$a['State']." ".$a['City']."</georss:featurename>\n";
				      		$str=$str."<pubDate>".$status->created_at."</pubDate>\n";
				      		$str=$str."<link>http://twitter.com/".$user->screen_name."/statuses/".$status->id."</link>\n";
				      		$str=$str."<author><a href=\"http://twitter.com/".$user->screen_name."/statuses/\">".$user->screen_name."</a></author>\n";
				      		$str=$str."<guid>".$status->id."</guid>\n";
					  		$str=$str."</item>\n";
						}
					}
				}
			}
		}
		$str=$str."</channel>";
		$str=$str."</rss>";	
		return $str;
	}
}

?>