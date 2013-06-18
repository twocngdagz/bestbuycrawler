<?php
require_once("/includes/initialize.php");
$file_handle = fopen("C:\wamp\www\crawler\bestbuy\\2013-04-09.txt", "r");
$count = 1;
while (!feof($file_handle)) {
	$content = fgets($file_handle);
	if (!IsNullOrEmptyString($content)) {
		switch (TRUE) {
			case $count<=500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU1.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>500 and $count <= 1000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU2.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>1000 and $count <= 1500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU3.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>1500 and $count <= 2000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU4.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>2000 and $count <= 2500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU5.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>2500 and $count <= 3000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU6.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>3000 and $count <= 3500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU7.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>3500 and $count <= 4000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU8.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>4000 and $count <= 4500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU9.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>4500 and $count <= 5000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU10.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			
			case $count>5000 and $count <= 5500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU11.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>5500 and $count <= 6000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU12.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>6000 and $count <= 6500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU13.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>6500 and $count <= 7000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU14.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>7000 and $count <= 7500:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU15.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
			case $count>7500 and $count <= 8000:
				if($handle=fopen('C:\wamp\www\crawler\bestbuy\BBY SKU16.txt', 'a')) {
					fwrite($handle, $content);
					fclose($handle);
				}
			break;
		}
	}
	$count++;
}
?>