<?php


error_reporting(0);


include('../lib/twitter.search.proxy.class.php');

$q =  $_GET['q'];
$location =  $_GET['location'];

str_replace("%body%", "black", "<body text='%body%'>");
$qs = str_replace(" ", "+", $q);
$qn = str_replace(" ", "_", $q);


//$twitterSearchProxy = new twitterSearchProxy('twitter_geo_search', 270);
$twitterSearchProxy = new twitterSearchProxy('twitter_geo_search_'.$location.'_'.$qn,270,5,$location,$qs);

echo $twitterSearchProxy->getProxyContent();

?>