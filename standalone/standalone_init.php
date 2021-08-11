<?php

$entrypoint_path = substr(__FILE__, 0, -1*strlen("/standalone/standalone_init.php"));

// мониторинг
if (extension_loaded('newrelic')) {
	newrelic_set_appname("sd2.zyxel.ru");
}

/* подключаем файлы ядра */
require("$entrypoint_path/balo3/config.php");
require("$entrypoint_path/balo3/db.php");
require("$entrypoint_path/balo3/functions.php");
require("$entrypoint_path/balo3/smarty/Smarty.class.php");

/* настройки сайта */
require("$entrypoint_path/common/common_config.php");

/* соединение с базой данных */
balo3_db_sql_connect($common_dbhost, $common_dbuser, $common_dbpass, $common_dbname);

/* инициализируем шаблонизатор и его базовые настройки */
$smarty = new Smarty;
balo3_smarty_init();

/* специфические функции сайта */
require("$entrypoint_path/common/common_db.php");
require("$entrypoint_path/common/common_functions.php");

setlocale(LC_ALL, 'ru_RU.UTF-8');

/* определяем текущий бренд */
$common_current_brand = "zyxel";

/* подменяем значения доменов и глобальных переменных, зависящих от бренда */
$common_domain = $common_brands_info[$common_current_brand]["common_domain"];
$common_simple_domain = $common_brands_info[$common_current_brand]["common_simple_domain"];
$common_brand_id = $common_brands_info[$common_current_brand]["brand_id"];

?>