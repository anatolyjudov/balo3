<h1>{#lang_news_list_journal_title#}</h1>

<div class="entries_list journal_list">
{foreach from=$news_list key=news_id item=news_info name=itemslistloop}
	
	{if $news_info.name ne "" || $news_info.short_text ne ""}

		<div class="entry journal_entry clearfix {if $news_info.news_image.FOTO_ID ne ""}item_with_image{/if}">

			{if $news_info.news_image.FOTO_ID ne ""}
			<div class="entry_image"><a href="{$base_path}{$news_info.NODE_PATH}{$news_id}/"><img src="{$farch_foto_params.folders.link}/{$news_info.news_image.ALBUM_ID}/{$farch_foto_params.previews.card.prefix}{$news_info.news_image.FOTO_ID}.{$news_info.news_image.TECH_INFO.extension}" alt="{$news_info.name|escape:'html'}"></a></div>
			{/if}

			{if $news_info.name ne ""}
			<div class="entry_title news_title"><h2><a href="{$base_path}{$news_info.NODE_PATH}{$news_id}/">{$news_info.name}</a></h2> <span>{if $news_info.visible == 0 }{#lang_news_closed#}{/if}</span></div>
			{/if}

			{if $news_info.short_text ne ""}
			<div class="entry_text news_short_text">{$news_info.short_text}</div>
			{/if}
			
			<div class="entry_date">
			<em>{$news_info.posted|date_format:'%d.%m.%Y'}</em>
			</div>
			
		</div>
		{if $smarty.foreach.itemslistloop.iteration%3 == 0}<div class="clearing clearing_left"></div>{/if}

	{/if}
{/foreach}
</div>



{if ($p > 0) || ($news_per_page eq $news_count)}
<p>
	{if $p > 0}<a class="button" href="./?p={$p-1}">&lt;&lt;</a> &nbsp;&nbsp;&nbsp;&nbsp;{/if}
	{if $news_per_page eq $news_count} {if $p > 0}&nbsp;&nbsp;&nbsp;&nbsp;{/if}<a class="button"  href="./?p={$p+1}">&gt;&gt;</a>{/if}
</p>
{/if}

{if $moder_here || $publisher_here }
{capture name="admin_place_links"}
<div class="admin_place">
	<a href="{$base_path}/admin/news/new/?cid={$c}">{#lang_news_contextmenu_add#}</a>
</div>
{/capture}
{/if}