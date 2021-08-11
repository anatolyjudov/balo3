<?php

require_once("$entrypoint_path/components/meta/meta_init.php");

do {

	// ������� ���������
	if (array_key_exists("p_filter", $_GET) && (preg_match("/^[\w-\/]+$/", $_GET['p_filter']))) {
		$p_filter = $_GET['p_filter'];
	} else {
		$p_filter == "";
	}

	$smarty->assign_by_ref("p_filter", $p_filter);

	$obj_meta_info = meta_db_get_metainfo_branch($p_filter);
	if ($obj_meta_info === "error") {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
	}

	$smarty->assign_by_ref("meta_info", $obj_meta_info);

	$users_rights = users_db_get_multiple_rights(users_get_user_id(), "CHANGE_METAINFO");

	// ��������� �����
	// ��� ������ ������� ������...
	foreach($obj_meta_info as $meta_id=>$meta_row) {
		// ���������� ����...
		$uri = $meta_row['URI'];
		// ���������� ��� branches...
		$nearest_parent = "";
		$nearest_parent_state = 0;
		// ��� ������ ������ � ������ CHANGE_METAINFO:
		if (count($users_rights) > 0) {
			foreach($users_rights['CHANGE_METAINFO'] as $branch=>$state) {
				// ���� ��� ����� - ���� �� ������� ��������������� �������, �� ����� state
				if ( (strpos($uri, $branch) === 0) && (strlen($branch) >= strlen($nearest_parent)) ) {
					$nearest_parent_state = $state;
					$nearest_parent = $branch;
				}
			}
		}
		// ���� ���������, �� ������ ��� �� �������
		if ($nearest_parent_state == 0) {
			unset($obj_meta_info[$meta_id]);
		}
	}

	// ������ ������
	balo3_controller_output($smarty->fetch("$templates_path/meta/manage.tpl"));

} while (false);

?>

<?php
/*
# manage.php
# ������� ���������� META, ������ �������������� �� ��������� ���������
# ��� ������ ����, ��� ����� �������� ������������ ������� CHANGE_METAINFO

include($file_path."/includes/meta/meta_config.php");
include($file_path."/includes/meta/meta_db.php");
include($file_path."/includes/meta/meta_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;
global $_META_ERRORS;
global $meta_error_states;

// ������� ���������
if (array_key_exists("p_filter", $_GET) && (preg_match("/^[\w-\/]+$/", $_GET['p_filter']))) {
	$p_filter = $_GET['p_filter'];
} else {
	$p_filter == "";
}

$smarty->assign_by_ref("p_filter", $p_filter);

$meta_info = meta_db_get_metainfo_branch($p_filter);
if ($meta_info === "error") {
	$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
}

//show_ar($meta_info);
$smarty->assign_by_ref("meta_info", $meta_info);

$rights = db_get_multiple_rights(get_user_id(), "CHANGE_METAINFO");

//show_ar($rights);

// ��������� �����
// ��� ������ ������� ������...
foreach($meta_info as $meta_id=>$meta_row) {
	// ���������� ����...
	$uri = $meta_row['URI'];
	// ���������� ��� branches...
	$nearest_parent = "";
	$nearest_parent_state = 0;
	// ��� ������ ������ � ������ CHANGE_METAINFO:
	foreach($rights['CHANGE_METAINFO'] as $branch=>$state) {
		// ���� ��� ����� - ���� �� ������� ��������������� �������, �� ����� state
		if ( (strpos($uri, $branch) === 0) && (strlen($branch) >= strlen($nearest_parent)) ) {
			$nearest_parent_state = $state;
			$nearest_parent = $branch;
		}
	}
	// ���� ���������, �� ������ ��� �� �������
	if ($nearest_parent_state == 0) {
		unset($meta_info[$meta_id]);
	}
}

// ������ ��� � ������
out_main($smarty->fetch("meta/manage.tpl"));
*/
?>