<?php

multilang_load_texts("search");

multilang_load_smarty_config("search");

/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/search/search_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/search/search_db.php");

/* ������� ������ */
require("$components_path/search/search_functions.php");

?>