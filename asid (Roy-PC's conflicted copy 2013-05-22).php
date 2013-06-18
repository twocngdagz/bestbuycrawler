<?php
require_once("/includes/initialize.php");
function scrape_without_proxy($url) {
	sleep(1);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
	   	return false;
	}
	curl_close($ch);
	return $data;	
}


?>