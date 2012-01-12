<?php

include('lib/failearth.proxy.class.php');

$failEarth = new failEarth('failearth', 60);

echo $failEarth->getProxyContent();

?>