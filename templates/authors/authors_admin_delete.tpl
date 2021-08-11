<h1>{#authors_admin_delete_title#}</h1>

<div class="additional">
{include file="authors/authors_admin_additional.tpl"}
</div>

<div class="content">

{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}

{if $author_info.id ne ""}

	<h4>{#authors_admin_delete_confirm_1#}</h4>

	<table class="ctrl sections_manage" style="width: 100%;">
	
		<tr class="head">
		<td style="width: 50px;">{#authors_admin_id#}</td>
		<td style="width: 150px;">{#authors_admin_sirname#}</td>
		<td style="width: 150px;">{#authors_admin_name#}</td>
		<td style="width: 150px;">{#authors_admin_patronymic#}</td>
		<td style="width: 400px;">{#authors_admin_short_text#}</td>
		</tr>

		<tr class="nodd">
		<td>{$author_info.id}</td>
		<td>{$author_info.sirname}</td>
		<td>{$author_info.name}</td>
		<td>{$author_info.patronymic}</td>
		<td>{$author_info.short_text}</td>
		</tr>

	</table>

	<h4>{#authors_admin_delete_confirm_2#}</h4>

	<form action="{$base_path}/admin/authors/remove/" method="POST">
	<input type="hidden" name="confirmed" value="on">
	<input type="hidden" name="author_id" value="{$author_info.id}">
	<p><input type="submit" value="{#authors_admin_remove#}"> <input type="button" value="{#authors_admin_cancel#}" onClick="document.location='{$base_path}/admin/authors/';">
	</form>

{/if}

</div>