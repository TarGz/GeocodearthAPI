<?php


//error_reporting(0);

include_once( "lib/class.flickr.set.php" ); 
include_once( "lib/class.SimpleXML.php" ); 

$twitterProxy = new FlickrSet('flick_set_72157624770107287', 60 , 5,  '72157624770107287' , '20');

echo $twitterProxy->getProxyContent();

?>