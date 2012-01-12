<?
//set_time_limit(0);
echo "bite";
/*
$query_data = array('track' => 'iphone');
$user = 'targz';	// replace with your account
$pass = 'egh5umpv28';	// replace with your account

$fp = fsockopen("stream.twitter.com", 80, $errno, $errstr, 30);
if(!$fp){
	print "$errstr ($errno)\n";
} else {
	$request = "GET /1/statuses/filter.json?" . http_build_query($query_data) . " HTTP/1.1\r\n";
	$request .= "Host: stream.twitter.com\r\n";
	$request .= "Authorization: Basic " . base64_encode($user . ':' . $pass) . "\r\n\r\n";
	fwrite($fp, $request);
	while(!feof($fp)){
		$json = fgets($fp);
		$data = json_decode($json, true);
		if($data){
			//
			// Do something with the data!
			//
			echo $data;
		}
	}
	fclose($fp);
}
*/

?>