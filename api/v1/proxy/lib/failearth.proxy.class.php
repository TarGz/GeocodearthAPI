<?php
include 'geoloc_IP.php5';
include('proxy.class.php');

class ffffoundProxy extends proxy {
	
	public function __construct($cacheDir, $timeout=43200, $cacheFileCount=5){
		
		parent::__construct($cacheDir, $timeout, $cacheFileCount);
		
	}
	
	public function customWriteCache(){
		
		$content = self::load();
				
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
		$str='<rss xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:media="http://search.yahoo.com/mrss">';
		$str =$str.'<channel>';
		$str =$str. '<title>ffffound public timeline in GeoRss</title>';
		$str =$str. '<link>http://geocodearth.com/api/ffffound_proxy.php5</link>';
		$str =$str. '<description>See what s going on ffffound</description>';
		$str =$str. '<language>en</language>';
		$str =$str. '<ttl>40</ttl>';
			

		$library = simplexml_load_file("http://search.twitter.com/search.atom?lang=en&q=%23fail");
		  foreach ($library->channel->item as $entry) {
			  ////// FFFFOUND SOURCE NAMESPACE	
		      $ns_dc = $entry->children('http://ffffound.com/scheme/feed'); 
			  //////// GEOLOC
			  $resultarray = geoIp_geo(getDns($ns_dc[0]->attributes()->url));
			  $geoDesc = $resultarray[0].",".$resultarray[1].",".$resultarray[2].",".$resultarray[3];
			  $lat = $resultarray[4];
			  $long = $resultarray[5];	
			  ////// MEDIA	
			  $ns_dc_media = $entry->children('http://search.yahoo.com/mrss/'); 
				//// RSS
				$str =$str. "<item>\n";	
				$str =$str.  "<title>".$entry->title."</title>\n";
			  //$str =$str.  "<description>".$entry->description."</description>\n";
				$str =$str.  "<description>".$entry->title."</description>\n";
				$str =$str.  "<pubDate>".$entry->pubDate."</pubDate>\n";
				$str =$str.  "<guid>".$ns_dc_media[0]->attributes()->url."</guid>\n";
				$str =$str.  "<author>".$entry->author."</author>\n";
				$str =$str.  "<geo:lat>".$lat."</geo:lat>\n";
				$str =$str.  "<geo:long>".$long."</geo:long>\n";
				$str =$str.  "<georss:point>".$lat." ".$long."</georss:point>\n";
				$str =$str.  "<georss:featuretypetag>".$geoDesc."</georss:featuretypetag>\n";
				$str =$str.  "<georss:featurename>".$geoDesc."</georss:featurename>\n";
				$str =$str.  "<link>".$ns_dc[0]->attributes()->url."</link>\n";
				$str =$str.  "<media:content url=\"".$ns_dc_media[0]->attributes()->url."\" />\n";
				$str =$str.  "<media:thumbnail url=\"".$ns_dc_media[1]->attributes()->url."\" />\n";
				$str =$str.  "</item>\n";	 
		  }   
		
		$str =$str. '</channel></rss>';
		return $str;
	}
}

?>