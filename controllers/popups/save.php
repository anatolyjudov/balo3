<?php

require_once("$components_path/popups/popups_init.php");

do {

	foreach (array_keys($_REQUEST) as $k) {
		if (!preg_match("/^priority_(\d+)$/", $k, $matches)) continue;
		$id = $matches[1];
		$priority = $_REQUEST[$k];
		if ( isset($_REQUEST["is_active_" . $id]) && $_REQUEST["is_active_" . $id] === 'on') {
			$is_hidden = 0;
		} else {
			$is_hidden = 1;
		}
		$res = popups_set($id, $priority, $is_hidden);
	}

	// снова достанем все попапы
	$popups_list = popups_get_all();
	$popup_current = popups_get_current();

	// сохраним для шаблона инфу что были произведены изменения
	$smarty->assign("popups_list", $popups_list);
	$smarty->assign("popup_current", $popup_current);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/popups/manage.tpl"));

} while (false);

?>