<h1>Лоты в разделе <em>{$sections_info.list[$section_id].SECTION_NAME}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog_section.tpl"}
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}
{if isset($msg)}<p class="msg">{$msg}</p>{/if}


{if count($goods_list) > 0}
<form action="{$base_path}/admin/catalog/sections/{$section_id}/goods/sort/" method="POST">
<table class="ctrl goods_manage">

{if count($goods_list) > 30}
<tr>
	<td colspan="4"></td>
	<td colspan="2"><input type="submit" value="Сохранить сортировку"></td>
</tr>
{/if}

<tr class="head">
	<td style="width: 20px;">№</td>
	<td></td>
	<td>Лот</td>
	<td style="width: 80px;">Цена</td>
	<td style="width: 80px;">Статус</td>
	<td>Сортировка</td>
	<td></td>
</tr>

{foreach from=$goods_list item=good_info key=good_id}
<tr class="{cycle values='odd,nodd'} {if $good_info.PUBLISHED == 0}good_hidden{/if} {if isset($good_info.SOLD)}{if $good_info.SOLD != 0}good_sold{/if}{/if} {if $good_info.SECTION_ID != $section_id}good_additional_section{/if}">
<td>{$good_id}<input type="hidden" name="{$good_id}" value="{$good_id}"></td>
<td>
{if isset($goods_fotos_list[$good_id])}
	<a 
		id="good"
		href="{$farch_foto_params.folders.link}/good_{$good_id}/{$goods_fotos_list[$good_id].GOOD_FOTO_ID}.{$goods_fotos_list[$good_id].TECH_INFO.extension}"
		data-fancybox-href="{$farch_foto_params.folders.link}/good_{$good_id}/{$goods_fotos_list[$good_id].GOOD_FOTO_ID}.{$goods_fotos_list[$good_id].TECH_INFO.extension}">

		<img src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.small.prefix}{$goods_fotos_list[$good_id].GOOD_FOTO_ID}.{$goods_fotos_list[$good_id].TECH_INFO.extension}" style="max-width: 150px;" /></a>
{/if}
</td>
<td>
	<a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/">{$good_info.TITLE}</a>
	{if $good_info.SECTION_ID != $section_id}
		<br><span style="font-size: 85%;"><span style="font-family: Arial, Helvetica;">&rarr; </span><a href="/admin/catalog/sections/{$good_info.SECTION_ID}/goods/">{$sections_info.list[$good_info.SECTION_ID].SECTION_NAME}</a></span>
	{/if}
</td>
<td>
{if isset($goods_prices_list[$good_id]) && ($goods_prices_list[$good_id].type ne 'no_price')}
{assign var=good_price value=$goods_prices_list[$good_id]}
{$good_price.price} {$currencies[$good_price.currency].SIGN}
{/if}
</td>

<td>
	{if isset($good_info.SOLD)}{if $good_info.SOLD == 1}Продан{elseif $good_info.SOLD == 2}Зарезервирован{else}В наличии{/if}{/if}
</td>

<td>
	<input type="text" value="{$good_info.COMPLEX_SORT_VALUE|escape:'html'}" style="width: 30px; font-size: 11px;" {if $good_info.SECTION_ID == $section_id}name="sort_{$good_id}"{else}name="complex_sort_{$good_id}"{/if}>
</td>

<td><a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/"><img src="{$pics_path}/edit.png" border=0 alt="{#lang_admin_edit_alt_text#}"></a><a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/delete/"><img src="{$pics_path}/delete.png" border=0 alt="{#lang_admin_remove_alt_text#}"></a></td>
</tr>
{/foreach}


<tr>
<td colspan="4"></td>
<td colspan="2"><input type="submit" value="{#lang_admin_savesort_btn_text#}"></td>
</tr>

</table>
</form>
{else}
<p>Нет лотов в этом разделе</p>
{/if}

<p><img src="{$pics_path}/add.png" border=0 alt="{#lang_admin_add_alt_text#}"> <a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/new/">Добавить лот</a></p>

</div>