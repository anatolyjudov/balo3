
{if $form_action eq "add"}
<form method="POST" action="{$base_path}/admin/catalog/sections/new/add/" enctype="multipart/form-data">
{else}
<form method="POST" action="{$base_path}/admin/catalog/sections/edit/modify/" enctype="multipart/form-data">
<input type="hidden" name="section_id" value="{$section_info.SECTION_ID}">
{/if}

<table class="ctrl" cellspacing=0 cellpadding=4>

<tr class="striked">
<td valign=top><b>{#lang_catalog_admin_parent_section#}</b></td>
<td>
<select name="parent_id">
<option value="0">{#lang_catalog_admin_parent_section_none#}</option>
{foreach from=$sections_info.plain item=parent_section_id}
<option value="{$parent_section_id}" {if $section_info.PARENT_ID == $parent_section_id}selected{/if}>
{section name=marginlevel loop=$sections_info.list[$parent_section_id].TREE_LEVEL start=0 step=1}&mdash;{/section}</span> 
{if $sections_info.list[$parent_section_id].SECTION_NAME ne ""}{$sections_info.list[$parent_section_id].SECTION_NAME}{else}({$parent_section_id}){/if}
</option>
{/foreach}
</select>
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_catalog_admin_section_name#}</b></td>
<td>
<input type="text" name="section_name" class="multilang" value="{$section_info.SECTION_NAME|escape:'html'}" style="width: 450px;">
</td>
</tr>


<tr class="striked">
<td valign=top><b>Имя папки</b><br><small>Короткое название на латинице без пробелов<br>для формирования адреса страницы</small></td>
<td>
<input type="text" name="dirname" value="{$section_info.DIRNAME|escape:'html'}" style="width: 250px;">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_catalog_admin_section_published#}</b></td>
<td>
<input type="checkbox" name="published" {if $section_info.PUBLISHED == 1}checked{/if}>
</td>
</tr>

{*
<script language="javascript" src="{$base_path}/tools.js"></script>
<tr class="striked">
<td valign=top><b>{#lang_catalog_admin_section_picture#}</b></td>
<td>

<div style=" padding: 0; margin: 10px 0;">
{if isset($section_info.PICTURE_TECH_INFO.previews.main)}
<a target="_blank" href="{$catalog_foto_params.folders.link}/{$section_info.SECTION_ID}/{$catalog_foto_params.previews.big.prefix}title.{$section_info.PICTURE_TECH_INFO.extension}">
<img id="section_img" src="{$catalog_foto_params.folders.link}/{$section_info.SECTION_ID}/{$catalog_foto_params.previews.small.prefix}title.{$section_info.PICTURE_TECH_INFO.extension}">
</a>
{/if}
</div>

<input type="file" name="section_picture">
</td>
</tr>
*}

<tr>
<td colspan="2"><b>{#lang_catalog_admin_section_text#}</b></td>
</tr>

<tr>
<td colspan="2" style="padding-top: 0;"><textarea class="multilang" name="description" style="width: 700px; height: 250px;">{$section_info.DESCRIPTION}</textarea></td>
</tr>

<tr>
<td></td>
<td><input type="submit" {if $form_action eq "add"}value="{#lang_admin_add_btn_text#}"{else}value="{#lang_admin_change_btn_text#}"{/if} style="font-size: 18px;"></td>
</tr>

</table>

<input type="hidden" value="{$section_info.SECTION_AUTHORS|escape:'html'}" name="section_authors" >
</form>