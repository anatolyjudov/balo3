<h1>Авторы лота <em>{$good_info.TITLE}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog_good.tpl"}
{include file="catalog/admin_catalog_section.tpl"}
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

<p style="margin-bottom: 21px;">Вы можете привязать к лоту несколько авторов.</p>

{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}
{if isset($msg)}<p class="msg">{$msg}</p>{/if}

<form method="POST" action="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/authors/" enctype="multipart/form-data">
	<input type="hidden" name="confirmed" value="1">

	<table class="ctrl" cellspacing=0 cellpadding=4>

		{if $authors_component}
			<tr class="striked">
			<td valign=top><b>Список авторов</b></td>
			
			{foreach from=$authors_list item=author}
			<tr class="{cycle values='odd,nodd'}">
				<td>
				<div style="display: block; font-size: 15px;">
				<input type="checkbox" name="author_choose_{$author.id}" {if isset($author_info[$author.id])}checked{/if} style="margin-right: 20px;">{$author.sirname} {$author.name} {$author.patronymic}
				</div>
			</td>
		</tr>
			{/foreach}

			</tr>
		{else}
		<h2>Компонент authors не подключен</h2>
		{/if}

		<tr>
		<td><input type="submit" value="Сохранить" style="font-size: 18px;"></td>
		</tr>

	</table>

</form>

</div>

