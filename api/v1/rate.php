<?php
		echo "...<br>";
		$filename = "http://twitter.com/account/rate_limit_status.xml";

		$header[] = "Content-type: text/xml";
		$header[] = "Content-length: ".strlen($post_data);

		$ch = curl_init( $filename ); 

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);


		if ( strlen($post_data)>0 ){
		    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		}

		$response = curl_exec($ch);     
		//$content = stream_get_contents($response);
		echo $response;	

?>