<ul>
{foreach from=$menu_items item=menu name=mm}
	<li {if ($uri_array[1] eq $menu.PARAMS) or ($uri_array[1] eq '' and $menu.PARAMS eq '--main')}class="here"{/if}><a href="{$menu.LINK_ADDRESS}">{$menu.LINK_TEXT}</a>
	{if ($menu.CHILD_BLOCK_ID ne "") and ($menu.CHILD_BLOCK_ID ne 0) and (count($child_blocks[$menu.CHILD_BLOCK_ID]) > 0) and ($uri_array[1] eq $menu.PARAMS)}
		{* вложенное меню *}
		<ul>
		{foreach from=$child_blocks[$menu.CHILD_BLOCK_ID] item=ite id=ite_id name=mmm}
			<li {if $uri_array[2] eq $ite.PARAMS}class="here"{/if}>
				<a href="{if $ite.LINK_ADDRESS eq ''}{$menu.LINK_ADDRESS}{else}{if substr($ite.LINK_ADDRESS, 0, 1) == '#'}{$menu.LINK_ADDRESS}{$ite.LINK_ADDRESS}{else}{$ite.LINK_ADDRESS}{/if}{/if}">
					{$ite.LINK_TEXT}
				</a>
			</li>
		{/foreach}
		</ul>
	{/if}
	</li>
{/foreach}
</ul>