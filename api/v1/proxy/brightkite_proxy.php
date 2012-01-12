<?php

include('lib/brightkite.proxy.class.php');

$brightkiteProxy = new brightkiteProxy('brightkite',  20);

echo $brightkiteProxy->getProxyContent();

?>