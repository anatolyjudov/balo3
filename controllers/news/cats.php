<?php

//include('$components_path/news/news_config.php');
//include('$components_path/news/news_db.php');

/* ����������� ��������� � ���������� ���������� ���������� */
require("$components_path/news/news_config.php");

/* ������� ���� ������ ���������� */
require("$components_path/news/news_db.php");

/*if(!db_check_rights(get_user_id(), 'MANAGE_NEWS'))
{
	out_error('ACCESS_DENIED');
	die();
}*/

global $smarty;

$smarty->assign("cats", news_db_get_cats());

out_main($smarty->fetch("news/cats_list.tpl"));

?>