{if count($fotos_list) > 0}
{if isset($farch_foto_params.folders)}
<ul class="about_persons clearfix">

{foreach from=$fotos_list item=foto key=foto_id name=fotoloop}
	<li class="person">
		<img class="person_foto" src="{$farch_foto_params.folders.link}/{$album_id}/{$farch_foto_params.previews.list.prefix}{$foto_id}.{$foto.TECH_INFO.extension}" border=0 alt="{$foto.FOTO_TITLE|strip_tags|escape:'html'}">
		<br>{$foto.FOTO_TITLE}
	</li>
{/foreach}

</ul>
{else}
foto params not initialized
{/if}
{else}
<p>{#lang_farch_album_empty#}</p>
{/if}
