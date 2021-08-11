	<h4>
	<a href="{$base_path}/authors/">{#author_vmenu_list#}</a> &rarr; <a href="{$base_path}/authors/{$author_info.id}/">{$author_info.sirname} {$author_info.name} {$author_info.patronymic}</a>
	</h4>

	{if $errmsg}<p style="color:red;">{$errmsg}</p>{/if}

	{if $author_info ne ""}

	<h1>{$author_info.sirname} {$author_info.name} {$author_info.patronymic}</h1>

	<div class="author_text">
		{$author_info.description}
	</div>

	{if $goods_info}
	
		<h3 class="author_goods_title">{#author_lots#}</h3>

		<div class="goods_grid clearfix">
			{foreach from=$goods_info item=good_info key=good_id}

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
						{if isset($goods_fotos_list[$good_id])}
							<a href="{$base_path}/collection/{$good_id}/">
								<img 
									{if $good_info.SOLD == 1}class="good_sold"{/if} 
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
		<div>

	{/if}

	{/if}