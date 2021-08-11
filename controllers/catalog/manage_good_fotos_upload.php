<?php

// ���������� �������� � ajax-��������!

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {


	$l = count(explode("/", trim($catalog_admin_uri, "/")));
	$section_id = $balo3_request_info['path'][$l+1];
	$good_id = $balo3_request_info['path'][$l+4];
	if (!is_numeric($good_id)) {
		balo3_error('bad parameter', true);
		exit;
	}
	$smarty->assign_by_ref("section_id", $section_id);
	$smarty->assign_by_ref("good_id", $good_id);


	$file_info = $_FILES['file_upload'];
	// show_ar($file_info);
	// ������ 4 - �� ����������� ����
	if ($file_info['error'] == 4) {
		echo 'bad file upload';
		exit;
		//return array('error', array('bad file upload'));
	}

	// ����� �����
	// (� ����� ������� ������ ���� ���������� fN, ��� N - �����)
	$file_number = 0;

	// ��������� � ���� ������
	$good_foto_id = catalog_db_add_foto($good_id, array());
	if ($good_foto_id === "error") {
		echo "db error ";
		echo $_FARCH_FOTO_ERRORS['DB_ERROR'];
		exit;
	}

	// �������� � �������, ������� ��������
	list($status, $error, $tech_info) = farch_fotos_save_foto("good_" . $good_id, $good_foto_id, $file_info);
	if ($status === "error") {
		catalog_db_remove_foto($good_foto_id);
		echo "$error ";
		echo $_FARCH_FOTO_ERRORS[$error];
		exit;
	}

	// �� � �������, ��������� ������ ����������
	// far_db_update_foto_info($foto_id, $foto_title, $tech_info, $sort_value = "")
	// show_ar($tech_info);
	$status = catalog_db_modify_foto($good_foto_id, array("TECH_INFO"=>$tech_info));
	if ($status === "error") {
		echo "db error2 ";
		echo $_FARCH_FOTO_ERRORS['DB_ERROR2'];
		exit;
	}

	echo "1";
	exit;

} while (false);

?>