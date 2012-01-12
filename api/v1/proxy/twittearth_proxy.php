<?php

include('lib/twittearth_proxy.class.php');

$twittearthProxy = new twittearthProxy('twittearth',  480);

echo $twittearthProxy->getProxyContent();

?>