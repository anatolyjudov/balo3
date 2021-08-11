<h1>ФОТОГАЛЕРЕЯ</h1>

<div class="album_info">
{* навигация вглубь *}
<p class="nav_str">
<a href="{$base_path}/gallery/">Фотогалерея</a>
{if count($albums_info.parents[$album_id]) > 0}
{foreach from=$albums_info.parents[$album_id] item=parent_album_id key=n name=fanavloop}
 &rarr; <a href="{$base_path}/gallery/{$parent_album_id}/">{$albums_info.list[$parent_album_id].ALBUM_NAME}</a>
{/foreach}
{/if}
 &rarr; <a href="{$base_path}/gallery/{$album_id}/">{$albums_info.list[$album_id].ALBUM_NAME}</a>
</p>

{* заголовок *}
<h2>{$albums_info.list[$album_id].ALBUM_NAME}</h2>
</div>

{* фотография *}
{include file="farch/_album_fotos_init.tpl" farch_fancybox_init_profile="simple"}
<div class="foto_page">
<a class="zoom" rel="album" href="{$farch_foto_params.folders.link}/{$album_id}/{$foto_id}.{$fotos_list[$foto_id].TECH_INFO.extension}"><img style="width: 90%;" src="{$farch_foto_params.folders.link}/{$album_id}/{$farch_foto_params.previews.main.prefix}{$foto_id}.{$fotos_list[$foto_id].TECH_INFO.extension}" border=0 alt="{$fotos_list[$foto_id].FOTO_TITLE|strip_tags|escape:'html'}"></a>
<div class="foto_title">{$fotos_list[$foto_id].FOTO_TITLE|strip_tags|escape:'html'}</div>
<script type="text/javascript">
VK.Widgets.Comments("vk_comments{$foto_id}", {ldelim} limit: 10, attach: false {rdelim}, 'fotoarchive{$foto_id}');
</script>
</div>



{* ссылка на управление *}
<div class="fa_albums_list">
{if db_check_rights(get_user_id(), "ACCESS_NODE", "/admin/farch/")}
<div class="adminPanel">
<a href="/admin/farch/">Управление фотогалереей</a>
</div>
{/if}
</div>