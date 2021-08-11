<h1>{#lang_catalog_admin_manage_sections_title#}</h1>

<div class="additional">
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if count($sections_info.list) > 0}
<form action="{$base_path}/admin/catalog/sections/sort/" method="POST">
<table class="ctrl sections_manage" style="width: 100%;">
{if count($sections_info.list) > 30}
<tr>
<td colspan="1"></td>
<td colspan="5"><input type="submit" value="{#lang_admin_savesort_btn_text#}"></td>
</tr>
{/if}
<tr class="head">
<td style="width: 20px;">№</td>
<td>{#lang_catalog_admin_manage_sections_head_sort#}</td>
<td>{#lang_catalog_admin_manage_sections_head_section#}</td>
<td>{*{#lang_catalog_admin_manage_sections_head_cover#}*}</td>
<td></td>
<td></td>
</tr>
{foreach from=$sections_info.plain item=section_id}
<tr class="{cycle values='odd,nodd'} {if $sections_info.list[$section_id].PUBLISHED == 0}section_hidden{/if}">
<td>{$section_id}</td>
<td><nobr><span style="color: #bbb;">{section name=marginlevel loop=$sections_info.list[$section_id].TREE_LEVEL start=0 step=1}&mdash;{/section}</span> <input type="text" value="{$sections_info.list[$section_id].SORT_VALUE|escape:'html'}" style="width: 30px; font-size: 11px;" name="sort_{$section_id}"></nobr></td>
<td><nobr><span style="color: #bbb;">{section name=marginlevel loop=$sections_info.list[$section_id].TREE_LEVEL start=0 step=1}&mdash;&ndash;{/section}</span></nobr>&nbsp;<a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/">{$sections_info.list[$section_id].SECTION_NAME}</a></td>
<td>
{*
{if isset($sections_info.list[$section_id].PICTURE_TECH_INFO.previews)}
<a target="_blank" href="{$catalog_foto_params.folders.link}/{$section_id}/{$catalog_foto_params.previews.big.prefix}title.{$sections_info.list[$section_id].PICTURE_TECH_INFO.extension}">
<img id="section_img" src="{$catalog_foto_params.folders.link}/{$section_id}/{$catalog_foto_params.previews.small.prefix}title.{$sections_info.list[$section_id].PICTURE_TECH_INFO.extension}">
{/if}
*}
</td>
<td><a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/">{#lang_catalog_admin_manage_sections_manage_goods_link#} ({$section_counts[$section_id].published|default:'0'}/{$section_counts[$section_id].count|default:'0'})</a></td>
<td><a href="{$base_path}/admin/catalog/sections/edit/?section_id={$section_id}"><img src="{$pics_path}/edit.png" border=0 alt="{#lang_admin_edit_alt_text#}"></a><a href="{$base_path}/admin/catalog/sections/delete/?section_id={$section_id}"><img src="{$pics_path}/delete.png" border=0 alt="{#lang_admin_remove_alt_text#}"></a></td>
</tr>
{/foreach}
<tr>
<td colspan="1"></td>
<td colspan="5"><input type="submit" value="{#lang_admin_savesort_btn_text#}"></td>
</tr>
</table>
</form>
{else}
<p>{#lang_catalog_admin_manage_sections_no_sections#}</p>
{/if}

</div>