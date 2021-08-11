<?php

// приводит размер файла в байтах к удобочитаемому виду типа 16,3 Мбайт

function smarty_modifier_filesize_format($filesize) {

	$filesize = (int)$filesize;

	if ($filesize < 128) {

		// показываем в байтах
		return $filesize . " байт";

	} elseif ($filesize < 1024*512) {

		// показываем в килобайтах
		return round($filesize/1024, 1) . " Кб";

	} else {

		// показываем в мегабайтах
		return round($filesize/1024/1024, 1) . " Мб";

	}

	return $str;

}

?>
