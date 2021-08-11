<?php

$common_domain = "http://www.domain.ru";						# доменное имя в адресной строке, включая http://, без слэша на конце
$common_simple_domain = "domain.ru";
if ($_SERVER['HTTP_HOST'] != '') {
	$common_simple_domain = $_SERVER['HTTP_HOST'];
	$common_domain = "http://" . $common_simple_domain;
}

$settings_dbhost = "localhost";
$settings_dbuser = "";
$settings_dbpass = '';
$settings_dbname = "";

?>