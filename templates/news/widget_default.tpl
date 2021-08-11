<h2>{#lang_news_list_title#}</h2>
<section class="news_section">

{foreach from=$news_list item=news key=id_new name=modnewslist}

<div class="news_item clearfix">
	{if $news.name ne ""}
	<div class="news_title">
		<a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.name}</a>
	</div>
	{/if}
	<div class="news_short_text">
		<a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.short_text}</a>
	</div>
	<div class="news_data">
		<em>{$news.posted|date_format:'%d.%m.%Y'}</em>
	</div>
</div>

{/foreach}

<a href="{$base_path}{$news.NODE_PATH}">{#lang_news_widget_link_all#}</a> &rarr;

</section>

<div class="aside_divider"></div>