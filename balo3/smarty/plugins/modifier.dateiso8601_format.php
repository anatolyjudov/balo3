<?php

function smarty_modifier_dateiso8601_format($string, $format = "d %_% Y, H:i") {
	global $sdzyxel_timezone_offset;

	$date_info = date_parse($string);
	if ($date_info === false) {
		return $string;
	}

	if (isset($sdzyxel_timezone_offset)) {
		$date_info['hour'] += $sdzyxel_timezone_offset;
	}

	$month_names = array(
		1 => "Января",
		2 => "Февраля",
		3 => "Марта",
		4 => "Апреля",
		5 => "Мая",
		6 => "Июня",
		7 => "Июля",
		8 => "Августа",
		9 => "Сентября",
		10 => "Октября",
		11 => "Ноября",
		12 => "Декабря"
	);

	$str =  date($format, mktime( $date_info['hour'], $date_info['minute'], $date_info['second'], $date_info['month'], $date_info['day'], $date_info['year'] ));
	// 16 СЕНТЯБРЯ 2013, 15:30

	$str = str_replace("%_%", $month_names[(int)$date_info['month']], $str);

	return $str;

}

?>
