<?php
/*
$file_handle = fopen("active BB shadows with UPCs and BBY SKUs.txt", "r");
$i = 1;
while (!feof($file_handle)) {
	   $string = explode("\t", fgets($file_handle));
	   echo "<pre>";
	   echo $string[1];
	   echo "</pre>";
	   /*
	   $string = $string[1];
	   echo $i. "\t" . $string . "\n";
	   $i++; 
}*/
/*
include_once('simple_html_dom.php');




function scrape($url, $ip, $port) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
	curl_setopt($ch, CURLOPT_PROXY, $ip.':'.$port);
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
	   echo 'Curl error: ' . curl_error($ch) . "\n";
	}
	curl_close($ch);
	return $data;
}


$ip = '210.242.85.95';
$ip_array = array('210.242.85.95:808','81.27.79.250:8080', '41.189.36.26:3128', '200.223.34.210:3128', '80.242.214.149:3128'); 
$port = '808';
$SKU = '1304789813';
//$url = "http://www.bestbuy.com/site/searchpage.jsp?_dyncharset=ISO-8859-1&_dynSessConf=-3052187188385421762&id=pcat17071&type=page&st=". $SKU . "&sc=Global&cp=1&nrp=15&sp=&qp=&list=n&iht=y&usc=All+Categories&ks=960";
$url = "http://checkip.dyndns.org/";


for ($i = 1; $i <= 10; $i++) {
	$html = str_get_html(scrape($url, $ip, $port));
	if ($html) {
		$body = $html->find('body', 0);
		$ipaddress =  $body->innertext;
		echo $ipaddress . "\n";
	}
	sleep(15);
}
*/

/*
if (IsNullOrEmptyString($str)) {
	echo "NULL";
}
*/

include_once('simple_html_dom.php');
require_once("/includes/initialize.php");
function getProxy() {
	$proxies = Proxy::find_all();
	$chosen = rand(0, count($proxies)-1);
	$ip = $proxies[$chosen]->ip;
	$port = $proxies[$chosen]->port;
	return $ip . ':' . $port;
}
$cookie_file_path = "C:/wamp/www/crawler/bestbuy/cookie.txt";
$fp = fopen($cookie_file_path,'w');
fclose($fp);

function post_scrape($url, $ip) {
	global $ip;
	global $cookie_file_path;
	$data = 'ac=on&c%5B%5D=China&c%5B%5D=Indonesia&c%5B%5D=Brazil&c%5B%5D=United+States&c%5B%5D=Russian+Federation&c%5B%5D=Venezuela&c%5B%5D=Colombia&c%5B%5D=Thailand&c%5B%5D=Iran&c%5B%5D=Argentina&c%5B%5D=Ecuador&c%5B%5D=India&c%5B%5D=Ukraine&c%5B%5D=Germany&c%5B%5D=Chile&c%5B%5D=Poland&c%5B%5D=Peru&c%5B%5D=Hong+Kong&c%5B%5D=France&c%5B%5D=Turkey&c%5B%5D=Serbia&c%5B%5D=Iraq&c%5B%5D=Ghana&c%5B%5D=Nigeria&c%5B%5D=Bangladesh&c%5B%5D=Czech+Republic&c%5B%5D=United+Kingdom&c%5B%5D=Korea%2C+Republic+of&c%5B%5D=Hungary&c%5B%5D=Pakistan&c%5B%5D=Netherlands&c%5B%5D=Moldova%2C+Republic+of&c%5B%5D=Bulgaria&c%5B%5D=Kenya&c%5B%5D=Philippines&c%5B%5D=Taiwan&c%5B%5D=Egypt&c%5B%5D=Cambodia&c%5B%5D=Luxembourg&c%5B%5D=Taiwan&c%5B%5D=Canada&c%5B%5D=Latvia&c%5B%5D=Italy&c%5B%5D=Slovakia&c%5B%5D=United+Arab+Emirates&c%5B%5D=Mexico&c%5B%5D=Kazakhstan&c%5B%5D=Japan&c%5B%5D=Spain&c%5B%5D=Malaysia&c%5B%5D=Denmark&c%5B%5D=Croatia&c%5B%5D=Romania&c%5B%5D=Switzerland&c%5B%5D=Georgia&c%5B%5D=Viet+Nam&c%5B%5D=Australia&c%5B%5D=Lebanon&c%5B%5D=Guatemala&c%5B%5D=Bolivia&c%5B%5D=South+Africa&c%5B%5D=Tanzania%2C+United+Republic+of&c%5B%5D=Zimbabwe&c%5B%5D=Brunei+Darussalam&c%5B%5D=Macedonia&c%5B%5D=Namibia&c%5B%5D=Qatar&c%5B%5D=Albania&c%5B%5D=Saudi+Arabia&c%5B%5D=Norway&c%5B%5D=Netherlands+Antilles&c%5B%5D=Paraguay&c%5B%5D=Greece&c%5B%5D=Singapore&c%5B%5D=Ireland&c%5B%5D=Sweden&c%5B%5D=Belgium&c%5B%5D=Lithuania&c%5B%5D=Libyan+Arab+Jamahiriya&c%5B%5D=Israel&c%5B%5D=Palestinian+Territory%2C+Occupied&c%5B%5D=Sudan&c%5B%5D=Panama&c%5B%5D=Puerto+Rico&c%5B%5D=Azerbaijan&c%5B%5D=Armenia&c%5B%5D=Afghanistan&c%5B%5D=Slovenia&c%5B%5D=Sri+Lanka&c%5B%5D=El+Salvador&c%5B%5D=Cote+D%27Ivoire&p=&pr%5B%5D=0&a%5B%5D=3&a%5B%5D=4&pl=on&sp%5B%5D=3&ct%5B%5D=3&s=0&o=0&pp=2&sortBy=date';
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
   		return false;
	   //return 'Curl error: ' . curl_error($ch) . "\n";
	}
	
	curl_close($ch);
	return $data;
}

function scrape($url, $ip) {
	global $ip;
	global $cookie_file_path;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);	
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Cache-Control: max-age=0', 
	'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8', 
	'Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.3',
	'Accept-Encoding: gzip,deflate,sdch', 
	'Accept-Language: en-US,en;q=0.8', 
	'Connection: keep-alive',
	//'Cookie: PHPSESSID=e7grmb4lsmbne6qg0poq55bts1; __utma=82459535.13069492.1355751942.1355865026.1355925284.6; __utmb=82459535.4.10.1355925284; __utmc=82459535; __utmz=82459535.1355751942.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none)',
	'Host: www.hidemyass.com'));
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
   		return false;
	   //return 'Curl error: ' . curl_error($ch) . "\n";
	}
	
	curl_close($ch);
	return $data;
}

$ip = '';
$url = 'http://www.hidemyass.com/proxy-list/search-225555';
$url_post = 'http://www.hidemyass.com/proxy-list/';
while (!$data = post_scrape($url_post, $ip)) {
	$ip = getProxy();
}
while (!$data = scrape($url, $ip)) {
	$ip = getProxy();
}
echo $data;




?>