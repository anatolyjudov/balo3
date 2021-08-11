<?php

require_once("$entrypoint_path/components/news/news_init.php");

do {

	$page = news_sa($_GET['p']);
	list($cat, $tpl) = explode(",", $controller_data['CONTROLLER_ARGS']);
	$cat = trim($cat);
	if(isset($tpl) && ($tpl != '')) {
		$special_template = trim($tpl);
	} else {
		$special_template = false;
	}
	//echo $cat . " " . $tpl;
	//$cat = $controller_data['CONTROLLER_ARGS'];

	$moder_here = false;
	if(users_db_check_rights(users_get_user_id(), 'MANAGE_NEWS', $node_info['current']['M_PATH_' . $node_info['current']['level']]))
	{
		$moder_here = true;
	}

	$publisher_here = false;
	//это самый крутой модер новостей, главный редактор
	if(users_db_check_rights(users_get_user_id(), 'NEWS_PUBLISHER', $node_info['current']['M_PATH_' . $node_info['current']['level']]))
	{
		$moder_here = true;
		$publisher_here = true;
	}

	$smarty->assign_by_ref("publisher_here", $publisher_here);
	$smarty->assign_by_ref("moder_here", $moder_here);
	$smarty->assign_by_ref("news_list", $_news);
	$smarty->assign_by_ref("news_info", $_news_one);
	$smarty->assign_by_ref("news_per_page", $news_per_page);
	$smarty->assign_by_ref("p", $page);

	$_cat = news_db_get_cats($cat);
	$smarty->assign_by_ref("c", $cat);
	$smarty->assign_by_ref("cat", $_cat[0]);

	// список новостей
	$_news = news_db_get_news($page, $cat, $publisher_here || $moder_here );
	// show_ar($_news);
	if ($_news === 'error') {
		balo3_error("db error", true);
		exit;
	}

	if ( $farch_component && (count($_news) > 0) ) {

		$fotos_ids = array();
		foreach($_news as $news_id => $news_one) {
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
						$_news[$tmp_news_id]['news_image'] = $foto_info;
					}
				}
			}

		}

	}
/*
	show_ar($balo3_request_info);
	show_ar($balo3_node_info);
*/
	// нужно ли показывать одну конкретную новость?
	$news_one_id = $balo3_request_info['path'][count($balo3_request_info['path']) - 1];
	if (is_numeric($news_one_id)) {

		// одна новость
		$_news_one = news_db_get_one($news_one_id, $publisher_here || $moder_here, true);	//true for certain lang 
		if ( $news_farch_component && isset($_news_one['news_image_foto_id']) && ($_news_one['news_image_foto_id'] != '') ) {
			$_news_one['news_image'] = far_db_get_foto_info($_news_one['news_image_foto_id']);
			if ($_news_one['news_image'] == 'error') {
				balo3_error("db error", true);
				exit;
			}
			if ($_news_one['news_image'] == 'notfound') {
				$_news_one['news_image'] = array();
			}
		}
		unset($_news[$_news_one['id_new']]);
		//show_ar($_news);
		if ($special_template !== false) {
			balo3_controller_output($smarty->fetch("$templates_path/news/$special_template"));
			break;
		} else {
			balo3_controller_output($smarty->fetch("$templates_path/news/news_one.tpl"));
			break;
		}

	} else {

		//show_ar($_news);
		$smarty->assign("news_count", count($_news));

		if ($special_template !== false) {
			balo3_controller_output($smarty->fetch("$templates_path/news/$special_template"));
			break;
		} else {
			balo3_controller_output($smarty->fetch("$templates_path/news/news_list.tpl"));
			break;
		}

	}


} while (false);

?>