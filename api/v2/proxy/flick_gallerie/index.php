<?php


//error_reporting(0);

include_once( "../lib/class.flickr.gallerie.php" ); 
include_once( "../lib/class.SimpleXML.php" ); 

$count = 100;
if (  isset( $_GET['count'] ) ) {
	$count =  $_GET['count'];
}

$twitterProxy = new FlickrGallerie('flick_gallerie_'.$_GET['id'].'_'.$count , 1 , 5,  $_GET['id'] , $count );

echo $twitterProxy->getProxyContent();

?>