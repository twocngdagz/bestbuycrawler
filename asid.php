<?php
require_once("/includes/initialize.php");

function cleanData(&$str)  {
    $str = preg_replace("/\t/", "", $str);
	$str = preg_replace("/\n/", "", $str);
	$str = preg_replace("/\r\n/", "", $str);
    $str = preg_replace("/\r?\n/", "", $str);
    $str = preg_replace("/\r/", "", $str);
    $str = trim($str, "(");
    $str = trim($str, ")");
	$str = trim($str, ";");
	$str = trim($str, ","); 
	$str = trim($str, "   ");
	$str = trim($str, "&nbsp;");
	$str = trim($str, "&mdash;");
	return ($str);
    //if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    //121776394/3089
}

function scrape_without_proxy($url) {
	$cookie_file_path = "C:/wamp/www/crawler_/bestbuy/cookie.txt";
	$data = "field_first_name_value=&field_last_name_value=&field_project_type_tid=24&field_profile_services_offered_value=&city=&province=Alaska&postal_code=&rid=All&distance%5Bsearch_units%5D=mi&distance%5Bsearch_distance%5D=32000";
	sleep(1);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file_path);
	curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
	   	return false;
	}
	curl_close($ch);
	return $data;	
}
$result = array();
$base_url = "http://asid.org/";
$project_type = "Residential";
$html =  str_get_html(scrape_without_proxy("http://asid.org/referral-service?field_first_name_value=&field_last_name_value=&field_project_type_tid=24&field_profile_services_offered_value=&city=&province=Alaska&postal_code=&rid=All&distance%5Bsearch_units%5D=mi&distance%5Bsearch_distance%5D=32000"));
$view_content = $html->find('div.view-content', 0);
$data = array();
foreach ($view_content->children() as $child) {
	$div = $child->children(0);
	$field = $div->children(0);
	$profile = $field->children(0);
	$profile_url =  $base_url . $profile->href;
	$profile_html = str_get_html(scrape_without_proxy($profile_url));
	$user_profile = $profile_html->find('div.user-profile', 0);
	$content = $user_profile->find('div.content', 0);
	$profile_information = $content->children(1);
	$field_content = $profile_information->find('div.field-content',0);
	$name = $field_content->innertext;
	$profile_name = explode(",", $name);
	$result['name'] = $profile_name[0];
	$profile_middle = $content->children(2);
	$company = $profile_middle->find('span.field-content',0);
	$result['company'] = $company->innertext;
	$profile_right = $content->children(3);
	$email = $profile_right->find('div.field-content a', 0);
	$result['email'] = $email->innertext;
	$website = $profile_right->find('div.field-content a', 1);
	if ($website) {
		$result['website'] = $website->innertext;
	} else {
		$result['website'] = "";
	}
	$result['project_type'] = $project_type;
	$sidebar = $user_profile->find('div[id=sidebar-first]', 0);
	$div_about = $sidebar->find('div.about', 0);
	$about = $div_about->find('div.details-content',0)->innertext;
	$result['about'] = cleanData($about);
	$div_services = $sidebar->find('div.services div.details-content', 0)->innertext;
	$result['services'] = cleanData($div_services);
	$awards = $sidebar->find('div.awards div.details-content', 0)->innertext;
	$result['awards'] = cleanData($awards);
	$fee = $sidebar->find('div.fee div.details-content', 0)->innertext;
	$result['fee'] = cleanData($fee);
	$data[] = $result;
}

$file = 'C:\wamp\www\crawler_\bestbuy\result.txt';
foreach ($data as $record) {
	$content = "";
	$content = $record['name'] . "\t" . $record['company'] . "\t" . $record['email'] . "\t" . $record['website'] . "\t" . $record['project_type'] . "\t" . $record['about'] . "\t" .$record['services'] . "\t" . $record['awards'] . "\t" . $record['fee'] . "\n";
	if($handle=fopen($file, 'a')) {
		fwrite($handle, $content);
		fclose($handle);
	}	
}

?>