<?
include_once($file_path."/includes/isetane/isetane_db.php");
include_once($file_path."/includes/isetane/isetane_config.php");
include_once($file_path."/includes/isetane/isetane_functions.php");
include_once($file_path."/includes/isetane/isetane_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS;

	$smarty->assign_by_ref("isetane_foto_params", $isetane_foto_params);


	// ����� �����
	if (!isset($_POST['upload'])) {
		out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
	}

	// ���������� ����������
	// ������� ���� ������������ ������
	foreach($_FILES as $k=>$file_info) {

		// ������ 4 - �� ����������� ����
		if ($file_info['error'] == 4) {
			continue;
		}

		// ����� �����
		// (� ����� ������� ������ ���� ���������� fN, ��� N - �����)
		if (!preg_match("/^f(\d+)$/", $k, $matches)) {
			// �����-�� ����� ����, �� ���
			continue;
		}
		$file_number = $matches[1];

		// ��������� � ���� ������
		$foto_id = istn_db_create_foto_info("", "", "");
		if ($foto_id === "error") {
			$smarty->assign("errmsg_add", $_ISTN_FOTO_ERRORS['DB_ERROR']);
			out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
		}

		// �������� � �������, ������� ��������
		list($status, $error, $tech_info) = isetane_fotos_save_foto($foto_id, $file_info);
		if ($status === "error") {
			istn_db_remove_foto($foto_id);
			$smarty->assign("errmsg_add", $_ISTN_FOTO_ERRORS[$error]);
			out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
		}

		// �� � �������, ��������� ������ ����������
		// istn_db_update_foto_info($foto_id, $foto_title, $tech_info, $sort_value = "")
		$status = istn_db_update_foto_info($foto_id, "", $tech_info);
		if ($status === "error") {
			$smarty->assign("errmsg_add", $_ISTN_FOTO_ERRORS['DB_ERROR2']);
			out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));
		}

		//show_ar($tech_info);

	}

	$smarty->assign("msg_add", $_ISTN_FOTO_MESSAGES['SUCCESSFUL_UPLOAD']);
	out_main($smarty->fetch("isetane/fotos_manage_new.tpl"));

?>