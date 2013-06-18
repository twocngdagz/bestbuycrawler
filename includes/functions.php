<?php

function strip_zeros_from_date($marked_string="") {
	$no_zeros = str_replace('*0', '', $marked_string);
	$cleaned_string = str_replace('*', '', $no_zeros);
	return cleaned_string;
}

function redirect_to($location = NULL) {
	if ($location != NULL) {
		header("Location: {$location}");
		exit;
	}
}

function output_message($message="") {
	if(!empty($message)) {
		return "<p class=\"messge\">{$message}</p>";
	} else {
		return "";
	}
}

function __autoload($class_name) {
	$class_name = strtolower($class_name);
	$path = LIB_PATH.DS."{$class_name}.php";
	if(file_exists($path)) {
		require_once($path);
	} else {
		die("The file {$class_name}.php could not be found");
	}
}

function include_layout_template($template="") {
	include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}

function log_action($action, $message="") {
	$logfile = SITE_ROOT.DS.'logs'.DS.'log.txt';
	$new = file_exists($logfile) ? false : true;
	if($handle=fopen($logfile, 'a')) {
		$timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
		$content = "{$timestamp} | {$action}:{$message}\n";
		fwrite($handle, $content);
		fclose($handle);
		if($new) {chmod($logfile, 0755);}
	} else {
		echo "Could not open log file for writing.";
	}
}

function datetime_to_text($datetime="") {
	$unixdatetime = strtotime($datetime);
	return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}



function time_elapsed($secs){
	if ($secs > 0) {
	    $bit = array(
	        'y' => $secs / 31556926 % 12,
	        'w' => $secs / 604800 % 52,
	        'd' => $secs / 86400 % 7,
	        'h' => $secs / 3600 % 24,
	        'm' => $secs / 60 % 60,
	        's' => $secs % 60
	        );
	        
	    foreach($bit as $k => $v)
	        if($v > 0)$ret[] = $v . $k;
	        
	    return join(' ', $ret);
	}
	return 0;
}

//check if string is null or empty
function IsNullOrEmptyString($str){
    return (!isset($str) || trim($str)==='' || strlen($str) == 0 || empty($str));
}


//return line count in a file
function lineCount($file) {
	$linecount = 0;
	$handle = fopen($file, "r");
	while(!feof($handle)) {
		if (fgets($handle) !== false) {
			$linecount++;
		}
	}
	fclose($handle);
	return $linecount;
}



?>