<?php

function smarty_modifier_balo3_parse_widget_calls($input_string) {
	global $controllers_path;
	global $smarty;

	$output_string = $input_string;

	if (preg_match_all("/\[widget\s+family=(\w+)\s+widget=(\w+)\s*\]/", $input_string, $matches, PREG_SET_ORDER)) {

		/*
		Array
		(
			[0] => Array
				(
					[0] => [widget family=freshdesk widget=overall]
					[1] => freshdesk
					[2] => overall
				)

		)
		*/

		foreach($matches as $match) {
			$controller_family = $match[1];
			$widget = $match[2];
			require_once("$controllers_path/".$controller_family."/widgets/".$widget.".php");
			$widget_return_value = call_user_func("widget_$widget", $widget_params, $smarty);
			$output_string = str_replace($match[0], $widget_return_value, $output_string);
		}

	}

/*
	$controller_family = $widget_params["family"];
	$widget = $widget_params["widget"];

	unset($widget_params["family"]);
	unset($widget_params["widget"]); 

	require_once("$controllers_path/".$controller_family."/widgets/".$widget.".php");

	$return_value = call_user_func("widget_$widget", $widget_params, $smarty);
*/

	return $output_string;

}

?>
