<?php
require_once("/includes/initialize.php");
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

$list = SKU::find_all();
foreach ($list as $item) {
	$product_v2 = new Product_v2();
	$product_v2->SKU = cleanData($item->bbysku);
	$product_v2->UPC = cleanData($item->upc);
	$product_v2->Main_SKU = cleanData($item->productid);
	$product_v2->seller = '';
	$product_v2->shipping = '';
	$product_v2->price = '';
	$product_v2->MatchUPC = 'False';
	$product_v2->Status = 'New';
	$product_v2->Time = time();
	$product_v2->save();
} 

?>