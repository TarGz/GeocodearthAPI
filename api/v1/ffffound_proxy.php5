<rss xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:media="http://search.yahoo.com/mrss">
<channel>
<title>ffffound public timeline in GeoRss</title>
<link>http://geocodearth.com/api/ffffound_proxy.php5</link>
<description>See what's going on ffffound</description>
<language>en</language>
<ttl>40</ttl>
<?php
include 'geoloc_IP.php5';
$library = simplexml_load_file($_GET['urlpage']);
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
		echo "<item>\n";	
		echo "<title>".$entry->title."</title>\n";
	  //echo "<description>".$entry->description."</description>\n";
		echo "<description>".$entry->title."</description>\n";
		echo "<pubDate>".$entry->pubDate."</pubDate>\n";
		echo "<guid>".$ns_dc_media[0]->attributes()->url."</guid>\n";
		echo "<author>".$entry->author."</author>\n";
		echo "<geo:lat>".$lat."</geo:lat>\n";
		echo "<geo:long>".$long."</geo:long>\n";
		echo "<georss:point>".$lat." ".$long."</georss:point>\n";
		echo "<georss:featuretypetag>".$geoDesc."</georss:featuretypetag>\n";
		echo "<georss:featurename>".$geoDesc."</georss:featurename>\n";
		echo "<link>".$ns_dc[0]->attributes()->url."</link>\n";
		echo "<media:content url=\"".$ns_dc_media[0]->attributes()->url."\" />\n";
		echo "<media:thumbnail url=\"".$ns_dc_media[1]->attributes()->url."\" />\n";
		echo "</item>\n";	 
  }   
?> 
</channel>
</rss>