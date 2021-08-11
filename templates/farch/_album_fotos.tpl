{if count($show_fotos_list) > 0}
{if isset($farch_foto_params.folders)}
<div class="fotoalbum items_list">
{foreach from=$show_fotos_list item=foto key=foto_id name=fotoloop}
<div class="foto item">
	<a class="zoom" rel="album" href="{$farch_foto_params.folders.link}/{$album_id}/{$farch_foto_params.previews.main.prefix}{$foto_id}.{$foto.TECH_INFO.extension}" title="{$foto.FOTO_TITLE|strip_tags|escape:'html'}">
		<img class="item_foto" src="{$farch_foto_params.folders.link}/{$album_id}/{$farch_foto_params.previews.item.prefix}{$foto_id}.{$foto.TECH_INFO.extension}" border=0 alt="{$foto.FOTO_TITLE|strip_tags|escape:'html'}">
		<div class="item_text">{$foto.FOTO_TITLE|strip_tags}</div>
	</a>
</div>
{if $smarty.foreach.fotoloop.iteration%3 == 0}<div class="clearing clearing_left"></div>{/if}
{/foreach}
</div><br clear="left"/>
{else}
foto params not initialized
{/if}
{else}
<p>{#lang_farch_album_empty#}</p>
{/if}