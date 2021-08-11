<div class="specials goods_grid clearfix">
	{foreach from=$widget_goods_list key=good_id item=good_info}
		<div class="good">
			<div class="good_picture">
					{* {if $good_info.SOLD == 1}<div style="position: absolute; top: 50px; text-align: center; color:grey; font-size: 22px;"><b>ТОВАР ПРОДАН</b></div>{/if} *}
				<a href="{$base_path}/collection/{$good_id}/">
				{if isset($widget_goods_fotos_list[$good_id])}
					<img src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.nlist.prefix}{$widget_goods_fotos_list[$good_id].GOOD_FOTO_ID}.{$widget_goods_fotos_list[$good_id].TECH_INFO.extension}" {* {if $good_info.SOLD == 1}style="-webkit-filter: grayscale(100%);-webkit-filter: opacity(50%);" {/if} *} />
				{else}
					{* {if $good_info.SOLD == 1}<div style="position: absolute; top: 50px; text-align: center; color:grey; font-size: 22px;"><b>ТОВАР ПРОДАН</b></div>{/if} *}
					<img src="{$pics_path}/no-photo.png" />
				{/if}
				</a>
			</div>
			<div class="good_descr">
				<h2><a href="{$base_path}/collection/{$good_id}/">{$good_info.TITLE}</a></h2>
				<p>{$good_info.SHORT_TEXT|truncate:128:"...":false|nl2br}</p>
			</div>
		</div>
	{/foreach}

	{*
	<div class="good">
		<div class="good_picture"><img src="{$base_path}/images/goods/01.jpg" /></div>
		<h2>Ваза для цветов</h2>
		<p>Голландия. Керамика, обливная глазурь. Высота 42 см.</p>
		<p>Рубеж XVIII-XIX вв.</p>
	</div>

	<div class="good">
		<div class="good_picture"><img src="{$base_path}/images/goods/02.jpg" /></div>
		<h2>Коллекция гоголевских персонажей</h2>
		<p>50-е гг.</p>
		<p>Скульптор Воробьев Б. Я.</p>
	</div>

	<div class="good">
		<div class="good_picture"><img src="{$base_path}/images/goods/03.jpg" /></div>
		<h2>Кобальтовая ваза</h2>
		<p>Ваза, кобальт, бронза.</p>
		<p>20-е годы, Высота &mdash; 40 см</p>
	</div>
	*}

</div>