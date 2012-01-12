<?php

include('proxy2.class.php');
include ('geoloc.php5');

class twitterSearchProxyFull extends proxy2 {
	
	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5, $params){
		$this->params = $params;	
		parent::__construct($cacheDir, $timeout, $cacheFileCount);
		
	}
	
	public function customWriteCache($isNew){
		
		//$content = date("H:i:s");
		$content = self::load();
		// DEBUG
		//echo $content;
		self::writeCache($content);		
		//echo $content;
		if($isNew == 1 ){
			self::echoContent($content);
		}else{
			self::readCache();
		}
	}
	
	public function customReadCache(){

		//OverRide before reading	
		self::readCache();
		//OverRide after reading
		
	}
	
	
	// Load
	public function load(){	
		//$filename = "http://search.twitter.com/search.atom?callback=foo&q=@targz";
		//$filename = "http://search.twitter.com/search.atom?geocode=48.856667,2.350987,1000.0km&q=+gucci+%2B+fashion+-mane+near:paris+within:1000km";
		
		
		
		//$filename = "http://search.twitter.com/search.atom?q=+gucci+%2B+fashion";
		
		//$filename = "http://search.twitter.com/search.atom?geocode=48.856667,2.350987,2500.0km&q=gucci";
		
		$location_search_geo = @yahoo_geo($this->params["location"]);
		$searchLatitube = $location_search_geo['Latitude'];
		$searchLongitude = $location_search_geo['Longitude'];


		
		$filename = "http://search.twitter.com/search.atom?q=".$this->params["query"]."&geocode=".$searchLatitube.",".$searchLongitude.",".$this->params["radius"].$this->params["unit"]."&lang=".$this->params["lang"]."&rpp=".$this->params["rpp"]."&page".$this->params["page"]."result_type=".$this->params["result_type"];
		
		$str1 = '<rss xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:media="http://search.yahoo.com/mrss">';
		//echo $str1;
		

		
		$str=$str1."<channel>\n";
		
		/*
			$str=$str."<debug>"; 
			$str=$str.$filename;
			$str=$str."</debug>"; 
			*/
		
	 	$str=$str."<title>Twitter public timeline in GeoRss</title>\n";
	    //$str=$str."<link>".	$filename."</link>\n";
	    $str=$str."<description>##READWRITE## See what's going on in the Twiter Universe</description>\n";
	    $str=$str."<language>en</language>\n";
	    $str=$str."<ttl>40</ttl>\n";


		//$post_data = $HTTP_RAW_POST_DATA;
		$header[] = "Content-type: text/html";
		//$header[] = "Content-length: ".strlen($post_data);
		$ch = curl_init( $filename );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		//if ( strlen($post_data)>0 ){
		  //  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		//}
		$response = curl_exec($ch);  
		
		  
		if (curl_errno($ch)) {
		    print curl_error($ch);
		} else {
		    curl_close($ch);
			$xml = simplexml_load_string ($response);
			

				
			foreach ($xml->entry as $status) {

				$location = $status->children('http://base.google.com/ns/1.0'); 

				//<georss:point>
				if($location != "" ){
					$geoloc = @yahoo_geo($location);
					//$str=$str."GEOOOOOOOOOOOOOOOOOOOOO".$geoloc;
					//$str=$str."latitude::".$geoloc['Latitude']."longitude::".$geoloc['Longitude']."\n";	
					if($geoloc['Latitude'] != "" && $geoloc['Longitude'] != ""){
						
						$tweetId = split(":",$status->id);
						
						$str=$str."<item>\n";
						$str=$str."<title>".$status->title."</title>\n";
			      		$str=$str."<description>".$status->title."</description>\n";
			      		$str=$str."<geo:lat>".$geoloc['Latitude']."</geo:lat>\n";
			      		$str=$str."<geo:long>".$geoloc['Longitude']."</geo:long>\n";
				  		$str=$str."<georss:point>".$geoloc['Latitude']." ".$geoloc['Longitude']."</georss:point>\n";
			      		$str=$str."<georss:featuretypetag>".$geoloc['Address']."</georss:featuretypetag>\n";
			      		$str=$str."<georss:featurename>".$geoloc['Country']." ".$geoloc['State']." ".$geoloc['City']."</georss:featurename>\n";
			      		$str=$str."<pubDate>".$status->published."</pubDate>\n";
			      		$str=$str."<link>".$status->link['href']."</link>\n";
			      		$str=$str."<author><a href=\"".$status->author->uri."\">".$status->author->name."</a></author>\n";
			      		$str=$str."<guid>".$tweetId[2]."</guid>\n";
				  		$str=$str."</item>\n";
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