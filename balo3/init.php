<?php

/*
* Ёто главный управл€ющий файл, реализующий базовую последовательность действий
*/

/* подключаем файлы €дра */
require("$entrypoint_path/balo3/config.php");
require("$entrypoint_path/balo3/db.php");
require("$entrypoint_path/balo3/functions.php");
require("$entrypoint_path/balo3/smarty/Smarty.class.php");

/* настройки сайта */
require("$entrypoint_path/settings/settings.php");

/* загрузка текстовых констант €дра */
balo3_load_texts("$entrypoint_path/balo3/texts.php");

/* соединение с базой данных */
balo3_db_sql_connect($settings_dbhost, $settings_dbuser, $settings_dbpass, $settings_dbname);

/* инициализируем шаблонизатор и его базовые настройки */
$smarty = new Smarty;
balo3_smarty_init();
balo3_load_smarty_config("$templates_path/balo3/texts.cfg");

/* выдадим базовые HTTP-заголовки */
balo3_output_headers();

/* определ€ем текущую вершину и получаем информацию о контроллерах и шаблонах, которые необходимо обработать */
require("$entrypoint_path/balo3/manuka.php");

/* инициализируем компонент COMMON, подключаем общие функции данного сайта */
require("$entrypoint_path/common/common_init.php");

/* поочередный запуск контроллеров, соответствующих этой вершине */
if (count($balo3_node_info['controllers']) > 0) {
	foreach($balo3_node_info['controllers'] as $controller_placeholder=>$controller_data) {
		$controller_args = isset($controller_data['controller_args']) ? $controller_data['controller_args'] : array();
		require("$controllers_path/".$controller_data['controller_family']."/".$controller_data['controller'].".php");
	}
}

/* вывод данных через layout-шаблон, соответствующий этой вершине */
balo3_process_layout();

?>