<?php
require_once("/includes/initialize.php");
$option = getopt("x:");
//$file_handle = fopen("C:\wamp\www\crawler\bestbuy\upload\\" . $option['x'], "r");
$file_handle = fopen("C:\wamp\www\crawler\bestbuy\upload\\2013-05-02.txt", "r");
Product_v2::deleteAll();

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


while (!feof($file_handle)) {
	$content = fgets($file_handle);
	if (!IsNullOrEmptyString($content)) {
		$SKU_array = explode("\t", $content);
		$SKU = $SKU_array[2];
		$UPC = $SKU_array[1];
		$Main_SKU = $SKU_array[0];
		$product_v2 = new Product_v2();
		$product_v2->SKU = cleanData($SKU);
		$product_v2->UPC = cleanData($UPC);
		$product_v2->Main_SKU = cleanData($Main_SKU);
		$product_v2->seller = '';
		$product_v2->shipping = '';
		$product_v2->price = '';
		$product_v2->MatchUPC = 'False';
		$product_v2->Status = 'New';
		$product_v2->Time = time();
		$product_v2->save();
	}
}
?>