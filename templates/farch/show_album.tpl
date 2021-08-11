<h1>{$albums_info.list[$album_id].ALBUM_NAME}</h1>

<link rel="stylesheet" type="text/css" href="{$base_path}/js/fancybox-2.1.0/jquery.fancybox.css" media="screen" />

{*
<div class="album_info">
<p class="nav_str">
<a href="{$base_path}/gallery/">Фотогалерея</a>
{if count($albums_info.parents[$album_id]) > 0}
{foreach from=$albums_info.parents[$album_id] item=parent_album_id key=n name=fanavloop}
 &rarr; <a href="{$base_path}/gallery/{$parent_album_id}/">{$albums_info.list[$parent_album_id].ALBUM_NAME}</a>
{/foreach}
{/if}
 &rarr; {$albums_info.list[$album_id].ALBUM_NAME}
</p>
<h2></h2>
</div>
*}

{* вложенные альбомы *}
{if count($albums_info.childs[$album_id].childs) > 0}
<div class="fa_albums_list items_list">
{foreach from=$albums_info.childs[$album_id].childs item=child_album_id key=n name=faalloop}
<div class="fa_album_link item">
	<a href="{$base_path}/gallery/{$child_album_id}/"><img class="item_foto" {if isset($albums_info.list[$child_album_id].PICTURE_TECH_INFO.extension)}src="{$farch_foto_params.folders.link}/{$child_album_id}/{$farch_foto_params.previews.item.prefix}title.{$albums_info.list[$child_album_id].PICTURE_TECH_INFO.extension}"{else}src="{$pics_path}/gallery_empty_pic.png"{/if} border=0 alt=""></a>
	<div class="item_title"><a href="{$base_path}/gallery/{$child_album_id}/">{$albums_info.list[$child_album_id].ALBUM_NAME}</a></div>
</div>
{if $smarty.foreach.faalloop.iteration % 3 == 0}<br clear="left" />{/if}
{/foreach}
<br clear="left" />
</div>
{/if}


{* фотографии в альбоме *}
{include file="farch/_album_fotos_init.tpl" farch_fancybox_init_profile="simple"}
{include file="farch/_album_fotos.tpl" show_fotos_list=$fotos_list}

<div class="album_info">
<div class="album_descr">
{$album_description_info.DESCRIPTION}
</div>
</div>

<h2>{#lang_farch_other_albums_title#}</h2>
{* соседние альбомы на верхнем уровне *}
{if isset($albums_info.tree[$album_id])}
<div class="album_info">
<div class="fa_albums_list items_list">
{assign var="iteration" value=0}
{foreach from=$albums_info.tree item=n key=root_album_id name=faalloop}
{if $root_album_id != $album_id}
	{assign var="iteration" value=$iteration+1}
	<div class="fa_album_link item">
		<a href="{$base_path}/press/gallery/{$root_album_id}/"><img class="item_foto" {if isset($albums_info.list[$root_album_id].PICTURE_TECH_INFO.extension)}src="{$farch_foto_params.folders.link}/{$root_album_id}/{$farch_foto_params.previews.item.prefix}title.{$albums_info.list[$root_album_id].PICTURE_TECH_INFO.extension}"{else}src="{$pics_path}/gallery_empty_pic.png"{/if} border=0 alt=""></a>
		<div class="item_title"><a href="{$base_path}/press/gallery/{$root_album_id}/">{$albums_info.list[$root_album_id].ALBUM_NAME}</a></div>
	</div>
	{if $iteration % 3 == 0}<br clear="left" />{/if}
{/if}
{/foreach}
<br clear="left" />
</div>
</div>
{/if}

{* соседние альбомы на других уровнях *}
{if !isset($albums_info.tree[$album_id])}


	{assign var="parent_album_id" value=$albums_info.list[$album_id].PARENT_ID}

	{if count($albums_info.childs[$parent_album_id].childs) > 1}

		<div class="album_info">
		<h2>Соседние альбомы</h2><br>
		<div class="fa_albums_list items_list">
		{assign var="iteration" value=0}
		{foreach from=$albums_info.childs[$parent_album_id].childs item=nested_album_id key=n name=faalloop}
		{if $nested_album_id != $album_id}
			{assign var="iteration" value=$iteration+1}
			<div class="fa_album_link item">
				<a href="{$base_path}/press/gallery/{$nested_album_id}/"><img class="item_foto" {if isset($albums_info.list[$nested_album_id].PICTURE_TECH_INFO.extension)}src="{$farch_foto_params.folders.link}/{$nested_album_id}/{$farch_foto_params.previews.item.prefix}title.{$albums_info.list[$nested_album_id].PICTURE_TECH_INFO.extension}"{else}src="{$pics_path}/gallery_empty_pic.png"{/if} border=0 alt=""></a>
				<div class="item_title"><a href="{$base_path}/press/gallery/{$nested_album_id}/">{$albums_info.list[$nested_album_id].ALBUM_NAME}</a></div>
			</div>
			{if $iteration % 3 == 0}<br clear="left" />{/if}
		{/if}
		{/foreach}
		<br clear="left" />
		</div>
		</div>

	{/if}

{/if}


{* ссылка на управление *}
<div class="fa_albums_list">
{if db_check_rights(get_user_id(), "ACCESS_NODE", "/admin/farch/")}
<div class="adminPanel">
<a href="/admin/farch/">Управление фотогалереей</a>
</div>
{/if}
</div>