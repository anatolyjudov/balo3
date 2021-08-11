<h4><a href="{$base_path}/news/">Архив новостей</a></h4>

<div class="entrypage news_page clearfixright">
	<h1 class="entrypage_title">{$news_info.name}</h1>
	{*{if $news_info.news_image.FOTO_ID ne ""}
	<div class="entrypage_image news_image news_picture">
		<img src="{$farch_foto_params.folders.link}/{$news_info.news_image.ALBUM_ID}/{$farch_foto_params.previews.main.prefix}{$news_info.news_image.FOTO_ID}.{$news_info.news_image.TECH_INFO.extension}" alt="{$news_info.name|escape:'html'}">
	</div>
	{/if}*}
	<div class="entrypage_text">{if $news_info.text ne ""}{$news_info.text}{else}<p>{$news_info.short_text}</p>{/if}</div>
	<div class="entrypage_date"><em>{$news_info.posted|date_format:'%d.%m.%Y'}</em></div>
</div>

{*
<h3>{#lang_news_other_title_smi#}</h3>

<div class="news_section">
{foreach from=$news_list key=news_id item=news_info name=itemslistloop}
	<div class="news_item clearfix {if $news_info.news_image.FOTO_ID ne ""}item_with_image{/if}">
		
		<div class="item_title news_title"><a href="{$base_path}{$news_info.NODE_PATH}{$news_id}/">{$news_info.name}</a> <span>{if $news_info.visible == 0 }{#lang_news_closed#}{/if}</span></div>

		{if $news_info.news_image.FOTO_ID ne ""}
		<div class="item_image news_picture"><a href="{$base_path}{$news_info.NODE_PATH}{$news_id}/"><img src="{$farch_foto_params.folders.link}/{$news_info.news_image.ALBUM_ID}/{$farch_foto_params.previews.card.prefix}{$news_info.news_image.FOTO_ID}.{$news_info.news_image.TECH_INFO.extension}" alt="{$news_info.name|escape:'html'}"></a></div>
		{/if}
		
		<div class="item_text news_short_text">{$news_info.short_text}</div>
	</div>
{if $smarty.foreach.itemslistloop.iteration%3 == 0}<div class="clearing clearing_left"></div>{/if}
{/foreach}
</div>

*}

{if $moder_here}
{capture name="admin_place_links"}
<div class="admin_place">
<a href="{$base_path}/admin/news/edit/?id={$news_info.id_new}">{#lang_news_contextmenu_edit#}</a>
<a href="{$base_path}/admin/news/delete/?id={$news_info.id_new}">{#lang_news_contextmenu_delete#}</a>
{if $publisher_here}
{if $news_info.visible == 0 }<a href="{$base_path}/admin/news/published/?id={$news_info.id_new}">{#lang_news_contextmenu_publish#}</a>{else}<a href="{$base_path}/admin/news/published/?id={$news_info.id_new}">{#lang_news_contextmenu_close#}</a>{/if}
{/if}
<a href="{$base_path}/admin/news/new/">{#lang_news_contextmenu_add#}</a>
{if $page_meta_id ne ""}
<a href="{$base_path}/admin/meta/edit/?meta_id={$page_meta_id}">{#lang_news_contextmenu_editmeta#}</a>
{else}
<a href="{$base_path}/admin/meta/new/?uri={$base_path}{$news_info.NODE_PATH}{$news_info.id_new}/">{#lang_news_contextmenu_createmeta#}</a>
<span style="color: red; font-weight: bold;">!</span>
{/if}
</div>
{/capture}
{/if}