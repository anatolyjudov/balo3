<h1>{$common_page_metainfo.TITLE}</h1>

<div class="fa_albums_list items_list">
{if count($albums_info.tree) > 0}
{foreach from=$albums_info.tree key=album_id item=n name=faalloop}
<div class="fa_album_link item">
	<a href="{$base_path}/press/gallery/{$album_id}/"><img class="item_foto" {if isset($albums_info.list[$album_id].PICTURE_TECH_INFO.extension)}src="{$farch_foto_params.folders.link}/{$album_id}/{$farch_foto_params.previews.item.prefix}title.{$albums_info.list[$album_id].PICTURE_TECH_INFO.extension}"{else}src="{$pics_path}/gallery_empty_pic.png"{/if} border=0 alt=""></a>
	<div class="item_title"><a href="{$base_path}/press/gallery/{$album_id}/">{$albums_info.list[$album_id].ALBUM_NAME}</a></div>
</div>
{if $smarty.foreach.faalloop.iteration % 3 == 0}<br clear="left" />{/if}
{/foreach}
<br clear="left" />
{/if}

{if db_check_rights(get_user_id(), "ACCESS_NODE", "/admin/farch/")}
<div class="adminPanel">
<a href="/admin/farch/">Управление фотогалереей</a>
</div>
{/if}

</div>