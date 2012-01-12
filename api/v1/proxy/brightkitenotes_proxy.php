<?php

include('lib/brightkitenotes.proxy.class.php');

$brightkitenotesProxy = new brightkitenotesProxy('brightkitenotes',  20);

echo $brightkitenotesProxy->getProxyContent();

?>