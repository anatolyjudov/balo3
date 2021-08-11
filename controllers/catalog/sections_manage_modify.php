<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	// определяем какой section показать
	if ( isset($_POST['section_id']) && (preg_match("/^\d+$/", $_POST['section_id'])) ) {
		$section_id = $_POST['section_id'];
	} elseif ( isset($_GET['section_id']) && (preg_match("/^\d+$/", $_GET['section_id'])) ) {
		$section_id = $_GET['section_id'];
	} else {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// если sectionа нет в списке sectionов - ошибка
	if (!isset($sections_info['list'][$section_id])) {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// поскольку вся инфа об sectionах есть в списке, берём просто оттуда
	// если когда-нибудь инфа для списка будет не полной - то здесь добавится метод обращения к бд
	$old_section_info = $sections_info['list'][$section_id];
	$old_section_description_info = catalog_db_get_section_description_info($section_id);
	if ($old_section_description_info === "error") {
		balo3_controller_output($_ERRORS['DB_ERROR']);
		break;
	}
	if ($old_section_description_info === "notfound") {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}
	$old_section_info = array_merge($old_section_info, $old_section_description_info);

	// если нет ничего в POST - выдаём просто форму
	if (!isset($_POST['section_id'])) {
		$smarty->assign_by_ref("section_info", $old_section_info);
		balo3_controller_output($smarty->fetch("catalog/sections_manage_edit.tpl"));
		break;
	}

	$smarty->assign_by_ref("old_section_info", $old_section_info);

	// иначе - принимаем параметры
	if (isset($_POST['section_name']) && ($_POST['section_name'] != '')) {
		$section_info['SECTION_NAME'] = $_POST['section_name'];
	} else {
		$section_info['SECTION_NAME'] = '';
	}
	if (isset($_POST['dirname']) && ($_POST['dirname'] != '')) {
		$section_info['DIRNAME'] = $_POST['dirname'];
	} else {
		$section_info['DIRNAME'] = '';
	}
	if (isset($_POST['parent_id']) && preg_match("/^\d+$/", $_POST['parent_id'])) {
		$section_info['PARENT_ID'] = $_POST['parent_id'];
	} else {
		$section_info['PARENT_ID'] = '';
	}
	if (isset($_POST['description']) && ($_POST['description'] != '')) {
		$section_info['DESCRIPTION'] = $_POST['description'];
	} else {
		$section_info['DESCRIPTION'] = '';
	}
	if (isset($_POST['section_authors']) && ($_POST['section_authors'] != '')) {
		$section_info['SECTION_AUTHORS'] = $_POST['section_authors'];
	} else {
		$section_info['SECTION_AUTHORS'] = '';
	}
	if (isset($_POST['published']) && ($_POST['published'] == 'on') ) {
		$section_info['PUBLISHED'] = 1;
	} else {
		$section_info['PUBLISHED'] = 0;
	}

	// сохранение входных данных для шаблона
	$smarty->assign_by_ref("section_info", $section_info);

	// проверка входных данных
	if ($section_info['PARENT_ID'] == '') {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		balo3_controller_output($smarty->fetch("catalog/sections_manage_edit.tpl"));
		break;
	}
	if ($section_info['SECTION_NAME'] == '') {
		$smarty->assign("errmsg", $_CATALOG_ERRORS['EMPTY_SECTION_NAME']);
		balo3_controller_output($smarty->fetch("catalog/sections_manage_edit.tpl"));
		break;
	}

	// файл картинки
	//show_ar($_FILES);exit;
	/*
	if (isset($_FILES['section_picture']) && ($_FILES['section_picture']['error']!=4)) {

		$file_info = $_FILES['section_picture'];

		// работаем с файлами, генерим превью
		list($status, $error, $tech_info) = catalog_fotos_save_foto($section_id, 'title', $file_info);
		if ($status === "error") {
			$smarty->assign("errmsg", $_FARCH_FOTO_ERRORS[$error]);
			balo3_controller_output($smarty->fetch("catalog/sections_manage_edit.tpl"));
			break;
		}

		$section_info['PICTURE_TECH_INFO'] = $tech_info;

	}
	*/

	// изменение
	$status = catalog_db_modify_section($section_id, $section_info);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("catalog/sections_manage_edit.tpl"));
		break;
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_CATALOG_ERRORS['TREE_ERROR']);
		balo3_controller_output($smarty->fetch("catalog/sections_manage_edit.tpl"));
		break;
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/catalog/sections_manage_modify.tpl"));

} while (false);

?>



<?php
/*
include_once($file_path."/includes/catalog/catalog_db.php");
include_once($file_path."/includes/catalog/catalog_config.php");
include_once($file_path."/includes/catalog/catalog_functions.php");
include_once($file_path."/includes/catalog/catalog_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS, $_CATALOG_ERRORS;

	$sections_info = catalog_db_get_sections();
	if ($sections_info === 'error') {
		out_main($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("sections_info", $sections_info);
	$smarty->assign_by_ref("catalog_foto_params", $catalog_foto_params);

	// определяем какой section показать
	if ( isset($_POST['section_id']) && (preg_match("/^\d+$/", $_POST['section_id'])) ) {
		$section_id = $_POST['section_id'];
	} elseif ( isset($_GET['section_id']) && (preg_match("/^\d+$/", $_GET['section_id'])) ) {
		$section_id = $_GET['section_id'];
	} else {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// если sectionа нет в списке sectionов - ошибка
	if (!isset($sections_info['list'][$section_id])) {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// поскольку вся инфа об sectionах есть в списке, берём просто оттуда
	// если когда-нибудь инфа для списка будет не полной - то здесь добавится метод обращения к бд
	$old_section_info = $sections_info['list'][$section_id];
	$old_section_description_info = catalog_db_get_section_description_info($section_id);
	if ($old_section_description_info === "error") {
		out_main($_ERRORS['DB_ERROR']);
	}
	if ($old_section_description_info === "notfound") {
		out_main($_ERRORS['BAD_PARAMETER']);
	}
	$old_section_info = array_merge($old_section_info, $old_section_description_info);

	// если нет ничего в POST - выдаём просто форму
	if (!isset($_POST['section_id'])) {
		$smarty->assign_by_ref("section_info", $old_section_info);
		out_main($smarty->fetch("catalog/sections_manage_edit.tpl"));
	}

	$smarty->assign_by_ref("old_section_info", $old_section_info);

	// иначе - принимаем параметры
	if (isset($_POST['section_name']) && ($_POST['section_name'] != '')) {
		$section_info['SECTION_NAME'] = $_POST['section_name'];
	} else {
		$section_info['SECTION_NAME'] = '';
	}
	if (isset($_POST['parent_id']) && preg_match("/^\d+$/", $_POST['parent_id'])) {
		$section_info['PARENT_ID'] = $_POST['parent_id'];
	} else {
		$section_info['PARENT_ID'] = '';
	}
	if (isset($_POST['description']) && ($_POST['description'] != '')) {
		$section_info['DESCRIPTION'] = $_POST['description'];
	} else {
		$section_info['DESCRIPTION'] = '';
	}
	if (isset($_POST['section_authors']) && ($_POST['section_authors'] != '')) {
		$section_info['SECTION_AUTHORS'] = $_POST['section_authors'];
	} else {
		$section_info['SECTION_AUTHORS'] = '';
	}
	if (isset($_POST['published']) && ($_POST['published'] == 'on') ) {
		$section_info['PUBLISHED'] = 1;
	} else {
		$section_info['PUBLISHED'] = 0;
	}

	// сохранение входных данных для шаблона
	$smarty->assign_by_ref("section_info", $section_info);

	// проверка входных данных
	if ($section_info['PARENT_ID'] == '') {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		out_main($smarty->fetch("catalog/sections_manage_edit.tpl"));
	}
	if ($section_info['SECTION_NAME'] == '') {
		$smarty->assign("errmsg", $_CATALOG_ERRORS['EMPTY_SECTION_NAME']);
		out_main($smarty->fetch("catalog/sections_manage_edit.tpl"));
	}

	// файл картинки
	//show_ar($_FILES);exit;
	if (isset($_FILES['section_picture']) && ($_FILES['section_picture']['error']!=4)) {

		$file_info = $_FILES['section_picture'];

		// работаем с файлами, генерим превью
		list($status, $error, $tech_info) = catalog_fotos_save_foto($section_id, 'title', $file_info);
		if ($status === "error") {
			$smarty->assign("errmsg", $_FARCH_FOTO_ERRORS[$error]);
			out_main($smarty->fetch("catalog/sections_manage_edit.tpl"));
		}

		$section_info['PICTURE_TECH_INFO'] = $tech_info;

	}

	// изменение
	$status = catalog_db_modify_section($section_id, $section_info);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("catalog/sections_manage_edit.tpl"));
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_CATALOG_ERRORS['TREE_ERROR']);
		out_main($smarty->fetch("catalog/sections_manage_edit.tpl"));
	}

	// вывод шаблона
	out_main($smarty->fetch("catalog/sections_manage_modify.tpl"));
*/
?>