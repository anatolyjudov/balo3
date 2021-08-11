<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_balo3_widget($widget_params, &$smarty) {
	global $controllers_path;

	$controller_family = $widget_params["family"];
	$widget = $widget_params["widget"];

	unset($widget_params["family"]);
	unset($widget_params["widget"]);

	require_once("$controllers_path/".$controller_family."/widgets/".$widget.".php");

	$return_value = call_user_func("widget_$widget", $widget_params, $smarty);

	return $return_value;

}

?>
