<div class="horizontal_menu">
	<span>
		{foreach from=$meta_navigation_info item=meta_nav_section_info name=meta_nav_loop}
			{if $meta_nav_section_info.URI ne '/' and ( $meta_nav_section_info.INNER_TITLE ne '' || $meta_nav_section_info.TITLE ne '' ) }
			<a href="{$meta_nav_section_info.URI}">{strip}
				{if $meta_nav_section_info.INNER_TITLE ne ''}
					{$meta_nav_section_info.INNER_TITLE}
				{else}
					{if $meta_nav_section_info.TITLE ne ''}
						{$meta_nav_section_info.TITLE}
					{/if}
				{/if}
			{/strip}</a> / 
			{/if}
		{/foreach}
	</span>
</div>