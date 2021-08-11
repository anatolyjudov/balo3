<?php


/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/authors/authors_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/authors/authors_db.php");

/* ������� � ������ ������� ���������� */
//require("$components_path/authors/authors_functions.php");

require("$components_path/authors/authors_texts.php");

multilang_load_smarty_config("authors");

$smarty->assign("authors_component", $authors_component);

// ���������� ��� �� ���������� ��������� catalog
$authors_catalog_component = true;

if ($authors_catalog_component) {

	/* ������������� ���������� catalog */
	require_once("$components_path/catalog/catalog_init.php");

	if (!isset($catalog_component)) {

	balo3_error("authors requires catalog component", true);
	exit;

	}
}

$smarty->assign("authors_catalog_component", $authors_catalog_component);

?>