<?php

/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/settings/settings_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/settings/settings_db.php");

/* ������� ���������� */
require("$components_path/settings/settings_functions.php");


$smarty->assign_by_ref("settings_admin_branch", $settings_admin_branch);

?>