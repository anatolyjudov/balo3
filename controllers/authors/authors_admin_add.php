<?php

require_once("$entrypoint_path/components/authors/authors_init.php");

do {

	// если нет ничего в POST - выдаём просто форму
	if (!isset($_POST['confirmed'])) {
		$smarty->assign("action", "add");
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
		break;
	}

	// иначе - принимаем параметры
	if (isset($_POST['sirname']) && ($_POST['sirname'] != '')) {
		$author_info['sirname'] = $_POST['sirname'];
	} else {
		$author_info['sirname'] = '';
	}

	if (isset($_POST['name']) && ($_POST['name'] != '')) {
		$author_info['name'] = $_POST['name'];
	} else {
		$author_info['name'] = '';
	}

	if (isset($_POST['patronymic']) && ($_POST['patronymic'] != '')) {
		$author_info['patronymic'] = $_POST['patronymic'];
	} else {
		$author_info['patronymic'] = '';
	}

	if (isset($_POST['short_text']) && ($_POST['short_text'] != '')) {
		$author_info['short_text'] = $_POST['short_text'];
	} else {
		$author_info['short_text'] = '';
	}

	if (isset($_POST['description']) && ($_POST['description'] != '')) {
		$author_info['description'] = $_POST['description'];
	} else {
		$author_info['description'] = '';
	}

	// сохранение входных данных для шаблона
	$smarty->assign_by_ref("author_info", $author_info);

	// проверка входных данных
	if ((($author_info['sirname']) == '') || (($author_info['name']) == '')) {
		$smarty->assign("errmsg", $_AUTHORS_ERRORS['EMPTY_AUTHOR_NAME']);
		$smarty->assign("action", "add");
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
		break;
	}

	// добавление автора в БД
	$status = authors_db_add_author($author_info);
	if ($status == "error") {
		$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
		$smarty->assign("action", "add");
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
		break;
	}

	
	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_added.tpl"));

} while (false);

?>