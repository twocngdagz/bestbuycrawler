<?php
include_once('simple_html_dom.php');
include_once('bestbuy.php');
require_once("/includes/initialize.php");
$crawler = new Crawler();
$crawler->name = 'Crawler 11';
$crawler->start = time();
$crawler->save();
$b = new BestBuy(TEXT_INPUT11, false);
$b->start();
$crawler->end = time();
$crawler->save();
?>