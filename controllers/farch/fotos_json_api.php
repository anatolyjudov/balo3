<?php

error_reporting(0);
require_once("$entrypoint_path/components/farch/farch_init.php");
require_once("$entrypoint_path/components/farch/farch_controllers_functions.php");

do {

	if (isset($_POST['act'])) {
		$act = $_POST['act'];
	} elseif (isset($_GET['act'])) {
		$act = $_GET['act'];
	} else {
		$act = "";
	}

	switch ($act) {
		case "fotos_list":
		case "foto_info":
		case "foto_info_update":
		case "fotos_remove":
		case "fotos_get_tags":
		case "fotos_save_tags":
		case "fotos_save_order":
		case "foto_upload":
			break;
		default:
			farch_json_out("error", $_FARCH_ERRORS['UNKNOWN_ACTION']);
			break;
	}

	switch ($act) {
		case "fotos_list":
			list($state, $result) = farch_json_fotos_list();
			break;
		case "foto_info":
			list($state, $result) = farch_json_foto_info();
			break;
		case "foto_info_update":
			list($state, $result) = farch_json_foto_info_update();
			break;
		case "fotos_remove":
			list($state, $result) = farch_json_fotos_remove();
			break;
		case "fotos_get_tags":
			list($state, $result) = farch_json_fotos_get_tags();
			break;
		case "fotos_save_tags":
			list($state, $result) = farch_json_fotos_save_tags();
			break;
		case "fotos_save_order":
			list($state, $result) = farch_json_fotos_save_order();
			break;
		case "foto_upload":
			farch_json_foto_upload();
			exit;
			break;
	}

	farch_json_out($state, $result);

	// גûהאול ראבכמם
	// balo3_controller_output($smarty->fetch("$templates_path/farch/fotos_manage.tpl"));

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

global $_ERRORS;
global $_FARCH_ERRORS;

	if (isset($_POST['act'])) {
		$act = $_POST['act'];
	} elseif (isset($_GET['act'])) {
		$act = $_GET['act'];
	} else {
		$act = "";
	}

	switch ($act) {
		case "fotos_list":
		case "foto_info":
		case "foto_info_update":
		case "fotos_remove":
		case "fotos_get_tags":
		case "fotos_save_tags":
		case "fotos_save_order":
		case "foto_upload":
			break;
		default:
			out("error", $_FARCH_ERRORS['UNKNOWN_ACTION']);
			break;
	}

	switch ($act) {
		case "fotos_list":
			list($state, $result) = fotos_list();
			break;
		case "foto_info":
			list($state, $result) = foto_info();
			break;
		case "foto_info_update":
			list($state, $result) = foto_info_update();
			break;
		case "fotos_remove":
			list($state, $result) = fotos_remove();
			break;
		case "fotos_get_tags":
			list($state, $result) = fotos_get_tags();
			break;
		case "fotos_save_tags":
			list($state, $result) = fotos_save_tags();
			break;
		case "fotos_save_order":
			list($state, $result) = fotos_save_order();
			break;
		case "foto_upload":
			foto_upload();
			exit;
			break;
	}

	out($state, $result);
*/
?>