<h1>{#lang_news_admin_manage_title#}</h1>

<div class="additional">
<img src="{$pics_path}/add.png" alt="" style="position: relative; top: 3px; margin-right: 3px;"><a href="{$base_path}/admin/news/new/">{#lang_news_admin_manage_links_add#}</a>
</div>

<div class="content">

{if ($p > 0) || ($news_per_page eq $news_count)}<p>
{if $p > 0}<a href="./?p={$p-1}{if $c}&c={$c}{/if}">&lt;&lt;</a> {/if}
{if $news_per_page eq $news_count} <a href="./?p={$p+1}{if $c}&c={$c}{/if}">&gt;&gt;</a>{/if}
</p>
{/if}

<table class="data">

<tr class="head">
<td>{#lang_news_admin_manage_head_id#}</td>
<td>{#lang_news_admin_manage_head_title#}</td>
<td>{#lang_news_admin_manage_head_date#}</td>
<td>{#lang_news_admin_manage_head_state#}</td>
<td></td>
<td></td>
</tr>

{foreach from=$news item=news_one key=news_id}
	<tr class="{cycle values='hl,nhl'}">
	<td>{$news_id}</td>
	<td><a href="{$base_path}{$news_one.NODE_PATH}{$news_id}/">{$news_one.name}</a></td>
	<td>{$news_one.posted|date_format:'%d.%m.%Y'}</td>
	<td>{if $news_one.visible == 0 }{#lang_news_closed#}{else}{#lang_news_published#}{/if}</td>
	<td>
		{if $publisher_here}
		{if $news_one.visible == 0 } <a href="{$base_path}/admin/news/published/?id={$news_id}">{#lang_news_admin_manage_publish#}</a>{else} <a href="{$base_path}/admin/news/published/?id={$news_id}">{#lang_news_admin_manage_close#}</a>{/if}
		{/if}
	</td>
	<td>
	<a href="{$base_path}/admin/news/edit/?id={$news_id}"><img src="{$pics_path}/edit.png" alt="{#lang_admin_edit_alt_text#}"></a> <a href="{$base_path}/admin/news/delete/?id={$news_id}"><img src="{$pics_path}/delete.png" alt="{#lang_admin_remove_alt_text#}"></a>
	</td>
	</tr>
{/foreach}

</table>

</div>