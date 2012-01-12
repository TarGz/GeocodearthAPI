<?php


error_reporting(0);


include('lib/twitter.proxy.class.php');

$twitterProxy = new twitterProxy('twitter', 480);

echo $twitterProxy->getProxyContent();

?>