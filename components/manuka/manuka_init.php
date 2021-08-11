<?php

/* программные настройки и глобальные переменные компонента */
require("$components_path/manuka/manuka_config.php");

/* функции базы данных компонента */
require("$components_path/manuka/manuka_db.php");

/* утилиты и другие функции компонента */
require("$components_path/manuka/manuka_functions.php");

/* основная функция компонента MANUKA - определить вершину и положить информацию о ней в массив ядра balo3_node_info */
$balo3_node_info = manuka_get_node_info();
if ($balo3_node_info === "notfound") {
	// если вершина не найдена, показываем ошибку 404
	balo3_error404();
}


?>