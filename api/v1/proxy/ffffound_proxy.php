<?php

include('lib/ffffound.proxy.class.php');

$ffffoundProxy = new ffffoundProxy('ffffound',  3200);

echo $ffffoundProxy->getProxyContent();

?>