<?php

/* подключим компонент currencies если он ещё не подключен */
require_once("$components_path/currencies/currencies_init.php");

/* подключим farch если он ещё не подключен */
require_once("$components_path/farch/farch_init.php");

/* программные настройки и глобальные переменные компонента */
require("$components_path/catalog/catalog_config.php");

$catalog_section_context = false;
$catalog_section_context_extended = array();
$sections_info = array();
$section_counts = array();

/* функции базы данных компонента */
require("$components_path/catalog/catalog_db.php");

/* утилиты и другие функции компонента */
require("$components_path/catalog/catalog_functions.php");

require("$components_path/catalog/catalog_texts.php");

//balo3_load_texts("catalog");
multilang_load_smarty_config("catalog");
//show_ar($currencies);

$sections_info = catalog_db_get_sections();
if ($sections_info === 'error') {
	balo3_error("db error", true);
	exit;
}


$section_counts = catalog_db_get_goods_counts(array_keys($sections_info['list']));
if ($section_counts === 'error') {
	balo3_error("db error", true);
	exit;
}
//show_ar($sections_info);

// подключать или не подключать компонент authors
$catalog_authors_component = true;

if ($catalog_authors_component) {

	/* инициализация компонента authors */
	require_once("$components_path/authors/authors_init.php");

	if (!isset($authors_component)) {

	balo3_error("catalog requires authors component", true);
	exit;

	}
}

$smarty->assign("catalog_authors_component", $catalog_authors_component);

$smarty->assign_by_ref("catalog_component", $catalog_component);

$smarty->assign_by_ref("sections_info", $sections_info);
$smarty->assign_by_ref("section_counts", $section_counts);

$smarty->assign_by_ref("catalog_sections_uri", $catalog_sections_uri);
$smarty->assign_by_ref("catalog_collection_uri", $catalog_collection_uri);

$smarty->assign_by_ref("catalog_section_context", $catalog_section_context);
$smarty->assign_by_ref("catalog_section_context_extended", $catalog_section_context_extended);

?>