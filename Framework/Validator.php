<?php
namespace Framework;
class Validator {
	public static function string($value, $min = 1, $max = INF) {
		if(is_string($value)) {
			$value = trim($value);
			$stringLen = strlen($value);
			return $stringLen>=$min && $stringLen<=$max;
		}
		return false;
	}
	public static function email($value) {
		$value = trim($value);
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}
	public static function match($value1, $value2) {
		return trim($value1)===trim($value2);
	}
	
}

?>