<h4>
Поиск
</h4>

<div class="search_form">
	<form action="{$base_path}/search/" method="GET">
		<input type="text" name="s" value="{$catalog_search_query|escape:'html'}" placeholder="Искать на сайте"/> <input class="button" type="submit" value="Найти" />
	</form>
</div>

{*
{if count($catalog_found_sections) > 0}
<div class="search_sections_list clearfix">
	<span>Подходящие разделы:</span>
	{foreach from=$catalog_found_sections item=section_id name=sections_loop}
		<a href="{$base_path}{$catalog_sections_uri}{foreach from=$sections_info.parents[$section_id] item=section_parent}{$sections_info.list[$section_parent].DIRNAME}/{/foreach}{$sections_info.list[$section_id].DIRNAME}/">{$sections_info.list[$section_id].SECTION_NAME}</a> {if !$smarty.foreach.sections_loop.last}, {/if}
	{/foreach}
</div>
{/if}
*}

{if count($catalog_found_sections) > 0}

{foreach from=$catalog_found_sections item=section key=section_id}
	<h4>
		{foreach from=$section.parents item=cntx_section_id name=context_loop}
			<a href="{$base_path}{$catalog_sections_uri}{foreach from=$sections_info.parents[$cntx_section_id] item=section_parent}{$sections_info.list[$section_parent].DIRNAME}/{/foreach}{$sections_info.list[$cntx_section_id].DIRNAME}/">{$sections_info.list[$cntx_section_id].SECTION_NAME}</a> &rarr;
		{/foreach}
		<a href="{$base_path}{$catalog_sections_uri}{foreach from=$sections_info.parents[$section_id] item=section_parent}{$sections_info.list[$section_parent].DIRNAME}/{/foreach}{$sections_info.list[$section_id].DIRNAME}/">{$sections_info.list[$section_id].SECTION_NAME}</a>
	</h4>

	<div class="goods_grid sorted clearfix">
	{foreach from=$section.good_ids key=good_id item=n}
		{assign var="good_info" value=$goods_list[$good_id]}
		<div class="good {strip}
			{if isset($goods_fotos_list[$good_id])
				&& $goods_fotos_list[$good_id].TECH_INFO.original_image_info[0] >= $goods_fotos_list[$good_id].TECH_INFO.original_image_info[1]
			}
				wide
			{else}
				tall
			{/if}
			"
			{/strip}>
			<div class="good_picture">
				{if $good_info.SOLD == 1}<div class="good_sold">Продано</div>{/if}
				{if $good_info.SOLD == 2}<div class="good_sold">В резерве</div>{/if}
				{if isset($goods_fotos_list[$good_id])}
					<a href="{$base_path}/collection/{$good_id}/"><img style="width: auto; height: auto;"
						{if $good_info.SOLD != 0}class="good_sold"{/if}  src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.nlist.prefix}{$goods_fotos_list[$good_id].GOOD_FOTO_ID}.{$goods_fotos_list[$good_id].TECH_INFO.extension}" /></a>
				{else}
					<a href="{$base_path}/collection/{$good_id}/"><img src="{$pics_path}/no-photo.png" /></a>
				{/if}
			</div>
			<div class="good_descr">
				<h2>{$good_info.TITLE}</h2>
				<p>{$good_info.SHORT_TEXT|truncate:128:"...":false|nl2br}</p>
			</div>
		</div>
	{/foreach}
	</div>

{/foreach}

{/if}

{*
{if count($goods_list) > 0}
<div class="goods_grid clearfix">

	{foreach from=$goods_list key=good_id item=good_info}
		<div class="good {strip}
			{if isset($goods_fotos_list[$good_id])
				&& $goods_fotos_list[$good_id].TECH_INFO.original_image_info[0] >= $goods_fotos_list[$good_id].TECH_INFO.original_image_info[1]
			}
				wide
			{else}
				tall
			{/if}
			"
			{/strip}>
			<div class="good_picture">
				{if isset($goods_fotos_list[$good_id])}
					<a href="{$base_path}/collection/{$good_id}/"><img style="width: auto; height: auto;" src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.nlist.prefix}{$goods_fotos_list[$good_id].GOOD_FOTO_ID}.{$goods_fotos_list[$good_id].TECH_INFO.extension}" /></a>
				{else}
					<a href="{$base_path}/collection/{$good_id}/"><img src="{$pics_path}/no-photo.png" /></a>
				{/if}
			</div>
			<div class="good_descr">
				<h2>{$good_info.TITLE}</h2>
				<p>{$good_info.SHORT_TEXT|truncate:128:"...":false|nl2br}</p>
			</div>
		</div>
	{/foreach}

</div>
{else}

<div>
	<p>К сожалению, по этому запросу ничего не найдено.</p>
</div>

{/if}

*}