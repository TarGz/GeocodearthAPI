<?php


//error_reporting(0);

include_once( "lib/class.flickr.gallerie.php" ); 
include_once( "lib/class.SimpleXML.php" ); 

$twitterProxy = new FlickrGallerie('flick_gallerie_proxy_'.$_GET['id'], 1 , 5,  $_GET['id'] , '20');

echo $twitterProxy->getProxyContent();

?>