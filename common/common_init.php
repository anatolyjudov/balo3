<?php

/* ����� ����������� ��������� � ���������� ���������� ����� */
require("$entrypoint_path/common/common_config.php");

/* ����� ������� ���� ������ ����� */
require("$entrypoint_path/common/common_db.php");

/* ����� ������� � ������ ������� ����� */
require("$entrypoint_path/common/common_functions.php");

/* ������ ������ � ������������� */
$smarty->assign("base_path", $root_path);
$smarty->assign("pics_path", $pics_path);
$smarty->assign("templates_path", $templates_path);
$smarty->assign("common_today_year", date("Y"));
$smarty->assign_by_ref("common_domain", $common_domain);
$smarty->assign("uri_array", $balo3_request_info['path']);
$smarty->assign("common_strpath", $balo3_request_info['strpath']);

$smarty->assign("common_simple_domain", $common_simple_domain);

/* ��������� �������� */
require_once("$components_path/settings/settings_init.php");
settings_load('common', true);

/* ��������� ��������������� */
require_once("$components_path/multilang/multilang_init.php");

//multilang_load_texts("$entrypoint_path/common/common_texts.php");

multilang_load_smarty_config("common");

multilang_load_smarty_config("feedback");

/* ��������� �������������� */
require_once("$components_path/meta/meta_init.php");

/* ��������� ��� �������������, ������ � ����������� */
require_once("$components_path/users/users_init.php");

/* ��������� ���������� */
require_once("$components_path/popups/popups_init.php");

# ��������� ���������
$antispam_value = md5(date("Y-m-d")."-dk83");
$antispam_field = "du03ifpsn347f";
$smarty->assign("antispam_field", $antispam_field);
$smarty->assign("antispam_value", $antispam_value);
$smarty->assign("antispam_func_name", "zxcv4kd8".rand(100,999));

require_once("$entrypoint_path/components/catalog/catalog_init.php");

//catalog_refresh_specials();


$common_admin_mode = false;
if ( users_db_check_rights( users_get_user_id(), 'ACCESS_NODE', "/admin/" ) ) {
	$common_admin_mode = true;
}
$smarty->assign("common_admin_mode", $common_admin_mode);

?>