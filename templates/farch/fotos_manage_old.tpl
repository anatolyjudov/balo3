<h1>Фотографии</h1>


<div class="additional">
{insert script="$mods_path/menus_show.php" name="menus_show" menu_block_id=18 menu_block_template="ar_admin_right2.tpl" menu_cache="off"}
{insert script="$mods_path/menus_show.php" name="menus_show" menu_block_id=17 menu_block_template="ar_admin_right.tpl" menu_cache="off"}
</div>

<div class="content">

{if count($fotos_list) > 0}

<form action="{$admin_path}/fotos/update/" method="POST"><input type="hidden" name="update" value="on">
{if isset($errmsg_modify)}<p class="errmsg">{$errmsg_modify}</p>{/if}
{if isset($msg_modify)}<p class="msg">{$msg_modify}</p>{/if}

<table class="ctrl" style="width: 100%;">
<tr class="head">
<td>фото</td>
<td>текст</td>
<td></td>
</tr>
{foreach from=$fotos_list item=foto_info key=foto_id}
<tr>
<td><img src="{$arein_foto_params.folders.link}/{$arein_foto_params.previews.special.prefix}{$foto_id}.{$foto_info.TECH_INFO.extension}" border=0 alt="">
</td>
<td>
<p>подпись к фото</p>
<input type="text" style="width: 400px;" name="foto_title{$foto_id}" value="{$foto_info.FOTO_TITLE|escape:'html'}">
</td>
<td style="border-width: 0 0 1px; border-color: #ccc; border-style: solid;"rowspan="2"><!-- {$foto_id} --><input type="checkbox" name="del{$foto_id}"></td>
</tr>
<tr>
<td colspan="2" style="border-width: 0 0 1px; border-color: #ccc; border-style: solid;">
<a href="{$arein_foto_params.folders.link}/{$foto_id}.{$foto_info.TECH_INFO.extension}" target="_blank">оригинал</a> | 
<a href="{$arein_foto_params.folders.link}/{$arein_foto_params.previews.main.prefix}{$foto_id}.{$foto_info.TECH_INFO.extension}" target="_blank">основной размер</a> | 
<a href="{$arein_foto_params.folders.link}/{$arein_foto_params.previews.small.prefix}{$foto_id}.{$foto_info.TECH_INFO.extension}" target="_blank">размер поменьше</a> | 
<a href="{$arein_foto_params.folders.link}/{$arein_foto_params.previews.special.prefix}{$foto_id}.{$foto_info.TECH_INFO.extension}" target="_blank">квадратное превью</a>
</td>
</tr>
{/foreach}
<tr>
<td></td>
<td colspan="2"><input type="submit" value="Изменить / Удалить"></td>
</tr>
</table>

</form>

{else}
<p>Нет ни одного фото, добавьте</p>
{/if}


</div>