<?php

require_once("$entrypoint_path/components/catalog/catalog_init.php");

do {

	exit;

	echo "<h1>Migration</h1><br>";

	$old_db = mysql_connect("localhost", "antique", "antique", true);
	if (!$old_db  || !mysql_select_db("antique_old")) {
		die("mysql server connection error, ".mysql_errno().":".mysql_error());
		exit;
	} else {
		$query = "set names utf8";
		mysql_query($query, $old_db);
	}


	$q = "select * from D_CATEGORIES";
	$r = mysql_query($q, $old_db);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}
	$old_categories = array();
	while($row = mysql_fetch_assoc($r)) {
		$old_categories[$row['C_ID']] = $row;
	}

	//
	foreach($old_categories as $c_id=>$cat) {

		if ( $s_info = cat_get_section_by_cid($c_id) == "notfound" ) {
			$section_id = catalog_db_add_section(array(
				"PUBLISHED" => 1,
				"PARENT_ID" => 0,
				"SECTION_NAME" => $cat['C_NAME']
			));
			cat_set_cid($section_id, $c_id, '', $cat['C_DIRNAME']);
		}

	}


	$q = "select * from D_SUBCATEGORIES";
	$r = mysql_query($q, $old_db);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}
	$old_subcategories = array();
	while($row = mysql_fetch_assoc($r)) {
		$old_subcategories[$row['SC_ID']] = $row;
	}

	//
	foreach($old_subcategories as $sc_id=>$subcat) {

		if ( $s_info = cat_get_section_by_cid($subcat['C_ID'], $sc_id) == "notfound" ) {
			$parent_info = cat_get_section_by_cid($subcat['C_ID']);
			$parent_section_id = $parent_info['SECTION_ID'];
			$section_id = catalog_db_add_section(array(
				"PUBLISHED" => 1,
				"PARENT_ID" => $parent_section_id,
				"SECTION_NAME" => $subcat['SC_NAME']
			));
			cat_set_cid($section_id, $subcat['C_ID'], $sc_id, $subcat['SC_DIRNAME'], $subcat['SORT_VALUE']);
		}

	}

	//show_ar($old_categories);
	//show_ar($old_subcategories);


	$q = "select * from S_GOODS";
	$r = mysql_query($q, $old_db);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}
	$old_goods = array();
	while($row = mysql_fetch_assoc($r)) {
		$old_goods[$row['G_ID']] = $row;
	}

	//
	foreach($old_goods as $g_id=>$good) {

		//show_ar($good);

		if ( ($g_info = cat_get_good_by_gid($g_id)) == "notfound" ) {

			$section_id = cat_get_section_by_cid($good['C_ID'], $good['SC_ID']);
			if ($section_id == 'notfound') {
				continue;
			}
			$section_id = $section_id['SECTION_ID'];

			$good_id = catalog_db_add_good($section_id, trim($good['G_NAME'],"."));
			cat_set_gid($good_id, $g_id);


		} else {
			$good_id = $g_info['GOOD_ID'];
		}

		$published = ($good['G_HIDDEN'] == 1) ? 0 : 1;
		$short_text = preg_replace("/<foto id=\d+>/", "", $good['G_TEXT']);
		catalog_db_modify_good(
			$good_id,
			array(
				"PUBLISHED" => $published,
				"SHORT_TEXT" => $short_text
			)
		);

		catalog_db_remove_prices($good_id);
		catalog_db_add_price($good_id, array(
				'PRICE' => $good['G_PRICE'],
				'CURRENCY_ID' => $good['G_CURRENCY'],
			));

	}

	$q = "select * from S_PHOTOS where P_CATEGORY = 1";
	$r = mysql_query($q, $old_db);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}
	$old_photos = array();
	while($row = mysql_fetch_assoc($r)) {
		$old_photos[$row['P_ID']] = $row;
	}

	//
	foreach($old_photos as $p_id=>$photo) {

		$g_id = $photo["P_INID"];
		$good_info = cat_get_good_by_gid($g_id);
		$good_id = $good_info['GOOD_ID'];
/*
        (
            [name] => 54.jpg
            [type] => image/jpeg
            [tmp_name] => /tmp/phpAfIiXz
            [error] => 0
            [size] => 69114
        )

*/
		$filename = $files_path . "/old_fotos/goods/" . $g_id . "/" . $p_id.".jpg";
		if (is_file($filename)) {
			$file_info = array(
					'name' => $p_id.".jpg",
					'type' => "image/jpeg",
					'tmp_name' => $filename,
					'error' => 0,
					'size' => filesize($filename)
				);
			cat_add_foto($good_id, $file_info);
		} else {
			echo "no file $filename <br>";
		}

	}

	balo3_controller_output(" ");

} while (false);



function cat_get_section_by_cid($c_id, $sc_id = '') {

	if ($sc_id != '') {
		$add = " and _SC_ID = $sc_id";
	}

	$q = "select
			SECTION_ID 
		from
			CATALOG_SECTIONS
		where
			_C_ID = $c_id
			$add";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}

	if ($row = mysql_fetch_assoc($r)) {
		return $row;
	}

	return "notfound";
}

function cat_get_good_by_gid($g_id) {

	$q = "select
			*
		from
			CATALOG_GOODS
		where
			_G_ID = $g_id
			$add";
	$r = balo3_db_query($q);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}

	if ($row = mysql_fetch_assoc($r)) {
		return $row;
	}

	return "notfound";
}

function cat_set_cid($section_id, $c_id, $sc_id, $dirname, $sort_value = '') {

	if ($sort_value != '') {
		$add = ", SORT_VALUE = $sort_value ";
	}

	$q = "update
			CATALOG_SECTIONS
		set
			`_C_ID` = '$c_id',
			`_SC_ID` = '$sc_id',
			`DIRNAME` = '$dirname'
			$add
		where
			SECTION_ID = $section_id";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}

	return "ok";
}

function cat_set_gid($good_id, $g_id) {

	$q = "update
			CATALOG_GOODS
		set
			`_G_ID` = '$g_id'
		where
			GOOD_ID = $good_id";
	//echo "<pre>$q</pre>";
	$r = balo3_db_query($q);
	if (!$r) {
		echo "<pre>$q</pre>";
		exit;
	}

	return "ok";
}



function cat_add_foto($good_id, $file_info) {


	// добавляем в базу данных
	$good_foto_id = catalog_db_add_foto($good_id, array());
	if ($good_foto_id === "error") {
		echo "db error ";
		echo $_FARCH_FOTO_ERRORS['DB_ERROR'];
		exit;
	}

	// работаем с файлами, генерим превьюхи
	list($status, $error, $tech_info) = farch_fotos_save_foto("good_" . $good_id, $good_foto_id, $file_info);
	if ($status === "error") {
		catalog_db_remove_foto($good_foto_id);
		echo "$error ";
		echo $_FARCH_FOTO_ERRORS[$error];
		exit;
	}

	// всё в порядке, сохраняем прочую информацию
	// far_db_update_foto_info($foto_id, $foto_title, $tech_info, $sort_value = "")
	// show_ar($tech_info);
	$status = catalog_db_modify_foto($good_foto_id, array("TECH_INFO"=>$tech_info));
	if ($status === "error") {
		echo "db error2 ";
		echo $_FARCH_FOTO_ERRORS['DB_ERROR2'];
		exit;
	}

}


?>