<?php

function phpmorphy_get_search_words($input = array()) {
	global $phpmorphy, $phpmorphy_clean_chars, $phpmorphy_search_word_min_strlen, $phpmorphy_search_stop_words, $phpmorphy_search_short_words_allowed;

	if (!is_array($input)) {
		$input = explode(" ", $input);
	}

	//var_dump($input);

	if (count($input) == 0) {
		return array();
	}

	$words = array();
	$result = array();
	foreach($input as $n=>$i) {
		$t = mb_strtoupper(trim($i, $phpmorphy_clean_chars));
		if ( in_array($t, $phpmorphy_search_stop_words) ) continue;
		if ( (mb_strlen($t) >= $phpmorphy_search_word_min_strlen) || in_array($t, $phpmorphy_search_short_words_allowed) ) {
			$words[] = $t;
		}
	}

	if (count($words) == 0) {
		return array();
	}

	$r = $phpmorphy->getAllForms($words);

	if($r == false) {
		return false;
	}

	//show_ar($r);

	foreach($r as $word=>$word_forms) {
		if (!is_array($word_forms)) {
			$result[$word] = array($word);
			continue;
		}
		foreach($word_forms as $form) {
			$result[$word][] = $form;
		}
	}

	//echo "done";

	return $result;

/*
array(2) {
  ["�����"]=>
  array(10) {
    [0]=>
    string(10) "�����"
    [1]=>
    string(10) "�����"
    [2]=>
    string(10) "�����"
    [3]=>
    string(10) "�����"
    [4]=>
    string(12) "������"
    [5]=>
    string(12) "������"
    [6]=>
    string(8) "����"
    [7]=>
    string(12) "������"
    [8]=>
    string(14) "�������"
    [9]=>
    string(12) "������"
  }
  ["������"]=>
  array(9) {
    [0]=>
    string(12) "������"
    [1]=>
    string(12) "������"
    [2]=>
    string(12) "������"
    [3]=>
    string(14) "�������"
    [4]=>
    string(12) "������"
    [5]=>
    string(10) "�����"
    [6]=>
    string(14) "�������"
    [7]=>
    string(16) "��������"
    [8]=>
    string(14) "�������"
  }
}
*/
}


?>