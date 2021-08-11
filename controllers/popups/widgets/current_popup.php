<?php

function widget_current_popup($widget_params, $smarty) {
	global $components_path, $templates_path, $entrypoint_path;
	global $sections_info, $section_counts;

	$popup_current = popups_get_current();
	if ($popup_current === 'error' || $popup_current === 'notfound') return "";

	$popup_tpl_file = $templates_path . '/common/widgets/popups/' . $popup_current['TEMPLATE'] . ".tpl";
	if (!is_file($popup_tpl_file)) return "";

	return $smarty->fetch($popup_tpl_file);

}

?>