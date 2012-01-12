<?php

include('lib/ffffound.proxy.class.php');

$ffffoundProxy = new ffffoundProxy('ffffound',  200);

echo $ffffoundProxy->getProxyContent();

?>