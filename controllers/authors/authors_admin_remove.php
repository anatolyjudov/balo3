<?php

require_once("$entrypoint_path/components/authors/authors_init.php");

do {

	// если удаление не подтверждено
	if (!isset($_POST['confirmed'])) {

		//проверка id из $_GET
		if ( (!isset($_GET['author_id'])) || ($_GET['author_id'] == "") || ($_GET['author_id'] == "0") ) {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		//запрос инфо по автору
		$author_info = authors_db_get_author_by_id($_GET['author_id']);

		if ($author_info == "error") {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		//существует ли автор с таким id
		if ($author_info['id'] != "") {
			$smarty->assign_by_ref("author_info", $author_info);
			//show_ar($author_info);
		} else {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		//выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
		break;

	} else {

		// иначе - удаляем
		if (isset($_POST['author_id'])) {
			$status = authors_db_remove_author($_POST['author_id']);
		} else {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		if ($status == "error") {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		
		// выдаем шаблон
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_removed.tpl"));
	}

} while (false);

?>