<h1>{#lang_farch_admin_manage_albums_title#}</h1>

{include file="farch/admin_farch.tpl"}

<div class="content">

{if count($albums_info.list) > 0}
<form action="{$base_path}/admin/farch/albums/sort/" method="POST">
<table class="ctrl" style="width: 100%;">
{if count($albums_info.list) > 30}
<tr>
<td colspan="3"></td>
<td colspan="3"><input type="submit" value="{#lang_admin_savesort_btn_text#}"></td>
</tr>
{/if}
<tr class="head">
<td style="width: 20px;">#</td>
<td>{#lang_farch_admin_manage_albums_head_album#}</td>
<td>{#lang_farch_admin_manage_albums_head_cover#}</td>
<td>{#lang_farch_admin_manage_albums_head_sort#}</td>
<td style="width: 50px;"></td>
<td></td>
</tr>
{foreach from=$albums_info.plain item=album_id}
<tr class="{cycle values='odd,nodd'}">
<td>{$album_id}</td>
<td><nobr><span style="color: #bbb;">{section name=marginlevel loop=$albums_info.list[$album_id].TREE_LEVEL start=0 step=1}&mdash;&ndash;{/section}</span></nobr>&nbsp;<a href="{$base_path}/admin/farch/fotos/?album_id={$album_id}">{$albums_info.list[$album_id].ALBUM_NAME}</a></td>
<td>
{if isset($albums_info.list[$album_id].PICTURE_TECH_INFO.previews)}
<a target="_blank" href="{$farch_foto_params.folders.link}/{$album_id}/{$farch_foto_params.previews.big.prefix}title.{$albums_info.list[$album_id].PICTURE_TECH_INFO.extension}">
<img id="album_img" src="{$farch_foto_params.folders.link}/{$album_id}/{$farch_foto_params.previews.small.prefix}title.{$albums_info.list[$album_id].PICTURE_TECH_INFO.extension}">
{/if}
</td>
<td><nobr><span style="color: #bbb;">{section name=marginlevel loop=$albums_info.list[$album_id].TREE_LEVEL start=0 step=1}&mdash;{/section}</span> <input type="text" value="{$albums_info.list[$album_id].SORT_VALUE|escape:'html'}" style="width: 30px; font-size: 11px;" name="sort_{$album_id}"></nobr></td>
<td><a href="{$base_path}/admin/farch/fotos/?album_id={$album_id}">{#lang_farch_admin_manage_albums_manage_pictures_link#}</a></td>
<td><a href="{$base_path}/admin/farch/albums/edit/?album_id={$album_id}"><img src="{$pics_path}/edit.png" border=0 alt="{#lang_admin_edit_alt_text#}"></a><a href="{$base_path}/admin/farch/albums/delete/?album_id={$album_id}"><img src="{$pics_path}/delete.png" border=0 alt="{#lang_admin_remove_alt_text#}"></a></td>
</tr>
{/foreach}
<tr>
<td colspan="3"></td>
<td colspan="3"><input type="submit" value="{#lang_admin_savesort_btn_text#}"></td>
</tr>
</table>
</form>
{else}
<p>{#lang_farch_admin_manage_albums_no_albums#}</p>
{/if}

</div>