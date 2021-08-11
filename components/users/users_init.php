<?php


/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/users/users_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/users/users_texts.php");

/* ������� */
require("$components_path/users/users_functions.php");

/* ������� ���� ������ */
require("$components_path/users/users_db.php");

multilang_load_smarty_config("users");

/* �������������� ������ */
users_init_session();

// �������� ���� �������, � ������ ���� ����� ������������
// �������� �� ������� � ������������ ���� �� ������ � ���� �����
if ( !users_db_check_rights( users_get_user_id(), 'ACCESS_NODE', $balo3_node_info['node_path'] ) ) {
	balo3_error403();
	exit;
}

?>
