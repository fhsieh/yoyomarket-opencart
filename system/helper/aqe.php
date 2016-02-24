<?php

if (!defined('TA_LOCAL')) {
	define('TA_LOCAL', 100);
}

if (!defined('TA_PREFETCH')) {
	define('TA_PREFETCH', 1000);
}

if (!defined('JSON_UNESCAPED_SLASHES')) {
	define('JSON_UNESCAPED_SLASHES', 0xFFFF);
}

/**
  * Use our custom json_encode function in case of older PHP versions
  *
  **/
if (!function_exists("json_enc")) {
	function json_enc($value, $options = 0, $depth = 512) {
		if (version_compare(phpversion(), '5.5.0') >= 0) {
			return json_encode($value, $options, $depth);
		} elseif (version_compare(phpversion(), '5.4.0') >= 0) {
			return json_encode($value, $options);
		} else {
			return json_encode($value);
		}
	}
}

/**
  * Validate date
  *
  **/
if (!function_exists("validate_date")) {
	function validate_date($date, $format = 'Y-m-d H:i:s') {
		$d = DateTime::createFromFormat($format, $date);
		return $d && $d->format($format) == $date;
	}
}

/**
  * Sort columns by index key
  *
  **/
if (!function_exists('column_sort')) {
	function column_sort($a, $b) {
		if ($a['index'] == $b['index']) {
			return 0;
		}
		return ($a['index'] < $b['index']) ? -1 : 1;
	}
}

/**
  * Filter columns by display value
  *
  **/
if (!function_exists('column_display')) {
	function column_display($a) {
		return (isset($a['display'])) ? (int)$a['display'] : false;
	}
}
