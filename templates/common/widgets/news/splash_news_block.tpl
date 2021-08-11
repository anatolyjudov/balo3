<h3>{#lang_news_module_title#}</h3>

{foreach from=$news_list item=news key=id_new name=modnewslist}

<div class="new clearfix">
	<span class="data">{$news.posted|date_format:'%d.%m.%Y'}</span>
	<p><a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{if $news.name ne ""}<b>{$news.name}</b>{/if} {$news.short_text}</a></p>
</div>

{/foreach}

<a class="allNews" href="{$base_path}{$news.NODE_PATH}">все новости</a>

{*
<div class="new clearfix">
	<span class="data">05.09.2013</span>
	<p><a href="">Версия биллинга &laquo;Гидра&raquo; 3.3.6</a></p>
</div>
<div class="new clearfix">
	<span class="data">27.08.2013</span>
	<p><a href="">Участвуем в&nbsp;V съезде кабельных операторов и&nbsp;интернет-провайдеров Дальнего Востока</a></p>
</div>
<div class="new clearfix">
	<span class="data">05.09.2013</span>
	<p><a href="">Выступление на&nbsp;КРОС&nbsp;&mdash; 2013</a></p>
</div>
<a class="allNews" href="">все новости</a>
*}

{*

Array
(
    [1] => Array
        (
            [id_new] => 1
            [category] => 1
            [catname] => News
            [posted] => 2014-03-04 00:00:00
            [visible] => 1
            [NODE_PATH] => /news/
            [picture] => 
            [name] => Тестовая новость
            [short_text] => Краткий текст тестовой новости
            [text] => Большой, полный текст тестовой новости.
В несколько строк, и всё такое.
        )

    [4] => Array
        (
            [id_new] => 4
            [category] => 1
            [catname] => News
            [posted] => 2014-03-04 00:00:00
            [visible] => 1
            [NODE_PATH] => /news/
            [picture] => 
            [name] => Новая новость
            [short_text] => Коротко
            [text] => Полностью
        )

)

*}