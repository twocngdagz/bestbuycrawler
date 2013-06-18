<?php


require_once("/includes/initialize.php");
include_once('simple_html_dom.php');
/*
Product::deleteAll();
$oldtime = time();
echo "Started" . "\n";
exec(PHP . " " . CRAWLER1); 
exec(PHP . " " . CRAWLER2); 
exec(PHP . " " . CRAWLER3); 
exec(PHP . " " . CRAWLER4); 
exec(PHP . " " . CRAWLER5); 
exec(PHP . " " . CRAWLER6); 
$nowtime = time();
echo "Time elapsed: ".time_elapsed($nowtime-$oldtime)."\n";
echo "Finish" . "\n";
*/

//https://www.lendingclub.com/fileDownload.action?file=LoanStats.csv&type=gen
/*
function post() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://localhost/crawler/bestbuy/");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11");
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
   		return false;
	   //return 'Curl error: ' . curl_error($ch) . "\n";
	}
	
	curl_close($ch);
	return $data;
}
*/

function post() {
	$fp = fopen('LoanStat.csv', 'w+');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.lendingclub.com/fileDownload.action?file=LoanStats.csv&type=gen");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11");
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/www.lendingclub.crt");
	curl_setopt($ch, CURLOPT_FILE, $fp);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
   		//return false;
	   return 'Curl error: ' . curl_error($ch) . "\n";
	}
	
	curl_close($ch);
	return $data;
}
//echo post();\
function api() {
	$url = 'http://api.dp.la/v2/api_key/twocngdagz@yahoo.com';
	//$data = 'token=YJoMMTEJ9guGlkacmOFeGw&email=twocngdagz@yahoo.com&last_name=beldia&first_name=Mederic Roy&company_url=http://www.centraleffects.com/&twitter_name=deric beldia&country=Philippines';
	$data = '';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);	
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data); 
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
   		
	   echo 'Curl error: ' . curl_error($ch) . "\n";
	   return false;
	}
	
	curl_close($ch);
	return $data;
}
echo api();
?>