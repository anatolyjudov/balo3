<?php

require_once("$entrypoint_path/components/authors/authors_init.php");

do {

	// если нет ничего в POST - выдаём форму с текущим содержимым
	if (!isset($_POST['confirmed'])) {

		//проверка id из $_GET
		if ( (!isset($_GET['author_id'])) || ($_GET['author_id'] == "") || ($_GET['author_id'] == "0") ) {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
			break;
		}

		//запрос инфо по автору
		$author_info = authors_db_get_author_by_id($_GET['author_id']);

		if ($author_info == "error") {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
			break;
		}

		//существует ли автор с таким id
		if ($author_info['id'] != "") {
			$smarty->assign("action", "modify");
			$smarty->assign_by_ref("author_info", $author_info);
			//show_ar($author_info);
		} else {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
			break;
		}

		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));

	// а если отправлены данные, то принимаем их
	} else {

	if (isset($_POST['id']) && ($_POST['id'] != '')) {
		$author_info['id'] = $_POST['id'];
	} else {
		$author_info['id'] = '';
	}

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
		$smarty->assign("action", "modify");
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
		break;
	}

	// изменение информации по автору в БД
	$status = authors_db_modify_author($author_info);
	
	if ($status == "error") {
		$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
		$smarty->assign("action", "modify");
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_form.tpl"));
		break;
	}

	// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_modified.tpl"));

	}

} while (false);

?>