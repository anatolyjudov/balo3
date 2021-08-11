<?php

function smarty_modifier_balo3_parse_settings_values($input_string) {
	global $controllers_path;
	global $smarty;

	$output_string = $input_string;

	if (preg_match_all("/\[setting\s+name=(\w+)\s*\]/", $input_string, $matches, PREG_SET_ORDER)) {

		/*
		Array
		(
			[0] => Array
				(
					[0] => [setting name=blabla]
					[1] => blabla
				)

		)
		*/

		foreach($matches as $match) {
			$setting_name = $match[1];
			global $settings_list;
			if (isset($settings_list[$setting_name])) {
				$output_string = str_replace($match[0], $settings_list[$setting_name], $output_string);
			}
		}

	}

	return $output_string;

}

?>
