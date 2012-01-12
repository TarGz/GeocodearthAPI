<?php

include('lib/twitter.proxy.class.php');

$twitterProxy = new twitterProxy('test',  30);

echo $twitterProxy->getProxyContent();

?>