<?php

/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/textblocks/textblocks_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/textblocks/textblocks_db.php");

multilang_load_smarty_config("textblocks");

$smarty->assign_by_ref("textblocks_admin_branch", $textblocks_admin_branch);


?>