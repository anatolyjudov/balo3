<div class="splash_row_2_1">

{foreach from=$news_list item=news key=id_new name=modjournallist}

{if $smarty.foreach.modjournallist.iteration == 1}
	<section class="last_entry">
		<div class="article_card">
			<h2 class="title"><a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.name|default:''}</a></h2>
			<div class="short_text"><a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.short_text}</a></div>
			<div class="date">{$news.posted|date_format:'%d.%m.%Y'}</div>
		</div>
	</section>
{else}
	{if $smarty.foreach.modjournallist.iteration == 2}
	<section class="other_entries">
	{/if}
	<div class="article_link">
		<div class="title"><a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.name|default:''}</a></div>
		<div class="date"><a href="{$base_path}{$news.NODE_PATH}{$news.id_new}/">{$news.posted|date_format:'%d.%m.%Y'}</a></div>
	</div>
	{if $smarty.foreach.modjournallist.last}
	<div class="more_link"><a href="{$base_path}{$news.NODE_PATH}">{#lang_news_journal_other_title#}</a></div>
	</section>
	{/if}
{/if}

{/foreach}

</div>