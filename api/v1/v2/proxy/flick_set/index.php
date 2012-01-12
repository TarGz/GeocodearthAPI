<?php


//error_reporting(0); '72157624770107287'

include_once( "../lib/class.flickr.set.php" ); 
include_once( "../lib/class.SimpleXML.php" ); 

$count = 20;
if ( $_GET['count'] ) {
	$count =  $_GET['count'];
}


$twitterProxy = new FlickrSet('flick_set_'.$_GET['id'].'_'.$count, 600 , 5, $_GET['id']  , '20');

echo $twitterProxy->getProxyContent();

?>