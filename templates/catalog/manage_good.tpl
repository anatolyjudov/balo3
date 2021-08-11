<h1>Лот <em>{$good_info.TITLE}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog_good.tpl"}
{include file="catalog/admin_catalog_section.tpl"}
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}
{if isset($msg)}<p class="msg">{$msg}</p>{/if}

<form method="POST" action="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/" enctype="multipart/form-data">

	<table class="ctrl" cellspacing=0 cellpadding=4>

		<tr class="striked">
		<td valign=top><b>Название лота</b></td>
		<td>
		<input type="text" name="title" class="multilang" value="{$good_info.TITLE|escape:'html'}" style="width: 450px;">
		</td>
		</tr>

		<tr class="striked">
		<td valign=top><b>Раздел</b></td>
		<td>
		<select name="section_id">
		{*<option value="0">{#lang_catalog_admin_parent_section_none#}</option>*}
		{foreach from=$sections_info.plain item=parent_section_id}
		<option value="{$parent_section_id}" {if $good_info.SECTION_ID == $parent_section_id}selected{/if}>
		{section name=marginlevel loop=$sections_info.list[$parent_section_id].TREE_LEVEL start=0 step=1}&mdash;{/section}</span> 
		{if $sections_info.list[$parent_section_id].SECTION_NAME ne ""}{$sections_info.list[$parent_section_id].SECTION_NAME}{else}({$parent_section_id}){/if}
		</option>
		{/foreach}
		</select>

		{if count($good_sections_list) > 0}
		<div style="display: block; margin-top: 10px; font-size: 85%;"><a href="./sections/">Дополнительно</a>: 
		{foreach from=$good_sections_list item=r_good_section_info key=section_id name=goodsectionsloop}
		{$sections_info.list[$section_id].SECTION_NAME}{if !$smarty.foreach.goodsectionsloop.last}, {else}.{/if}
		{/foreach}
		</div>
		{/if}

		</td>
		</tr>

	{*
		{if $authors_component}
			<tr class="striked">
			<td valign=top><b>Автор</b></td>
			<td>
			
			{foreach from=$authors_list item=author}
				<div style="display: block;">
				<input type="checkbox" name="author_choose_{$author.id}" {if isset($author_info[$author.id])}checked{/if}>{$author.sirname} {$author.name} {$author.patronymic}
				</div>
			{/foreach}

			</td>
			</tr>
		{/if}
	*}

		<tr class="striked">
		<td valign=top><b>Опубликован на сайте</b></td>
		<td>
		<input type="checkbox" name="published" {if $good_info.PUBLISHED == 1}checked{/if}>
		</td>
		</tr>

		<tr class="striked">
		<td valign=top><b>Лот продан</b></td>
		<td>
			<select name="sold">
				<option value="">Нет</option>
				<option value="1" {if $good_info.SOLD == 1}selected{/if}>Продан</option>
				<option value="2" {if $good_info.SOLD == 2}selected{/if}>Зарезервирован</option>
			</select>
		</td>
		</tr>

		<tr class="striked">
		<td valign=top><b>Краткое описание</b><br><small>Будет отображаться в списках под фото.</small></td>
		<td>
		<textarea class="multilang" name="short_text" style="width: 450px; height: 50px;">{$good_info.SHORT_TEXT}</textarea>
		</td>
		</tr>

		<tr>
		<td colspan="2"><b>Описание лота</b><br><small>Будет отображаться только на странице лота. Если оставить пустым, то сайт отобразит вместо него краткое описание.</small></td>
		</tr>

		<tr>
		<td colspan="2" style="padding-top: 0;"><textarea class="multilang" name="description" style="width: 700px; height: 200px;">{$good_info.DESCRIPTION}</textarea></td>
		</tr>


		<tr class="striked">
		<td valign=top><b>Тэги</b><br><small>Текстовые метки, через запятую</small></td>
		<td>
		<textarea name="tags" style="width: 450px; height: 50px;">{if isset($new_tags_list)}{foreach from=$new_tags_list key=tag_id item=tag name=tagloop}{$tag}{if !$smarty.foreach.tagloop.last}, {/if}{/foreach}{else}{foreach from=$good_tags_list key=tag_id item=tag name=tagloop}{$tag}{if !$smarty.foreach.tagloop.last}, {/if}{/foreach}{/if}</textarea>
		</td>
		</tr>


		<tr>
		<td></td>
		<td><input type="submit" value="Изменить" style="font-size: 18px;"></td>
		</tr>

	</table>

</form>

</div>

