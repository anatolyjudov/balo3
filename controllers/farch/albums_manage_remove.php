<?php

require_once("$entrypoint_path/components/farch/farch_init.php");

do {

	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		balo3_controller_output($_ERRORS['DB_ERROR']);
		break;
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// ���������� ����� ������ ��������
	if ( isset($_POST['album_id']) && (preg_match("/^\d+$/", $_POST['album_id'])) ) {
		$album_id = $_POST['album_id'];
	} elseif ( isset($_GET['album_id']) && (preg_match("/^\d+$/", $_GET['album_id'])) ) {
		$album_id = $_GET['album_id'];
	} else {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// ���� ������� ��� � ������ �������� - ������
	if (!isset($albums_info['list'][$album_id])) {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// ��������� ��� ���� �� �������� ���� � ������, ���� ������ ������
	// ���� �����-������ ���� ��� ������ ����� �� ������ - �� ����� ��������� ����� ��������� � ��
	$album_info = $albums_info['list'][$album_id];

	$smarty->assign_by_ref("album_info", $album_info);

	// ���� ��� ������ � POST - ����� ������ ����� �������������
	if (!isset($_POST['album_id'])) {
		balo3_controller_output($smarty->fetch("$templates_path/farch/albums_manage_delete.tpl"));
		break;
	}

	// ��������
	$status = far_db_remove_album($album_id);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/farch/albums_manage_delete.tpl"));
		break;
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_FARCH_ERRORS['TREE_ERROR']);
		balo3_controller_output($smarty->fetch("$templates_path/farch/albums_manage_delete.tpl"));
		break;
	}


	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/farch/albums_manage_remove.tpl"));

} while (false);

?>



<?php
/*
include_once($file_path."/includes/farch/farch_db.php");
include_once($file_path."/includes/farch/farch_config.php");
include_once($file_path."/includes/farch/farch_functions.php");
include_once($file_path."/includes/farch/farch_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS, $_FARCH_ERRORS;

	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		out_main($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// ���������� ����� ������ ��������
	if ( isset($_POST['album_id']) && (preg_match("/^\d+$/", $_POST['album_id'])) ) {
		$album_id = $_POST['album_id'];
	} elseif ( isset($_GET['album_id']) && (preg_match("/^\d+$/", $_GET['album_id'])) ) {
		$album_id = $_GET['album_id'];
	} else {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// ���� ������� ��� � ������ �������� - ������
	if (!isset($albums_info['list'][$album_id])) {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// ��������� ��� ���� �� �������� ���� � ������, ���� ������ ������
	// ���� �����-������ ���� ��� ������ ����� �� ������ - �� ����� ��������� ����� ��������� � ��
	$album_info = $albums_info['list'][$album_id];

	$smarty->assign_by_ref("album_info", $album_info);

	// ���� ��� ������ � POST - ����� ������ ����� �������������
	if (!isset($_POST['album_id'])) {
		out_main($smarty->fetch("farch/albums_manage_delete.tpl"));
	}

	// ��������
	$status = far_db_remove_album($album_id);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("farch/albums_manage_delete.tpl"));
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_FARCH_ERRORS['TREE_ERROR']);
		out_main($smarty->fetch("farch/albums_manage_delete.tpl"));
	}

	// ����� �������
	out_main($smarty->fetch("farch/albums_manage_remove.tpl"));
*/
?>