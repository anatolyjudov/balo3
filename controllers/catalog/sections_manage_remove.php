<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	// ���������� ����� section ��������
	if ( isset($_POST['section_id']) && (preg_match("/^\d+$/", $_POST['section_id'])) ) {
		$section_id = $_POST['section_id'];
	} elseif ( isset($_GET['section_id']) && (preg_match("/^\d+$/", $_GET['section_id'])) ) {
		$section_id = $_GET['section_id'];
	} else {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// ���� section� ��� � ������ section�� - ������
	if (!isset($sections_info['list'][$section_id])) {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// ��������� ��� ���� �� section�� ���� � ������, ���� ������ ������
	// ���� �����-������ ���� ��� ������ ����� �� ������ - �� ����� ��������� ����� ��������� � ��
	$section_info = $sections_info['list'][$section_id];

	$smarty->assign_by_ref("section_info", $section_info);

	// ���� ��� ������ � POST - ����� ������ ����� �������������
	if (!isset($_POST['section_id'])) {
		balo3_controller_output($smarty->fetch("$templates_path/catalog/sections_manage_delete.tpl"));
		break;
	}

	// ��������
	$status = catalog_db_remove_section($section_id);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/catalog/sections_manage_delete.tpl"));
		break;
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_CATALOG_ERRORS['TREE_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/catalog/sections_manage_delete.tpl"));
		break;
	}


	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/catalog/sections_manage_remove.tpl"));

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

	// ���������� ����� section ��������
	if ( isset($_POST['section_id']) && (preg_match("/^\d+$/", $_POST['section_id'])) ) {
		$section_id = $_POST['section_id'];
	} elseif ( isset($_GET['section_id']) && (preg_match("/^\d+$/", $_GET['section_id'])) ) {
		$section_id = $_GET['section_id'];
	} else {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// ���� section� ��� � ������ section�� - ������
	if (!isset($sections_info['list'][$section_id])) {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// ��������� ��� ���� �� section�� ���� � ������, ���� ������ ������
	// ���� �����-������ ���� ��� ������ ����� �� ������ - �� ����� ��������� ����� ��������� � ��
	$section_info = $sections_info['list'][$section_id];

	$smarty->assign_by_ref("section_info", $section_info);

	// ���� ��� ������ � POST - ����� ������ ����� �������������
	if (!isset($_POST['section_id'])) {
		out_main($smarty->fetch("catalog/sections_manage_delete.tpl"));
	}

	// ��������
	$status = catalog_db_remove_section($section_id);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("catalog/sections_manage_delete.tpl"));
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_CATALOG_ERRORS['TREE_ERROR']);
		out_main($smarty->fetch("catalog/sections_manage_delete.tpl"));
	}

	// ����� �������
	out_main($smarty->fetch("catalog/sections_manage_remove.tpl"));
*/
?>