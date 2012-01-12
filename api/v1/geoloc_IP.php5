<?php
function getDns($url) {
	$dns = split("/",mb_substr($url,7,strlen($url)));
	return $dns[0];
}

function geoIp_geo($domain) {
  	$q = 'http://api.geoio.com/q.php?key=d72ljq7haHdWQwx7&qt=geoip&d=pipe&q='.gethostbyname("$domain");
	$lines = file($q);
	$ligne = $lines[0];
	$ar = split("\|",$ligne);
	return $ar;
}
?>