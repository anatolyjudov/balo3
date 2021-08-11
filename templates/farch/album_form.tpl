<script language="javascript" src="{$base_path}/tools.js"></script>


{if $form_action eq "add"}
<form method="POST" action="{$base_path}/admin/farch/albums/new/add/" enctype="multipart/form-data">
{else}
<form method="POST" action="{$base_path}/admin/farch/albums/edit/modify/" enctype="multipart/form-data">
<input type="hidden" name="album_id" value="{$album_info.ALBUM_ID}">
{/if}

<table class="ctrl" cellspacing=0 cellpadding=4>

<tr class="striked">
<td valign=top><b>{#lang_farch_admin_parent_album#}</b></td>
<td>
<select name="parent_id">
<option value="0">{#lang_farch_admin_parent_album_none#}</option>
{foreach from=$albums_info.plain item=parent_album_id}
<option value="{$parent_album_id}" {if $album_info.PARENT_ID == $parent_album_id}selected{/if}>
{section name=marginlevel loop=$albums_info.list[$parent_album_id].TREE_LEVEL start=0 step=1}&mdash;{/section}</span> 
{if $albums_info.list[$parent_album_id].ALBUM_NAME ne ""}{$albums_info.list[$parent_album_id].ALBUM_NAME}{else}({$parent_album_id}){/if}
</option>
{/foreach}
</select>
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_farch_admin_album_name#}</b></td>
<td>
<input type="text" name="album_name" class="multilang" value="{$album_info.ALBUM_NAME|escape:'html'}" style="width: 450px;">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_farch_admin_album_published#}</b></td>
<td>
<input type="checkbox" name="published" {if $album_info.PUBLISHED == 1}checked{/if}>
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_farch_admin_album_picture#}</b></td>
<td>

<div style=" padding: 0; margin: 10px 0;">
{if isset($album_info.PICTURE_TECH_INFO.previews.main)}
<a target="_blank" href="{$farch_foto_params.folders.link}/{$album_info.ALBUM_ID}/{$farch_foto_params.previews.big.prefix}title.{$album_info.PICTURE_TECH_INFO.extension}">
<img id="album_img" src="{$farch_foto_params.folders.link}/{$album_info.ALBUM_ID}/{$farch_foto_params.previews.small.prefix}title.{$album_info.PICTURE_TECH_INFO.extension}">
</a>
{/if}
</div>

<input type="file" name="album_picture">
</td>
</tr>

<tr>
<td colspan="2"><b>{#lang_farch_admin_album_text#}</b></td>
</tr>

<tr>
<td colspan="2" style="padding-top: 0;"><textarea class="multilang" name="description" style="width: 700px; height: 250px;">{$album_info.DESCRIPTION}</textarea></td>
</tr>

<tr>
<td></td>
<td><input type="submit" {if $form_action eq "add"}value="{#lang_admin_add_btn_text#}"{else}value="{#lang_admin_change_btn_text#}"{/if} style="font-size: 18px;"></td>
</tr>

</table>

<input type="hidden" value="{$album_info.ALBUM_AUTHORS|escape:'html'}" name="album_authors" >
</form>