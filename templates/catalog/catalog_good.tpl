<h4>
{foreach from=$catalog_section_context_extended item=section_id name=context_loop}
	<a href="{$base_path}{$catalog_sections_uri}{foreach from=$sections_info.parents[$section_id] item=section_parent}{$sections_info.list[$section_parent].DIRNAME}/{/foreach}{$sections_info.list[$section_id].DIRNAME}/">{$sections_info.list[$section_id].SECTION_NAME}</a> {if !$smarty.foreach.context_loop.last}&rarr; {/if}
{/foreach}
</h4>

{assign var="omnibox_orientation" value="vertical"}
{foreach from=$goods_fotos_list[$good_id] item=good_foto name=good_big_picture}
{if $smarty.foreach.good_big_picture.first}
	{if $good_foto.TECH_INFO.previews.big[0] >= $good_foto.TECH_INFO.previews.big[1] }
		{assign var="omnibox_orientation" value="horizontal"}
	{/if}
{/if}
{/foreach}

<div class="good_info orientation_{$omnibox_orientation} clearfix">

	<h1>{$good_info.TITLE}</h1>

	<div class="good_omnibox">

		<div class="good_picture">
			{foreach from=$goods_fotos_list[$good_id] item=good_foto name=good_big_picture}
			{if $smarty.foreach.good_big_picture.first}
			<a 
				href="{$farch_foto_params.folders.link}/good_{$good_id}/{$good_foto.GOOD_FOTO_ID}.{$good_foto.TECH_INFO.extension}"
				data-fancybox-href="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.big.prefix}{$good_foto.GOOD_FOTO_ID}.{$good_foto.TECH_INFO.extension}"
			>
				<img src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.card.prefix}{$good_foto.GOOD_FOTO_ID}.{$good_foto.TECH_INFO.extension}" /></a>
			{/if}
			{/foreach}
		</div>

	</div>

	<div class="good_description">


		{if count($goods_fotos_list[$good_id]) > 1}
		<div class="good_previews">
			<ul class="thumbnails">
			{foreach from=$goods_fotos_list[$good_id] item=good_foto name=good_pictures}
				<li>
					<a 
						rel="good" href="{$farch_foto_params.folders.link}/good_{$good_id}/{$good_foto.GOOD_FOTO_ID}.{$good_foto.TECH_INFO.extension}"
						data-standard="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.card.prefix}{$good_foto.GOOD_FOTO_ID}.{$good_foto.TECH_INFO.extension}"
					>
						<img src="{$farch_foto_params.folders.link}/good_{$good_id}/{$farch_foto_params.previews.small.prefix}{$good_foto.GOOD_FOTO_ID}.{$good_foto.TECH_INFO.extension}" />
					</a>
				</li>
			{/foreach}
			</ul>
		</div>
		{/if}

		{if $good_info.DESCRIPTION eq ''}
		<div class="good_text good_short_text">
			<p>{$good_info.SHORT_TEXT|nl2br}</p>
		</div>
		{else}
		<div class="good_text">
			<p>{$good_info.DESCRIPTION|nl2br}</p>
		</div>
		{/if}

		{if $author_info ne ""}
			<div class="good_text good_author">
			{if count($author_info) eq 1}<p><b>{#lang_authors_author#}:</b><br>{elseif count($author_info) > 1}<p><b>{#lang_authors_authors#}:</b><br>{/if}
			{foreach from=$author_info item=author}
			<a href="{$base_path}/authors/{$author.id}/">{$author.sirname} {$author.name} {$author.patronymic}</a></p>
		{/foreach}
			</div>
		{/if}

		<div class="good_price">

			{if $good_info.SOLD == 0}

			{if $good_price.type eq 'no_price'}
			<a class="button" href="{$base_path}/feedback/?ask={$good_id}">{#lang_catalog_btn_request_price#}</a>
			{else}
			<span class="price">{#lang_catalog_price#} &mdash; {$good_price.price|number_format} {$currencies[$good_price.currency].SIGN}</span> <a class="button" href="{$base_path}/feedback/?buy={$good_id}">{#lang_catalog_btn_buy#}</a>
			{/if}

			{else}
				{if $good_info.SOLD == 1}<button disabled class="good_sold">{#lang_catalog_item_sold#}</button>{/if}
				{if $good_info.SOLD == 2}<button disabled class="good_sold">{#lang_catalog_item_reserved#}</button>{/if}
			{/if}
			
		</div>

	</div>

	<div class="good_discuss">
		<a class="button smaller" href="{$base_path}/feedback/?discuss={$good_id}">{#lang_catalog_btn_report_mistake#}</a>
	</div>


</div>

{if $common_admin_mode}
<div class="admin_actions" style="clear: both;">
	<a href="{$base_path}/admin/catalog/sections/{$good_info.SECTION_ID}/goods/manage/{$good_id}/">Редактировать лот</a>
</div>
{/if}