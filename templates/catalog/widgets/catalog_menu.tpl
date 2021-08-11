{strip}
<div class="menu catalog_menu">

	{assign var="first_second_level" value=true}

	
	{* LEVEL TOP *}
	<ul>
		{balo3_widget family="textblocks" widget="textblocks_show" textblock_name="surprise_link" textblock_template="clean.tpl"}
	{foreach from=$sections_info.tree key=section_id item=t1}
		{if $sections_info.list[$section_id].PUBLISHED == 1}
		<li {if in_array($section_id, $catalog_section_context_extended)}class="context"{/if}>
		<a href="{$catalog_sections_uri}{foreach from=$sections_info.parents[$section_id] item=parent_section_id}{$sections_info.list[$parent_section_id].DIRNAME}/{/foreach}{$sections_info.list[$section_id].DIRNAME}/">{$sections_info.list[$section_id].SECTION_NAME}</a>

		{if count($t1) > 0 && ($first_second_level || in_array($section_id, $catalog_section_context_extended))}
			{* LEVEL 2 *} {assign var="first_second_level" value=false}
			<ul>
			{foreach from=$t1 key=section_id item=t2}
				{if $sections_info.list[$section_id].PUBLISHED == 1}
				<li {if in_array($section_id, $catalog_section_context_extended)}class="context"{/if}>
				<a href="{$catalog_sections_uri}{foreach from=$sections_info.parents[$section_id] item=parent_section_id}{$sections_info.list[$parent_section_id].DIRNAME}/{/foreach}{$sections_info.list[$section_id].DIRNAME}/">{$sections_info.list[$section_id].SECTION_NAME}</a>

				{if count($t2) > 0 && in_array($section_id, $catalog_section_context_extended)}
					{* LEVEL 3 *}
					<ul>
					{foreach from=$t2 key=section_id item=t3}
						{if $sections_info.list[$section_id].PUBLISHED == 1}
						<li {if in_array($section_id, $catalog_section_context_extended)}class="context"{/if}>
						<a href="{$catalog_sections_uri}{foreach from=$sections_info.parents[$section_id] item=parent_section_id}{$sections_info.list[$parent_section_id].DIRNAME}/{/foreach}{$sections_info.list[$section_id].DIRNAME}/">{$sections_info.list[$section_id].SECTION_NAME}</a>

						</li>
						{/if}
					{/foreach}
					</ul>
				{/if}

				</li>
				{/if}
			{/foreach}
			</ul>
		{/if}

		</li>
		{/if}
	{/foreach}
	</ul>

</div>
{/strip}