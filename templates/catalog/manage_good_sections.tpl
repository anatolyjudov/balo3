<h1>Дополнительные разделы товара <em>{$good_info.TITLE}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog_good.tpl"}
{include file="catalog/admin_catalog_section.tpl"}
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

<p style="margin-bottom: 21px;">Товар может отображаться не только в своем основном разделе, но и в других разделах по вашему выбору.</p>

{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}
{if isset($msg)}<p class="msg">{$msg}</p>{/if}


<form action="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/sections/" method="POST">
<input type="hidden" name="send" value="1">
<table class="ctrl" >


<tr class="head">
<td></td>
<td>Раздел</td>
<td>Удалить привязку</td>
</tr>

<tr class="{cycle values='odd,nodd'}">
<td></td>
<td>
	{section name=marginlevel loop=$sections_info.list[$good_info.SECTION_ID].TREE_LEVEL start=0 step=1}&mdash;{/section}
	{$sections_info.list[$good_info.SECTION_ID].SECTION_NAME}
</td>
<td></td>
</tr>

{if count($good_sections_list) > 0}
{foreach from=$good_sections_list item=r_good_section_info key=section_id}
<tr class="{cycle values='odd,nodd'}">
<td>{*{$price_id}*}<input type="hidden" name="{$section_id}" value="{$section_id}"></td>
<td>
	{section name=marginlevel loop=$sections_info.list[$section_id].TREE_LEVEL start=0 step=1}&mdash;{/section}
	{$sections_info.list[$section_id].SECTION_NAME}
</td>
<td><input type="checkbox" name="del_{$section_id}"></td>
</tr>
{/foreach}
{/if}

<tr style="border-width: 1px 0 0; border-style: solid; border-color: #ccc;">
<td><span style="font-weight: bold;">+</span></td>
<td>
	<select name="new_section_id">
	<option value="0"></option>
	{foreach from=$sections_info.plain item=new_section_id}
		<option value="{$new_section_id}" {if $good_info.SECTION_ID == $new_section_id}disabled{/if}>
		{section name=marginlevel loop=$sections_info.list[$new_section_id].TREE_LEVEL start=0 step=1}&mdash;{/section}
		{if $sections_info.list[$new_section_id].SECTION_NAME ne ""}{$sections_info.list[$new_section_id].SECTION_NAME}{else}({$new_section_id}){/if}
		</option>
	{/foreach}
	</select>
</td>
<td></td>
</tr>
<td></td>
<td colspan="3"><input type="submit" style="font-size: 120%;" value="Добавить или удалить"></td>
</tr>

</table>
</form>


</div>