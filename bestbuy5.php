<?php
include_once('simple_html_dom.php');
include_once('bestbuy_v2.php');
require_once("/includes/initialize.php");

$b = new BestBuy_v2(TEXT_INPUT1, false);
$b->start();


?> 