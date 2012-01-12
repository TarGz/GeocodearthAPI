<?php


error_reporting(0);


include('../lib/twitter.proxy.class.php');

$twitterProxy = new twitterProxy('twitter_geo', 270);

echo $twitterProxy->getProxyContent();

?>