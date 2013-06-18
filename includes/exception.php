<?php
class CustomException extends Exception {
	public function __construct($message, $code = 0) {
		parent::__construct($message, $code);
		
		$msg = "---------------------------------------------\n";
		$msg .= __CLASS__. ": [{$this->code}]: {$this->message}\n";
		$msg .= $this->getTraceAsString() . "\n";
		error_log($msg);
	}
	
	public function __toString() {
		return $this->printMessage();
	}
	
	public function printMessage() {
		$usermsg = '';
		$code = $this->getCode();
		
		switch ($code) {
			case 3001:
				$usermsg = 'Database connection lost';
				break;
			case 3002:
				$usermsg = 'Parse Error';
				break;
			default:
				$usermsg = '';
		}
		return $usermsg;
	}
	
	public static function exception_handler($exception) {
		throw new CustomException($exception);
	}
}
?>