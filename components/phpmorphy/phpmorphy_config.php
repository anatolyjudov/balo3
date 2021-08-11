<?php

$phpmorphy_path = $components_path."/phpmorphy/phpmorphy-0.3.7";

// каталог со словарями
$phpmorphy_dicts_path = $phpmorphy_path . '/dicts';

// Укажите, для какого языка будем использовать словарь.
// Язык указывается как ISO3166 код страны и ISO639 код языка, 
// разделенные символом подчеркивания (ru_RU, uk_UA, en_EN, de_DE и т.п.)
$phpmorphy_lang = 'ru_RU';

$phpmorphy_clean_chars = " \t\n\r\0\x0B,.!?-=()*%$#@'\"\\\/";

$phpmorphy_search_word_min_strlen = 3;
$phpmorphy_search_short_words_allowed = array("НЮ");
$phpmorphy_search_stop_words = array("ДЛЯ");
?>