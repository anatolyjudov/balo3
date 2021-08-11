<?php

require_once("$entrypoint_path/components/staticpages/staticpages_init.php");

do {

	// ��������� ��������
	if ( (!array_key_exists("node", $_POST)) || (!preg_match("/^\d+$/", $_POST['node'])) ) {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}

	// ���������� � �������
	$old_node_info = manuka_db_get_node_info_by_id($_POST['node']);
	if ($old_node_info == "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	if ($old_node_info == "notfound") {
		// ��������� ������
		balo3_error("no such node", true);
		exit;
	}
	$smarty->assign_by_ref("node_info", $old_node_info);

	// �������� ����
	if (!users_db_check_rights(users_get_user_id(), 'MODIFY_HTML', $old_node_info['NODE_PATH'])) {
		// ��������� ������
		balo3_error403();
		exit;
	}

	$page_id = false;
	foreach($old_node_info['controllers'] as $controller) {
		if ( ("staticpages" === $controller['CONTROLLER_FAMILY']) && ("page" === $controller['CONTROLLER'])) {
			$page_id = $controller['CONTROLLER_ARGS'];
		}
		$controller_info = $controller;
	}
	if ($page_id === false) {
		// ��������� ������
		balo3_error("node has not staticpage controller", true);
		exit;
	}
	$smarty->assign_by_ref("controller_info", $controller_info);

	// ���������� � ��������
	$old_page_info = staticpages_db_get_page_by_id($page_id);
	if ("error" === $page_id) {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	if ("notfound" === $page_id) {
		// ��������� ������
		balo3_error("no such node", true);
		exit;
	}
	$smarty->assign("page_info", $old_page_info);

	// �������
	$status = staticpages_db_remove_page($old_node_info['NODE_ID'], $page_id);
	if ("error" === $status) {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}


	balo3_controller_output($smarty->fetch("$templates_path/staticpages/remove.tpl"));

} while (false);

?>

<?
/*
include($file_path."/includes/html/html_db.php");
include($file_path."/includes/html/html_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;
global $_HTML_ERRORS;

	// ��������� ��������
	if ( (!array_key_exists("node", $_POST)) || (!preg_match("/^\d+$/", $_POST['node'])) ) {
		// ��������� ������
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// ���������
	$html_node_info = db_get_node_by_id($_POST['node']);
	if ($html_node_info == "error") {
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($html_node_info == "notfound") {
		// ��������� ������
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}
	$smarty->assign("node_info", $html_node_info);

	if (!db_check_rights(get_user_id(), 'DELETE_HTML', $html_node_info['M_PATH'])) {
		// ��������� ������
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}

	// ������� html-��������
	$status = db_html_remove_html_node($_POST['node']);
	if ($status == "notfound") {
		// ��������� ������
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}
	if ($status == "error") {
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($status == "nothtml") {
		// ��������� ������
		echo $_HTML_ERRORS['NON_HTML_IN_TREE'];
		exit;
	}

	// ������� ��������������
	if ($meta_component) {
		include($file_path."/includes/meta/meta_config.php");
		include($file_path."/includes/meta/meta_db.php");
	
		$uri = $html_node_info['M_PATH'];
		$status = meta_db_delete_metainfo_branch($uri);
		if ($status === "error") {
			echo $meta_error;
			exit;
		}
	}

	// ������� ������
	out_main($smarty->fetch("html/remove.tpl"));

*/
?>