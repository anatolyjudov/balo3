<?php

	function widget_last_albums($args, $smarty) {
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

		if (isset($args['parent_album_id'])) {
			$parent_album_id = $args['parent_album_id'];
		} else {
			$parent_album_id = "";
		}
		$smarty->assign_by_ref("parent_album_id", $parent_album_id);

		if (isset($args['template'])) {
			$template = $templates_path . '/common/widgets/farch/' . $args['template'];
		} else {
			$template = $templates_path . "/farch/widget_default.tpl";
		}


		// информация об альбомах
		$albums_info = far_db_get_albums();
		if ($albums_info === 'error') {
			return "db error";
		}
		$smarty->assign_by_ref("albums_info", $albums_info);


		//show_ar($albums_info);

		return $smarty->fetch($template);

	}

?>