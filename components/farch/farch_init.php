<?php


/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/farch/farch_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/farch/farch_db.php");

/* ������� � ������ ������� ���������� */
require("$components_path/farch/farch_functions.php");
require("$components_path/farch/farch_image_functions.php");


multilang_load_smarty_config("farch");

$smarty->assign_by_ref("farch_component", $farch_component);
$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);
$smarty->assign_by_ref("farch_users_session_info", $users_session_info);

?>