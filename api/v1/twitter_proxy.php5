<?php
include 'geoloc.php5';
?>
<rss xmlns:geo="http://www.w3.org/2003/01/geo/wgs84_pos#" version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:media="http://search.yahoo.com/mrss">
  <channel>
    <title>Twitter public timeline in GeoRss</title>
    <link>http://geocodearth.com/api/twitter_proxy.php5</link>
    <description>See what's going on in the Twiter Universe</description>
    <language>en</language>
    <ttl>40</ttl>


<?

$post_data = $HTTP_RAW_POST_DATA;

$header[] = "Content-type: text/xml";
$header[] = "Content-length: ".strlen($post_data);

$ch = curl_init( $_GET['urlpage'] );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
curl_setopt($ch, CURLOPT_USERPWD, "Twittearth:egh5umpv");

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
		  		$a = yahoo_geo($loc);
			  	if($a['Latitude'] != "" && $a['Longitude'] != ""){
		  		//echo "<pre>"; print_r($a); echo "</pre>";
			  		echo "	<item>\n";
		      		echo "		<title>".$status->text."</title>\n";
		      		echo "		<description>".$status->text."</description>\n";
		      		echo "		<geo:lat>".$a['Latitude']."</geo:lat>\n";
		      		echo "		<geo:long>".$a['Longitude']."</geo:long>\n";
			  		echo "		<georss:point>".$a['Latitude']." ".$a['Longitude']."</georss:point>\n";
		      		echo "		<georss:featuretypetag>".$a['Address']."</georss:featuretypetag>\n";
		      		echo "		<georss:featurename>".$a['Country']." ".$a['State']." ".$a['City']."</georss:featurename>\n";
		      		echo "		<pubDate>".$status->created_at."</pubDate>\n";
		      		echo "		<link>http://twitter.com/".$user->screen_name."/statuses/".$status->id."</link>\n";
		      		echo "		<guid>".$status->id."</guid>\n";
			  		echo "	</item>\n";
				}
			}
		}
	}
    //print $response;
}


?>

  </channel>

</rss>
