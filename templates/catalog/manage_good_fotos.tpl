<h1>Фото лота <em>{$good_info.TITLE}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog_good.tpl"}
{include file="catalog/admin_catalog_section.tpl"}
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

	{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}
	{if isset($msg)}<p class="msg">{$msg}</p>{/if}


	<form action="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/fotos/" method="POST"  enctype="multipart/form-data">
	<input type="hidden" name="send" value="1">
	<table class="ctrl" >
	<tr class="head">
	<td style="width: 20px;"></td>
	<td></td>
	<td>сортировка</td>
	<td>удалить</td>
	</tr>

	{if count($good_fotos_list) > 0}
	{foreach from=$good_fotos_list item=foto_info key=good_foto_id}
	<tr class="{cycle values='odd,nodd'}">
	<td>{*{$good_foto_id}*}<input type="hidden" name="{$good_foto_id}" value="{$good_foto_id}"></td>
	<td>
	<img src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.small.prefix}{$good_foto_id}.{$foto_info.TECH_INFO.extension}" />
	</td>
	<td><input type="text" name="sort_{$good_foto_id}" style="width: 50px;" value="{$foto_info.SORT_VALUE|escape:'html'}"></td>
	<td><input type="checkbox" name="del_{$good_foto_id}"></td>
	</tr>
	{/foreach}
	{/if}

	<tr>
	<td></td>
	<td colspan="3"><input type="submit" value="Сохранить изменения"></td>
	</tr>

	</table>
	</form>

	<h3>Добавление фотографий</h3>

	<script type="text/javascript" src="/js/lib/dropzone/dropzone.min.js"></script>
	<div id="photoUpload" class="dropzone"></div>

</div>


{literal}
<script type="text/javascript">
var qstr = "?act=foto_upload&good_id=" +  {/literal}{$good_id}{literal};

Dropzone.options.photoUpload = {
	url: "{/literal}{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/fotos/upload/{literal}" + qstr,
	paramName : 'file_upload',
	clickable : true,
	dictDefaultMessage : 'Перенесите файлы или кликните для добавления',
	acceptedFiles : '.jpg,.jpeg,.png',
	createImageThumbnails : false,
	queuecomplete : function() {
		location.reload();
	}
};

</script>
{/literal}