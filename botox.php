<?php
require_once("/includes/initialize.php");

$API = "AIzaSyBtbI-FULoq19z-078YBratnO3aGVS-RJM";




function scrape_without_proxy($url) {	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	//curl_setopt($ch, CURLOPT_POST, 1); 
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/html;charset=utf-8'));
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');
	$data = curl_exec($ch);
   	if(curl_errno($ch)){  		
	   echo 'Curl error: ' . curl_error($ch) . "\n";
	   return false;
	}
	
	curl_close($ch);
	return $data;
}

function google($post_data) {	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://translate.google.com/translate_a/t?client=t&hl=en&sl=ja&tl=en&ie=UTF-8&oe=UTF-8&multires=1&oc=2&otf=1&ssel=0&tsel=0&pc=1");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
	curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
	curl_setopt($ch, CURLOPT_POST, 1); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data); 
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: text/html;charset=utf-8'));
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; da; rv:1.9.1) Gecko/20090624 Firefox/3.5');
	$data = curl_exec($ch);
   	if(curl_errno($ch)){  		
	   echo 'Curl error: ' . curl_error($ch) . "\n";
	   return false;
	}
	
	curl_close($ch);
	return $data;
}
$url = "http://www.botoxvista.jp/consumer/search/index.html";
$post_data = '';
$sourceData =  scrape_without_proxy($url);
$source = 'ja';
$target = 'en';
$translator = new LanguageTranslator($API);

$targetData = $translator->translate($sourceData, $source, $target);
file_put_contents($targetData, 'file.txt-' . $target);

?>