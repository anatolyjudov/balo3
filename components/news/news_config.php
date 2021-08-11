<?php

// News Component
// Cofiguration File

$news_table_name = "NEWS_NEWS";
$news_categories_table_name = "NEWS_CATEGORIES";

// news per page
$news_per_page = 99;

// news per category in news module
$news_per_cat = 5;

// errors
$_news_errors = array(
	'values_not_set' => 'Ошибка: не установлены необходимые значения перменных',
	'error: existing uri entered' => 'Ошибка: введен уже существующий путь',
	'error: wrong parent uri' => 'Ошибка: введен неверный путь',
	'error: empty name' => 'Ошибка: пустое название',
	'error: id must be positive integer' => 'Ошибка: идентификатор записи должен быть натуральным числом',
	'error: category does not exist' => 'Ошибка: не существует такой категории',
	'error: no text' => 'Ошибка: отсутствует текст',
	'error: cat_id must be positive integer' => 'Ошибка: идентификатор категории новости должен быть натуральным числом',
	'error: you not have permission level for published' => 'Ошибка: у вас нет прав публиковать новости',
	'error' => 'Ошибка!'
);

?>