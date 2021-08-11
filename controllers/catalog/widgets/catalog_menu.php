<?php

function widget_catalog_menu($widget_params, $smarty) {
	global $components_path, $templates_path, $entrypoint_path;
	global $sections_info, $section_counts;


	//show_ar($sections_info);
	//show_ar($section_counts);



	return $smarty->fetch($templates_path . '/catalog/widgets/catalog_menu.tpl');

}

?>