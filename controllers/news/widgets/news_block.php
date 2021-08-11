<?php

	function widget_news_block($args, $smarty) {
		global $components_path, $templates_path, $entrypoint_path;
		global $news_table_name, $news_categories_table_name;
		global $farch_component;

		include_once("$entrypoint_path/components/news/news_config.php");
		include_once("$entrypoint_path/components/news/news_db.php");

		multilang_load_smarty_config("news");

		if (isset($args['limit'])) {
			$limit = $args['limit'];
		} else {
			$limit = "";
		}

		if (isset($args['news_cat'])) {
			$news_cat = $args['news_cat'];
		} else {
			$news_cat = "";
		}

		if (isset($args['template'])) {
			$template = $templates_path . '/common/widgets/news/' . $args['template'];
		} else {
			$template = $templates_path . "/news/widget_default.tpl";
		}

		if(users_db_check_rights(users_get_user_id(), 'NEWS_PUBLISHER', $node_info['current']['M_PATH_' . $node_info['current']['level']]))
		{
			$show_all = true;
		}

		// вызов функции
		$news_list = news_db_get_news_widget($limit, $news_cat, $show_all);
		//show_ar($news_list);
		if ("error" === $news_list) {
			return "db error";
		}
		$smarty->assign_by_ref("news_list", $news_list);

		if ( $farch_component && (count($news_list) > 0) ) {

			$fotos_ids = array();
			foreach($news_list as $news_id => $news_one) {
				if (isset($news_one['news_image_foto_id']) && ($news_one['news_image_foto_id'] != '')) {
					$fotos_ids[$news_one['news_image_foto_id']][] = $news_id;
				}
			}

			if (count($fotos_ids) > 0) {

				$fotos_list = far_db_get_fotos_info(array_keys($fotos_ids));
				if ($fotos_list === 'error') {
					return $_ERRORS['DB_ERROR'];
				}

				foreach($fotos_list as $foto_id => $foto_info) {
					if (isset($fotos_ids[$foto_id])) {
						foreach($fotos_ids[$foto_id] as $tmp_news_id) {
							$news_list[$tmp_news_id]['news_image'] = $foto_info;
						}
					}
				}

			}

		}

		//show_ar($news_list);

		return $smarty->fetch($template);

	}

?>