<h4>
{foreach from=$catalog_section_context_extended item=section_id name=context_loop}
	<a href="{$base_path}{$catalog_sections_uri}{foreach from=$sections_info.parents[$section_id] item=section_parent}{$sections_info.list[$section_parent].DIRNAME}/{/foreach}{$sections_info.list[$section_id].DIRNAME}/">{$sections_info.list[$section_id].SECTION_NAME}</a> {if !$smarty.foreach.context_loop.last}&rarr; {/if}
{/foreach}
</h4>

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
				{if $good_info.SOLD == 1}<div class="good_sold">Продано</div>{/if}
				{if $good_info.SOLD == 2}<div class="good_sold">В резерве</div>{/if}
				{if isset($goods_fotos_list[$good_id])}
					<a href="{$base_path}/collection/{$good_id}/{if $good_info.SECTION_ID ne $catalog_section_context}?ref={$catalog_section_context}{/if}">
						<img 
							{if $good_info.SOLD != 0}class="good_sold"{/if} 
							style="width: auto; height: auto;" src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.nlist.prefix}{$goods_fotos_list[$good_id].GOOD_FOTO_ID}.{$goods_fotos_list[$good_id].TECH_INFO.extension}"
						/>
					</a>
				{else}
					<a href="{$base_path}/collection/{$good_id}/"><img src="{$pics_path}/no-photo.png" /></a>
				{/if}
			</div>
			<div class="good_descr">
				<h2><a href="{$base_path}/collection/{$good_id}/">{$good_info.TITLE}</a></h2>
				<p>{$good_info.SHORT_TEXT|truncate:128:"...":false|nl2br}</p>
			</div>
		</div>
	{/foreach}


</div>


{if $common_admin_mode}
<div class="admin_actions clearfix" style="clear: both;">
	<a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/">Редактировать лоты в разделе</a>
	<a href="{$base_path}/admin/catalog/sections/{$section_id}/goods/new/">Добавить лот</a>
	<a href="{$base_path}/admin/catalog/sections/edit/?section_id={$section_id}">Редактировать раздел</a>
</div>
{/if}