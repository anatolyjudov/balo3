<?php

/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/manuka/manuka_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/manuka/manuka_db.php");

/* ������� � ������ ������� ���������� */
require("$components_path/manuka/manuka_functions.php");

/* �������� ������� ���������� MANUKA - ���������� ������� � �������� ���������� � ��� � ������ ���� balo3_node_info */
$balo3_node_info = manuka_get_node_info();
if ($balo3_node_info === "notfound") {
	// ���� ������� �� �������, ���������� ������ 404
	balo3_error404();
}


?>