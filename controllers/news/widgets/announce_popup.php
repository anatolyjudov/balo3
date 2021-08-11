<?php

	function widget_announce_popup($args, $smarty) {
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

		$news_list = news_db_get_last_news(1, $news_cat, false);
		if ("error" === $news_list) {
			return "db error";
		}
		$smarty->assign_by_ref("news_list", $news_list);

		return $smarty->fetch($template);

	}

?>