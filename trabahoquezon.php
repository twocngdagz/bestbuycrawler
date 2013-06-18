<?php
require_once("/includes/initialize.php");
include_once('simple_html_dom.php');

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

$imagepath = "/fass/images/research/researchclusters/cities/";

function trabaho() {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.fas.nus.edu.sg/cities/members/index.html");
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11");
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch,CURLOPT_BINARYTRANSFER, true);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
   		//return false;
	   return 'Curl error: ' . curl_error($ch) . "\n";
	}
	
	curl_close($ch);
	return $data;
}

function getImage($filename) {
	$fp = fopen($filename, 'w+');
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://www.fas.nus.edu.sg/migration/images/members/".$filename);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.97 Safari/537.11");
	curl_setopt($ch, CURLOPT_ENCODING, 'identity');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,0);
	curl_setopt($ch, CURLOPT_FILE, $fp);
	$data = curl_exec($ch);
   	if(curl_errno($ch)){
   		//return false;
	   return 'Curl error: ' . curl_error($ch) . "\n";
	}
	curl_close($ch);
	return $data;
}

function getName($data) {
	if ($data) {
		$td_name = $data->find('td', 1);
		if ($td_name) {
			$p = $td_name->find('p', 0);
			if ($p) {
				return $p->innertext;
			} else {
				$div = $td_name->find('div', 0);
				if ($div) {
					return $div->innertext;
				}
			}
			//$strong_name = $td_name->find('strong', 0);
			//if ($strong_name) {
			//	$name = $strong_name->innertext;
			//	return implode(", ",explode(",", $name));
			//}
		}
	}
}

function getPublication($data) {
	if ($data) {
		$link = $data->find('a', 0);
		if ($link) {
			return $link->href;
		}
	}
}

function getEmail($data) {
	if ($data) {
		$link = $data->find('a', 1);
		if ($link) {
			return $link->href;
		}
	}
}

function getProfile($data) {
	if ($data) {
		$td = $data->find('td', 2);
		if ($td) {
			$link = $data->find('a', 2);
			if ($link) {
				return $link->href;
			}
		}
	}
	return "";
}

function getKeyword($data) {
	if ($data) {
		$td = $data->find('td', 3);
		if ($td) {
			$div = $td->find('div', 0);
			if ($div) {
				return $div->innertext; 
			} else {
				$p = $td->find('p', 0);
				if ($p) {
					return $p->innertext;
				} else {
					return $td->innertext;
				}
			}
		}
	}
}

function getDepartment($data) {
	if ($data) {
		$td = $data->find('td', 4);
		if ($td) {
			$a = $td->find('a', 0);
			if ($a) {
				return $a->innertext;
			}
		}
	}
}

function getDepartmentLink($data) {
	if ($data) {
		$td = $data->find('td', 4);
		if ($td) {
			$a = $td->find('a', 0);
			if ($a) {
				return $a->href;
			}
		}
	}
}

function getFileName($data) {
	if ($data) {
		$td = $data->find('td', 0);
		if ($td) {
			$img = $td->find('img', 0);
			if ($img) {
				$filename = explode("/", $img->src);
				return $filename[count($filename)-1];
			}
		}
	}
}

function getImagePath($data) {
	if ($data) {
		$td = $data->find('td', 0);
		if ($td) {
			$img = $td->find('img', 0);
			if ($img) {
				return $img->src;
			}
		}
	}
}

$html = str_get_html(trabaho());
//$table = $html->find('table[id=memberstable]', 0);
foreach ($html->find('table[id=memberstable]') as $table) {
	foreach ($table->find('tr') as $tr) {
		$filename = getFileName($tr);
		//if ($filename && $filename != "") {
		//	getImage($filename);
		//}
		echo "<tr>" . "\n";
		echo "\t" . "<td width=\"100\" valign=\"middle\" align=\"center\" class=\"profileimg\">" . "\n";
		echo "\t\t" . "<p align=\"center\">" . "\n";
		echo "\t\t\t" . "<img width=\"90\" height=\"90\" src=\"" . $imagepath . $filename . "\"/>" . "\n";
		echo "\t\t" . "</p>" . "\n";
		echo "\t" . "</td>" . "\n";
		echo "\t" . "<td width=\"145\" valign=\"middle\" align=\"center\" class=\"membernames\">" . "\n";
		echo "\t\t" . "<p align=\"center\">" . getName($tr) . "</p>" . "\n";
		echo "\t". "</td>" . "\n";
		echo "\t" . "<td width=\"59\" valign=\"middle\" align=\"center\">" . "\n";
		echo "\t\t" ."<p align=\"center\">" . "\n";
		echo "\t\t" . "<a href=\"" . getPublication($tr) . "\" >" . "\n";
		echo "\t\t\t" . "<img border=\"0\" alt=\"PUBLICATIONS\" src=\"../../../images/research/researchclusters/publications.gif\" />" . "\n";
		echo "\t\t" . "</a>" . "\n";
		echo "\t\t" . "<br />" . "\n";
		echo "\t\t" . "<a href=\"" . getEmail($tr) . "\" >" . "\n";
		echo "\t\t\t" . "<img width=\"16\" height=\"16\" border=\"0\" alt=\EMAIL\" src=\"../../../images/research/researchclusters/mail.gif\" />" . "\n";
		echo "\t\t" . "</a>" . "\n";
		echo "\t\t" . "<br />" . "\n";
		if (getProfile($tr) == "") {
			echo "\t\t\t\t" . "<img width=\"16\" height=\"16\" border=\"0\" alt=\"PROFILE\" src=\"../../../images/research/researchclusters/user.gif\" />" . "\n";
		}  else {
			echo "\t\t" . "<a href=\"" . getProfile($tr) . "\">" . "\n";
			echo "\t\t\t" . "<img width=\"16\" height=\"16\" border=\"0\" alt=\"PROFILE\" src=\"../../../images/research/researchclusters/user.gif\" />" . "\n";
			echo "\t\t" . "</a>" . "\n"; 
		}
		echo "\t\t" . "<br />" . "\n";
		echo "\t". "</p>" . "\n";
		echo "\t". "</td>" . "\n";
		echo "\t" . "<td width=\"59\" valign=\"middle\" align=\"center\">" . "\n";
		echo "\t\t" . "<div align=\"center\">"  . "\n";
		echo "\t\t\t" . getKeyword($tr) . "\n";
		echo "\t\t" . "</div>"  . "\n";
		echo "\t". "</td>" . "\n";
		echo "\t" . "<td valign=\"middle\" align=\"center\" class=\"out\">". "\n";
		echo "\t\t". "<p align=\"center\">". "\n";
		echo "\t\t\t" . "<a href=\"" . getDepartmentLink($tr) . "\">".getDepartment($tr)."</a>". "\n";
		echo "\t\t" . "</p>" . "\n";
		echo "\t". "</td>" . "\n";
		echo "</tr>" . "\n";
		//echo getPublication($tr) . "\n";
		//echo getEmail($tr) . "\n";
		//echo getProfile($tr) . "\n";
		//echo getKeyword($tr) . "\n";
		//echo getDepartment($tr) . "\t" .  getDepartmentLink($tr) .  "\n";
	}
}
?>