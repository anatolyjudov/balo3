<?php

	function widget_show_album_by_id($args, $smarty) {
		global $components_path, $templates_path, $entrypoint_path;
		global $farch_fotos_table, $farch_albums_table, $farch_tags_table, $farch_r_fotos_tags_table, $farch_rel_posts_table, $farch_rel_authors_table, $farch_rel_fotos_table;

		include_once("$entrypoint_path/components/farch/farch_config.php");
		include_once("$entrypoint_path/components/farch/farch_db.php");

		multilang_load_smarty_config("farch");

		if (isset($args['limit'])) {
			$limit = $args['limit'];
		} else {
			$limit = "";
		}

		if (isset($args['album_id'])) {
			$album_id = $args['album_id'];
		} else {
			$album_id = "";
		}
		$smarty->assign_by_ref("album_id", $album_id);

		if (isset($args['template'])) {
			$template = $templates_path . '/common/widgets/farch/' . $args['template'];
		} else {
			$template = $templates_path . "/farch/widget_default.tpl";
		}

		//show_ar($args);
		// информация об альбомах
		/*$albums_info = far_db_get_albums();
		if ($albums_info === 'error') {
			return "db error";
		}
		$smarty->assign_by_ref("albums_info", $albums_info);*/


		//show_ar($albums_info);

		//достанем фоточки
		$skip = 0;
		$limit = 0;
		$fototag_filter = array();
		$albums_filter = array($album_id);
		$fotos_list = far_db_get_fotos_list($skip, $limit, $fototag_filter, $albums_filter);
		if ($fotos_list === 'error') {
			out_main($_ERRORS['DB_ERROR']);
		}
		$smarty->assign_by_ref("fotos_list", $fotos_list);

		//show_ar($fotos_list);

		return $smarty->fetch($template);

	}

?>