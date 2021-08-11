<h1>{#lang_news_admin_delete_title#}</h1>

<form action="./remove/" method="post">
	<input type="hidden" name="confirmed" value="1">
	<input type="hidden" name="id" value="{$id}">
	<p>{#lang_news_admin_delete_confirm#}<br>
	<br>
	<a href="../">{#lang_admin_cancel_remove#}</a><br>
	<br>
	<input type="submit" class="knopke" value="{#lang_admin_confirm_remove#}">
	</p>
</form>