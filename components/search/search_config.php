<?php

$search_areas = array (
'htmls' => array (
		name => $_SEARCH_TEXTS['SEARCH_HTML_NAME'],
		name_p => $_SEARCH_TEXTS['SEARCH_HTML_NAME_P'],
		name_v => $_SEARCH_TEXTS['SEARCH_HTML_NAME_V'],
		name_title => $_SEARCH_TEXTS['SEARCH_HTML_TITLE'],
		search_function => "search_db_htmls_multilang",
		count_function => "search_db_count_htmls_multilang",
		restricted_path => array('/admin/')
),
/*
'news' => array (
		name => $_LANG_STRINGS['SEARCH_NEWS_NAME'],
		name_p => $_LANG_STRINGS['SEARCH_NEWS_NAME_P'],
		name_v => $_LANG_STRINGS['SEARCH_NEWS_NAME_V'],
		name_title => $_LANG_STRINGS['SEARCH_NEWS_TITLE'],
		search_function => "db_search_news_multilang",
		count_function => "db_search_count_news_multilang",
		restricted_path => array('/admin/')
)
*/
);

// эти параметры сделаны большими, чтобы не было никакой постраничности
$max_area_results = 9999;
$results_on_page = 9999;

?>