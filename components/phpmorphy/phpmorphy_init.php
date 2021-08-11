<?php

require_once("$entrypoint_path/components/phpmorphy/phpmorphy_config.php");
require_once("$entrypoint_path/components/phpmorphy/phpmorphy_functions.php");

// Подключите файл common.php
require_once( $phpmorphy_path . '/src/common.php');

// Укажите опции
// Список поддерживаемых опций см. ниже
$phpmorphy_opts = array(
	'storage' => PHPMORPHY_STORAGE_FILE,
);

// создаем экземпляр класса phpMorphy
// обратите внимание: все функции phpMorphy являются throwable т.е. 
// могут возбуждать исключения типа phpMorphy_Exception (конструктор тоже)
try {
	$phpmorphy = new phpMorphy($phpmorphy_dicts_path, $phpmorphy_lang, $phpmorphy_opts);
} catch(phpMorphy_Exception $e) {
	die('Error occured while creating phpMorphy instance: ' . $e->getMessage());
}



//var_dump($phpmorphy);
/*
$r = phpmorphy_get_search_words($_GET['q']);

echo "<pre>";
var_dump($r);
echo "</pre>";

exit;
*/

?>