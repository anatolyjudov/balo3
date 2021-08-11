<?php

require_once("$entrypoint_path/components/staticpages/staticpages_init.php");

do {

	// ��������� ��������
	if ( (!array_key_exists("parent_node", $_POST)) || (!preg_match("/\d+/", $_POST['parent_node'])) ) {
		// ��������� ������
		balo3_error("no such node", true);
		exit;
	}

	// �������� ���������� � ������������ �������
	$parent_node = manuka_db_get_node_info_by_id($_POST['parent_node']);
	if("error" == $parent_node) {
		balo3_error("db error", true);
		exit;
	}
	if("notfound" == $parent_node) {
		balo3_error("no such node", true);
		exit;
	}

	if (!users_db_check_rights(users_get_user_id(), 'ADD_HTML', $parent_node['NODE_PATH'])) {
		// ��������� ������
		balo3_error403();
		exit;
	}

	$dirname = trim($_POST['dirname'], "/");
	$placeholder = $_POST['placeholder'];
	if ( ($dirname=="") || ($placeholder=="") || (!preg_match("/^[\w\-_]+$/", $dirname)) ||
			($_POST['controller_family']=="") ||
					($_POST['controller']=="") ||
						($_POST['layout']=="") ) {
		// ��������� ������
		balo3_error("bad parameter", true);
		exit;
	}

	$new_node_path = $parent_node['NODE_PATH'] . $dirname . "/";

	// ���������
	$node_id = staticpages_db_add_page($new_node_path, $_POST['controller_family'], $_POST['controller'], $_POST['layout'], $placeholder);
	if ($node_id == "error") {
		// ��������� ������
		balo3_error("db error", true);
		exit;
	}
	if ($node_id == "notfound") {
		// ��������� ������
		balo3_error("no such node", true);
		exit;
	}

	// ���������� ������ ������ � ������
	$smarty->assign_by_ref("manuka_nodes", $manuka_nodes);

	balo3_controller_output($smarty->fetch("$templates_path/common/redirectback2.tpl"));

} while (false);

?>


<?

/*
include($file_path."/includes/html/html_db.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

// ����� �� ��������� �������

	// ��������� ��������
	if ( (!array_key_exists("m_parent", $_POST)) || (!preg_match("/\d+/", $_POST['m_parent'])) ) {
		// ��������� ������
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// ���������� � �������
	$html_node_info = db_get_node_by_id($_POST['m_parent']);
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

	if (!db_check_rights(get_user_id(), 'ADD_HTML', $html_node_info['M_PATH'])) {
		// ��������� ������
		echo $_ERRORS['ACCESS_DENIED'];
		exit;
	}

	$m_dirname = trim($_POST['m_dirname'], "/");
	if ( ($m_dirname=="") || (!preg_match("/^[\w\-_]+$/", $m_dirname)) ||
			($_POST['m_component']=="") ||
					($_POST['m_executive']=="") ||
						($_POST['m_template']=="") ) {
		// ��������� ������
		echo $_ERRORS['BAD_PARAMETER'];
		exit;
	}

	// ���������
	$m_id = db_html_add_node($_POST['m_parent'], $m_dirname, $_POST['m_component'], $_POST['m_executive'], $_POST['m_template']);
	if ($m_id == "error") {
		// ��������� ������
		echo $_ERRORS['DB_ERROR'];
		exit;
	}
	if ($m_id == "notfound") {
		// ��������� ������
		echo $_ERRORS['NO_SUCH_NODE'];
		exit;
	}

	// ������� ������
	$smarty->display("redirectback2.tpl");

*/
?>