{*
{if count($albums_info.childs[$parent_album_id].childs) > 0}
<h4>Фотогалерея</h4>
<div class="albums_list clearfixleft">
{foreach from=$albums_info.childs[$parent_album_id].childs item=child_album_id key=n name=faalloop}
	{if $smarty.foreach.faalloop.iteration < 5}
	<div class="album">
		<a href="{$base_path}/gallery/{$child_album_id}/"><img {if isset($albums_info.list[$child_album_id].PICTURE_TECH_INFO.extension)}src="{$farch_foto_params.folders.link}/{$child_album_id}/{$farch_foto_params.previews.tiny.prefix}title.{$albums_info.list[$child_album_id].PICTURE_TECH_INFO.extension}"{else}src="{$pics_path}/gallery_empty_pic.png"{/if} /></a>
		<h3>{$albums_info.list[$child_album_id].ALBUM_NAME}</h3>
		<p>{$albums_info.list[$child_album_id].DESCRIPTION|truncate:64}</p>
	</div>
	{/if}
{/foreach}
</div>
<div class="more_link"><a href="{$base_path}/gallery/">Все альбомы</a> &rarr;</div>
{/if}
*}

{*
<div class="fa_album_link item">
	<a href="{$base_path}/gallery/{$child_album_id}/"><img class="item_foto" {if isset($albums_info.list[$child_album_id].PICTURE_TECH_INFO.extension)}src="{$farch_foto_params.folders.link}/{$child_album_id}/{$farch_foto_params.previews.tiny.prefix}title.{$albums_info.list[$child_album_id].PICTURE_TECH_INFO.extension}"{else}src="{$pics_path}/gallery_empty_pic.png"{/if} border=0 alt=""></a>
	<div class="item_title"><a href="{$base_path}/gallery/{$child_album_id}/">{$albums_info.list[$child_album_id].ALBUM_NAME}</a></div>
</div>
*}

{*
<h4>Последние фотографии</h4>
<div class="albums_list clearfixleft">
	<div class="album">
		<img src="/images/example/1.png" />
		<h3>Название альбома</h3>
		<p>Реальность понимает под собой язык образов, изменяя привычную реальность.</p>
	</div>
	<div class="album">
		<img src="/images/example/2.png" />
		<h3>Матчи плей-офф сезона 2013-2014</h3>
		<p>Надо сказать, что позитивизм индуцирует закон исключённого третьего</p>
	</div>
	<div class="album">
		<img src="/images/example/3.png" />
		<h3>На скамейке запасных</h3>
		<p>Отсюда естественно следует, что мир категорически индуцирует структурализм, открывая новые горизонты.</p>
	</div>
	<div class="album">
		<img src="/images/example/4.png" />
		<h3>Вперёд, Зеленоград!</h3>
		<p>Закон внешнего мира контролирует трагический знак, отрицая очевидное.</p>
	</div>
</div>
*}