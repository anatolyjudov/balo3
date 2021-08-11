<?php

/* uri */
$root_path = "";												# путь к корню сайта относительно корня сервера, без слэша на конце

/* файловые пути */
$file_path = $entrypoint_path . $root_path;						# полный путь к корню сайта
$templates_path = $entrypoint_path . "/templates";				# каталог с шаблонами, без слэша на конце
$components_path = $entrypoint_path . "/components";			# каталог с компонентами, без слэша на конце
$controllers_path = $entrypoint_path . "/controllers";			# каталог с контроллерами, без слэша на конце
$files_path = $entrypoint_path . "/files";						# путь к прикрепленным файлам
$tmpfiles_path = $entrypoint_path . "/tmp";
$smarty_tmp_path = $tmpfiles_path . "/smarty_tmp";				# путь к временным директориям смарти

/* путь, необходимый для smarty */
define("SMARTY_DIR", $entrypoint_path . "/balo3/smarty/");	# константа, указывающая на путь к библиотеке smarty

/* использовать специальный компонент для работы с вершинами, вместо обычных функций ядра */
$balo3_use_manuka_component = "manuka";

/* глобальные переменные */
$balo3_request_info = array();
$balo3_node_info = array(
	"controllers" => array(),
	"layout" => array()
);
$balo3_template_data = array();
$balo3_controllers_output = array();

// массив с сообщениями об ошибках
$balo3_errors = array();

// массив текстовых констант
$balo3_texts = array();

//
$balo3_db = false;
?>