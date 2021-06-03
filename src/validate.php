<?php

class  Validator {
	static $errors = true;

	static function check($arr, $on = false) {
		if ($on === false) {
			$on = $_REQUEST;
		}
		foreach ($arr as $value) {	
			if (empty($on[$value])) {
				self::throwError('Data is missing', 900);
			}
		}
	}

	static function int($val) {
		$val = filter_var($val, FILTER_VALIDATE_INT);
		if ($val === false) {
			self::throwError('Invalid Integer', 901);
		}
		return $val;
	}

	static function str($val) {
		if (!is_string($val)) {
			self::throwError('Invalid String', 902);
		}
		$val = trim(htmlspecialchars($val));
		return $val;
    }
    
    static function tooshort($fieldname, $val, $minimum) {
		$length = strlen($val);
		if ($length < $minimum) {
			self::throwError('Value too long',903);	
		}
	}

	static function toolong($fieldname, $val, $maximum) {
		$length = strlen($val);
		if ($length > $maximum) {
			self::throwError('Value too short',904);	
		}
	}

	static function badcontent($fieldname, $val) {
		if (!preg_match("/^[a-zA-Z0-9 '-]*$/",$val)) {
			self::throwError('Bad content',905);
		}
	}


	static function url($val) {
		$val = filter_var($val, FILTER_VALIDATE_URL);
		if ($val === false) {
			self::throwError('Invalid URL', 906);
		}
		return $val;
    }

	static function throwError($error = 'Error In Processing', $errorCode = 0) {
		if (self::$errors === true) {
			throw new Exception($error, $errorCode);
		}
	}
}