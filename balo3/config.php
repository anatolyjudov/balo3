<?php

/* uri */
$root_path = "";												# ���� � ����� ����� ������������ ����� �������, ��� ����� �� �����

/* �������� ���� */
$file_path = $entrypoint_path . $root_path;						# ������ ���� � ����� �����
$templates_path = $entrypoint_path . "/templates";				# ������� � ���������, ��� ����� �� �����
$components_path = $entrypoint_path . "/components";			# ������� � ������������, ��� ����� �� �����
$controllers_path = $entrypoint_path . "/controllers";			# ������� � �������������, ��� ����� �� �����
$files_path = $entrypoint_path . "/files";						# ���� � ������������� ������
$tmpfiles_path = $entrypoint_path . "/tmp";
$smarty_tmp_path = $tmpfiles_path . "/smarty_tmp";				# ���� � ��������� ����������� ������

/* ����, ����������� ��� smarty */
define("SMARTY_DIR", $entrypoint_path . "/balo3/smarty/");	# ���������, ����������� �� ���� � ���������� smarty

/* ������������ ����������� ��������� ��� ������ � ���������, ������ ������� ������� ���� */
$balo3_use_manuka_component = "manuka";

/* ���������� ���������� */
$balo3_request_info = array();
$balo3_node_info = array(
	"controllers" => array(),
	"layout" => array()
);
$balo3_template_data = array();
$balo3_controllers_output = array();

// ������ � ����������� �� �������
$balo3_errors = array();

// ������ ��������� ��������
$balo3_texts = array();

//
$balo3_db = false;
?>