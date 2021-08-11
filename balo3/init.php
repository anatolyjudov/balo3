<?php

/*
* ��� ������� ����������� ����, ����������� ������� ������������������ ��������
*/

/* ���������� ����� ���� */
require("$entrypoint_path/balo3/config.php");
require("$entrypoint_path/balo3/db.php");
require("$entrypoint_path/balo3/functions.php");
require("$entrypoint_path/balo3/smarty/Smarty.class.php");

/* ��������� ����� */
require("$entrypoint_path/settings/settings.php");

/* �������� ��������� �������� ���� */
balo3_load_texts("$entrypoint_path/balo3/texts.php");

/* ���������� � ����� ������ */
balo3_db_sql_connect($settings_dbhost, $settings_dbuser, $settings_dbpass, $settings_dbname);

/* �������������� ������������ � ��� ������� ��������� */
$smarty = new Smarty;
balo3_smarty_init();
balo3_load_smarty_config("$templates_path/balo3/texts.cfg");

/* ������� ������� HTTP-��������� */
balo3_output_headers();

/* ���������� ������� ������� � �������� ���������� � ������������ � ��������, ������� ���������� ���������� */
require("$entrypoint_path/balo3/manuka.php");

/* �������������� ��������� COMMON, ���������� ����� ������� ������� ����� */
require("$entrypoint_path/common/common_init.php");

/* ����������� ������ ������������, ��������������� ���� ������� */
if (count($balo3_node_info['controllers']) > 0) {
	foreach($balo3_node_info['controllers'] as $controller_placeholder=>$controller_data) {
		$controller_args = isset($controller_data['controller_args']) ? $controller_data['controller_args'] : array();
		require("$controllers_path/".$controller_data['controller_family']."/".$controller_data['controller'].".php");
	}
}

/* ����� ������ ����� layout-������, ��������������� ���� ������� */
balo3_process_layout();

?>