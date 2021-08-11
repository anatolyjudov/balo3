<?php

$entrypoint_path = substr(__FILE__, 0, -1*strlen("/standalone/standalone_init.php"));

// ����������
if (extension_loaded('newrelic')) {
	newrelic_set_appname("sd2.zyxel.ru");
}

/* ���������� ����� ���� */
require("$entrypoint_path/balo3/config.php");
require("$entrypoint_path/balo3/db.php");
require("$entrypoint_path/balo3/functions.php");
require("$entrypoint_path/balo3/smarty/Smarty.class.php");

/* ��������� ����� */
require("$entrypoint_path/common/common_config.php");

/* ���������� � ����� ������ */
balo3_db_sql_connect($common_dbhost, $common_dbuser, $common_dbpass, $common_dbname);

/* �������������� ������������ � ��� ������� ��������� */
$smarty = new Smarty;
balo3_smarty_init();

/* ������������� ������� ����� */
require("$entrypoint_path/common/common_db.php");
require("$entrypoint_path/common/common_functions.php");

setlocale(LC_ALL, 'ru_RU.UTF-8');

/* ���������� ������� ����� */
$common_current_brand = "zyxel";

/* ��������� �������� ������� � ���������� ����������, ��������� �� ������ */
$common_domain = $common_brands_info[$common_current_brand]["common_domain"];
$common_simple_domain = $common_brands_info[$common_current_brand]["common_simple_domain"];
$common_brand_id = $common_brands_info[$common_current_brand]["brand_id"];

?>