<?php

if ($balo3_use_manuka_component != "") {

	/* инициализируем компонент MANUKA */
	require($entrypoint_path."/components/".$balo3_use_manuka_component."/".$balo3_use_manuka_component."_init.php");

} else {

	/* разбор URI и определение текущей вершины дерева путей */
	require("$entrypoint_path/settings/simple_manuka_settings.php");
	$balo3_node_info = balo3_get_node_info();
	if ($balo3_node_info === "notfound") {
		// если вершина не найдена, показываем ошибку 404
		balo3_error404();
	}

}

?>