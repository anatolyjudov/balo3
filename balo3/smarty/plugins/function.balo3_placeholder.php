<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */

function smarty_function_balo3_placeholder($params, &$smarty) {
	global $balo3_controllers_output;

	if (!isset($params["name"]) || ($params["name"] == "")) {
		return "placeholder name not defined";
	}
	$placeholder_name = $params["name"];

	if (!isset($balo3_controllers_output[$placeholder_name])) {
		return "controller probably not worked at $placeholder_name";
	}

	return $balo3_controllers_output[$placeholder_name];

}

?>
