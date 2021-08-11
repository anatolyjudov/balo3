{include file="$templates_path/common/tinymce_init.tpl" tinymce_elements="text"}

<h1>{if $action eq "modify"}{#authors_admin_form_edit#}{elseif $action eq "add"}{#authors_admin_form_new#}{/if}</h1>

<div class="additional">
{include file="authors/authors_admin_additional.tpl"}
</div>

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

<div class="content">

<form action="{$base_path}/admin/authors/{$action}/" method="post" enctype="multipart/form-data">

	<table class="ctrl" style="width: 100%">
		<input type="hidden" name="confirmed" value="1">

		{if $action eq "modify"}
		<input type="hidden" name="id" value="{$author_info.id}">
		{/if}

		<tr class="striked">
		<td valign=top><b>{#authors_admin_sirname#}</b></td>
		<td>
		<input type="text" name="sirname" value="{$author_info.sirname}" style="width: 300px; font-size: 140%;">
		</td>
		</tr>

		<tr class="striked">
		<td valign=top><b>{#authors_admin_name#}</b></td>
		<td>
		<input type="text" name="name" value="{$author_info.name}" style="width: 300px; font-size: 140%;">
		</td>
		</tr>

		<tr class="striked">
		<td valign=top><b>{#authors_admin_patronymic#}</b></td>
		<td>
		<input type="text" name="patronymic" value="{$author_info.patronymic}" style="width: 300px; font-size: 140%;">
		</td>
		</tr>

		<tr class="striked">
		<td valign=top><b>{#authors_admin_short_text#}</b></td>
		<td>
		<textarea name="short_text" style="width: 565px; height: 40px;">{$author_info.short_text}</textarea>
		<p>{#authors_admin_short_text_note#}</p>
		</td>
		</tr>

		<tr>
		<td colspan="2">
			<b>{#authors_admin_description#}</b>
		</td>
		</tr>

		<tr>
		<td colspan="2" style="padding-top: 0;">
			<textarea name="description" id="text" style="width: 90%; height: 500px;">{$author_info.description}</textarea>
			<p style="margin-top: 10px;">{#authors_admin_description_note#}</p>
		</td>
		</tr>

		<tr>
		<td></td>
		<td><input type="submit" value="{if $action eq "modify"}{#authors_admin_form_edit_submit#}{elseif $action eq "add"}{#authors_admin_form_new_submit#}{/if}" style="font-size: 18px;"></td>
		</tr>
	</table>

</form>

</div>