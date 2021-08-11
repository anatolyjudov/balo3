{if count($news_list) > 0}
<div class="splash_row_2_1">

	<section class="news_list">
	{foreach from=$news_list item=news key=id_new name=modnewslist}
	{if $smarty.foreach.modnewslist.iteration <= 2}
	<section class="entry">
		<div class="article_card">
			<h2 class="title"><a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.name|default:''}</a></h2>
			<div class="short_text"><a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.short_text}</a></div>
			<div class="date">{$news.posted|date_format:'%d.%m.%Y'}</div>
		</div>
	</section>
	{/if}
	{/foreach}

	<div class="more_link"><a href="{$base_path}{$news.NODE_PATH}">{#lang_news_archive_link#}</a></div>
	</section>

	<section class="subscribe_block">
	{*
		<img class="surprise_link" src="{$pics_path}/surprises/surprise_ny2_link.png" style="display: block; margin: .3rem auto 3rem; cursor: pointer;" onclick="common_surprise.show();" />
	*}
	</section>

</div>
{/if}