<?php

require_once("$components_path/popups/popups_init.php");

do {

	$popups_list = popups_get_all();
	$popup_current = popups_get_current();

	// сохраним для шаблона инфу что были произведены изменения
	$smarty->assign("popups_list", $popups_list);
	$smarty->assign("popup_current", $popup_current);

	// выдаем шаблон
	balo3_controller_output($smarty->fetch("$templates_path/popups/manage.tpl"));

} while (false);

?>