<?php


//error_reporting(0);

include_once( "lib/class.flickr.gallerie.php" ); 
include_once( "lib/class.SimpleXML.php" ); 

$twitterProxy = new FlickrGallerie('flick_gallerie_proxy_7449465-72157625198519698', 1 , 5,  '7449465-72157625198519698' , '20');

echo $twitterProxy->getProxyContent();

?>