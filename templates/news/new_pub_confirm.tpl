<h1>{if $news.visible == 0 }{#lang_news_admin_publish_title#}{else}{#lang_news_admin_close_title#}{/if} </h1>

<div class="content">
	<p><strong>{$news.name}</strong> | <span class="newsdate">{$news.posted|date_format:'%d.%m.%Y %H:%M'}</span> | <span>{if $news.visible == 0 }{#lang_news_closed#}{else}{#lang_news_published#}{/if}</span></p>
<p>{$news.short_text|regex_replace:"~<morelink>([\\s\\S]*)</morelink>~":"<a href='$base_path$news[M_PATH]$news[id_new]/'>\\1</a>"}</p>
<p>{$news.text}</p>

<script language=javascript>
function doback() {literal} {
   document.location = '../'; }
{/literal}
</script>
<p>{if $news.visible == 0 } {#lang_news_admin_publish_confirm#} {else} {#lang_news_admin_close_confirm#} {/if}
<br>
<form action="./public/" method="post">
	<input type="hidden" name="confirmed" value="1">
	<input type="hidden" name="id" value="{$news.id_new}">
	<input type="submit" class="knopke" value="{#lang_news_admin_yes#}">
	<input type="button" class="knopke" onClick="javascript: history.go(-1);" value="{#lang_news_admin_no#}">
</form>
</p>
</div>