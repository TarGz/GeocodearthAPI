<?php
//error_reporting(E_ALL);
//ini_set('display_errors','On');
include('../lib/twitter.search.proxy.full.class.php');

// Query Exmaple
/*

Find tweets containing a word: http://search.twitter.com/search.atom?q=twitter
Find tweets from a user: http://search.twitter.com/search.atom?q=from:alexiskold
Find tweets to a user: http://search.twitter.com/search.atom?q=to:techcrunch
Find tweets referencing a user: http://search.twitter.com/search.atom?q=@mashable
Find tweets containing a hashtag: http://search.twitter.com/search.atom?q=#haiku
Combine any of the operators together: http://search.twitter.com/search.atom?q=movie :)

http://api.geocodearth.com/v2/proxy/twitter_search_georss_full/?q=from:targz&location=PARIS&lang=fr&lang=en&rpp=100&unit=km&radius=2500&page=1&result_type=mixed

*/


// The query
//$qs = str_replace("_", "%20", urlencode($_GET['q']));
$qs = str_replace("_", "+", $_GET['q']);
$qs = str_replace("*", "%23", $qs);
$qs = str_replace("@", "%40", $qs);



// The query with _ for the folder name
$qn = $_GET['q'];
$qn = str_replace("*", "H_", $qn);
$qn = str_replace("+", "_", $qn);
$qn = str_replace("%20", "_", $qn);
$qn = str_replace("@", "AT_", $qn);
$qn = str_replace("from:", "from_", $qn);

// Params Array
$params = array ('query' => $qs,
				'location' => $_GET['location'],
				'lang' => $_GET['lang'],
				'rpp' => $_GET['rpp'],
				'page' => $_GET['page'],
				'result_type' => $_GET['result_type'],
				'unit' => $_GET['unit'],
				'radius' => $_GET['radius']); 

// trick gor generating the folder name
$params["query"] = $qn;
$folderPath = implode("_", $params);
// Back to the real query
$params["query"] = $qs;




// Let's gor for the proxy
$twitterSearchProxyFull = new twitterSearchProxyFull('TGS_'.$folderPath,100,5,$params);
echo $twitterSearchProxyFull->getProxyContent();

?>