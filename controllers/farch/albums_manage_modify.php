<?php

require_once("$entrypoint_path/components/farch/farch_init.php");

do {

	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		balo3_controller_output($_ERRORS['DB_ERROR']);
		break;
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// определяем какой альбом показать
	if ( isset($_POST['album_id']) && (preg_match("/^\d+$/", $_POST['album_id'])) ) {
		$album_id = $_POST['album_id'];
	} elseif ( isset($_GET['album_id']) && (preg_match("/^\d+$/", $_GET['album_id'])) ) {
		$album_id = $_GET['album_id'];
	} else {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// если альбома нет в списке альбомов - ошибка
	if (!isset($albums_info['list'][$album_id])) {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}

	// поскольку вся инфа об альбомах есть в списке, берём просто оттуда
	// если когда-нибудь инфа для списка будет не полной - то здесь добавится метод обращения к бд
	$old_album_info = $albums_info['list'][$album_id];
	$old_album_description_info = far_db_get_album_description_info($album_id);
	if ($old_album_description_info === "error") {
		balo3_controller_output($_ERRORS['DB_ERROR']);
		break;
	}
	if ($old_album_description_info === "notfound") {
		balo3_controller_output($_ERRORS['BAD_PARAMETER']);
		break;
	}
	$old_album_info = array_merge($old_album_info, $old_album_description_info);

	// если нет ничего в POST - выдаём просто форму
	if (!isset($_POST['album_id'])) {
		$smarty->assign_by_ref("album_info", $old_album_info);
		balo3_controller_output($smarty->fetch("farch/albums_manage_edit.tpl"));
		break;
	}

	$smarty->assign_by_ref("old_album_info", $old_album_info);

	// иначе - принимаем параметры
	if (isset($_POST['album_name']) && ($_POST['album_name'] != '')) {
		$album_info['ALBUM_NAME'] = $_POST['album_name'];
	} else {
		$album_info['ALBUM_NAME'] = '';
	}
	if (isset($_POST['parent_id']) && preg_match("/^\d+$/", $_POST['parent_id'])) {
		$album_info['PARENT_ID'] = $_POST['parent_id'];
	} else {
		$album_info['PARENT_ID'] = '';
	}
	if (isset($_POST['description']) && ($_POST['description'] != '')) {
		$album_info['DESCRIPTION'] = $_POST['description'];
	} else {
		$album_info['DESCRIPTION'] = '';
	}
	if (isset($_POST['album_authors']) && ($_POST['album_authors'] != '')) {
		$album_info['ALBUM_AUTHORS'] = $_POST['album_authors'];
	} else {
		$album_info['ALBUM_AUTHORS'] = '';
	}
	if (isset($_POST['published']) && ($_POST['published'] == 'on') ) {
		$album_info['PUBLISHED'] = 1;
	} else {
		$album_info['PUBLISHED'] = 0;
	}

	// сохранение входных данных для шаблона
	$smarty->assign_by_ref("album_info", $album_info);

	// проверка входных данных
	if ($album_info['PARENT_ID'] == '') {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		balo3_controller_output($smarty->fetch("farch/albums_manage_edit.tpl"));
		break;
	}
	if ($album_info['ALBUM_NAME'] == '') {
		$smarty->assign("errmsg", $_FARCH_ERRORS['EMPTY_ALBUM_NAME']);
		balo3_controller_output($smarty->fetch("farch/albums_manage_edit.tpl"));
		break;
	}

	// файл картинки
	//show_ar($_FILES);exit;
	if (isset($_FILES['album_picture']) && ($_FILES['album_picture']['error']!=4)) {

		$file_info = $_FILES['album_picture'];

		// работаем с файлами, генерим превью
		list($status, $error, $tech_info) = farch_fotos_save_foto($album_id, 'title', $file_info);
		if ($status === "error") {
			$smarty->assign("errmsg", $_FARCH_FOTO_ERRORS[$error]);
			balo3_controller_output($smarty->fetch("farch/albums_manage_edit.tpl"));
			break;
		}

		$album_info['PICTURE_TECH_INFO'] = $tech_info;

	}

	// изменение
	$status = far_db_modify_album($album_id, $album_info);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		balo3_controller_output($smarty->fetch("farch/albums_manage_edit.tpl"));
		break;
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_FARCH_ERRORS['TREE_ERROR']);
		balo3_controller_output($smarty->fetch("farch/albums_manage_edit.tpl"));
		break;
	}

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/farch/albums_manage_modify.tpl"));

} while (false);

?>



<?php
/*
include_once($file_path."/includes/farch/farch_db.php");
include_once($file_path."/includes/farch/farch_config.php");
include_once($file_path."/includes/farch/farch_functions.php");
include_once($file_path."/includes/farch/farch_texts.php");

global $smarty;
global $params;
global $node_info;

global $_ERRORS, $_FARCH_ERRORS;

	$albums_info = far_db_get_albums();
	if ($albums_info === 'error') {
		out_main($_ERRORS['DB_ERROR']);
	}
	$smarty->assign_by_ref("albums_info", $albums_info);
	$smarty->assign_by_ref("farch_foto_params", $farch_foto_params);

	// определяем какой альбом показать
	if ( isset($_POST['album_id']) && (preg_match("/^\d+$/", $_POST['album_id'])) ) {
		$album_id = $_POST['album_id'];
	} elseif ( isset($_GET['album_id']) && (preg_match("/^\d+$/", $_GET['album_id'])) ) {
		$album_id = $_GET['album_id'];
	} else {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// если альбома нет в списке альбомов - ошибка
	if (!isset($albums_info['list'][$album_id])) {
		out_main($_ERRORS['BAD_PARAMETER']);
	}

	// поскольку вся инфа об альбомах есть в списке, берём просто оттуда
	// если когда-нибудь инфа для списка будет не полной - то здесь добавится метод обращения к бд
	$old_album_info = $albums_info['list'][$album_id];
	$old_album_description_info = far_db_get_album_description_info($album_id);
	if ($old_album_description_info === "error") {
		out_main($_ERRORS['DB_ERROR']);
	}
	if ($old_album_description_info === "notfound") {
		out_main($_ERRORS['BAD_PARAMETER']);
	}
	$old_album_info = array_merge($old_album_info, $old_album_description_info);

	// если нет ничего в POST - выдаём просто форму
	if (!isset($_POST['album_id'])) {
		$smarty->assign_by_ref("album_info", $old_album_info);
		out_main($smarty->fetch("farch/albums_manage_edit.tpl"));
	}

	$smarty->assign_by_ref("old_album_info", $old_album_info);

	// иначе - принимаем параметры
	if (isset($_POST['album_name']) && ($_POST['album_name'] != '')) {
		$album_info['ALBUM_NAME'] = $_POST['album_name'];
	} else {
		$album_info['ALBUM_NAME'] = '';
	}
	if (isset($_POST['parent_id']) && preg_match("/^\d+$/", $_POST['parent_id'])) {
		$album_info['PARENT_ID'] = $_POST['parent_id'];
	} else {
		$album_info['PARENT_ID'] = '';
	}
	if (isset($_POST['description']) && ($_POST['description'] != '')) {
		$album_info['DESCRIPTION'] = $_POST['description'];
	} else {
		$album_info['DESCRIPTION'] = '';
	}
	if (isset($_POST['album_authors']) && ($_POST['album_authors'] != '')) {
		$album_info['ALBUM_AUTHORS'] = $_POST['album_authors'];
	} else {
		$album_info['ALBUM_AUTHORS'] = '';
	}
	if (isset($_POST['published']) && ($_POST['published'] == 'on') ) {
		$album_info['PUBLISHED'] = 1;
	} else {
		$album_info['PUBLISHED'] = 0;
	}

	// сохранение входных данных для шаблона
	$smarty->assign_by_ref("album_info", $album_info);

	// проверка входных данных
	if ($album_info['PARENT_ID'] == '') {
		$smarty->assign("errmsg", $_ERRORS['BAD_PARAMETER']);
		out_main($smarty->fetch("farch/albums_manage_edit.tpl"));
	}
	if ($album_info['ALBUM_NAME'] == '') {
		$smarty->assign("errmsg", $_FARCH_ERRORS['EMPTY_ALBUM_NAME']);
		out_main($smarty->fetch("farch/albums_manage_edit.tpl"));
	}

	// файл картинки
	//show_ar($_FILES);exit;
	if (isset($_FILES['album_picture']) && ($_FILES['album_picture']['error']!=4)) {

		$file_info = $_FILES['album_picture'];

		// работаем с файлами, генерим превью
		list($status, $error, $tech_info) = farch_fotos_save_foto($album_id, 'title', $file_info);
		if ($status === "error") {
			$smarty->assign("errmsg", $_FARCH_FOTO_ERRORS[$error]);
			out_main($smarty->fetch("farch/albums_manage_edit.tpl"));
		}

		$album_info['PICTURE_TECH_INFO'] = $tech_info;

	}

	// изменение
	$status = far_db_modify_album($album_id, $album_info);
	if ($status === 'error') {
		$smarty->assign("errmsg", $_ERRORS['DB_ERROR']);
		out_main($smarty->fetch("farch/albums_manage_edit.tpl"));
	}
	if ($status === 'tree_error') {
		$smarty->assign("errmsg", $_FARCH_ERRORS['TREE_ERROR']);
		out_main($smarty->fetch("farch/albums_manage_edit.tpl"));
	}

	// вывод шаблона
	out_main($smarty->fetch("farch/albums_manage_modify.tpl"));
*/
?>