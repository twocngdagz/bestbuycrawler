<?php

require_once("/includes/initialize.php");
//$products = Product::find_all();
ini_set('max_execution_time', 300);
$products = Product_v2::find_all_by_where("Status", "Done");
$strResult= "";			
foreach($products as $product) {
	$strResult .= '<tr>';	  
	$strResult .= '<td>' . $product->SKU . '</td>';
	$strResult .= '<td>' . $product->UPC . '</td>';
	$strResult .= '<td>' . $product->Main_SKU . '</td>';
	$strResult .= '<td>' . $product->price . '</td>';
	$strResult .= '<td>' . $product->seller . '</td>';
	$strResult .= '<td>' . $product->shipping . '</td>';
	$strResult .= '<td>' . $product->MatchUPC . '</td>';
	$strResult .= '</tr>';
}
if(isset($database)) { $database->close_connection(); } 
echo $strResult;

?>
