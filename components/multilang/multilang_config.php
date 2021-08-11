<?php

// включить мультиязычность
// флаг для других компонентов
$ml_multilang_mode = TRUE;

// названия таблиц
$ml_strings_table = "ML_STRINGS";
$ml_texts_table = "ML_TEXTS";

// конфиг языков
$ml_langs_list = array(
	0 => array(
			'domains' => array('domain.ru'),
			'template_folder'=>"/templates",
			'templates_c_folder'=>"$smarty_tmp_path/templates_c/",
			'name_switcher'=>'ru',
			'flag_image'=>$pics_path . "/flags2/ru.png"
	),
	1 => array(
			'domains' => array('en.domain.ru'),
			'template_folder'=>"/templates",
			'templates_c_folder'=>"$smarty_tmp_path/templates_c/",
			'name_switcher'=>'en',
			'flag_image'=>$pics_path . "/flags2/en.png"
	)
);

$ml_lang_cookie_name = "ml_lang";
$ml_cookie_lifetime = 3600*24*7;

$ml_lang_default = 0;

// путь к языковым конфигам
$lang_path = $entrypoint_path . "/langs";

?>