<?php

require_once("$entrypoint_path/components/authors/authors_init.php");

do {

	// ���� �������� �� ������������
	if (!isset($_POST['confirmed'])) {

		//�������� id �� $_GET
		if ( (!isset($_GET['author_id'])) || ($_GET['author_id'] == "") || ($_GET['author_id'] == "0") ) {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		//������ ���� �� ������
		$author_info = authors_db_get_author_by_id($_GET['author_id']);

		if ($author_info == "error") {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['DB_ERROR']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		//���������� �� ����� � ����� id
		if ($author_info['id'] != "") {
			$smarty->assign_by_ref("author_info", $author_info);
			//show_ar($author_info);
		} else {
			$smarty->assign("errmsg", $_AUTHORS_ERRORS['INCORRECT_ID']);
			balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
			break;
		}

		//������ ������
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_delete.tpl"));
		break;

	} else {

		// ����� - �������
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

		
		// ������ ������
		balo3_controller_output($smarty->fetch("$templates_path/authors/authors_admin_removed.tpl"));
	}

} while (false);

?>