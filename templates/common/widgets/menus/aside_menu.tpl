{strip}<div class="menu"><div class="_deco1"></div>

	<ul>
		{foreach from=$menu_items item=menu name=mm}
		<li {if ($uri_array[1] eq $menu.PARAMS) or ($uri_array[1] eq '' and $menu.PARAMS eq '--main')}class="here"{/if}><a href="{$menu.LINK_ADDRESS}">{$menu.LINK_TEXT}</a></li>
		{/foreach}

	</ul>

</div>
{/strip}

{if $common_admin_mode}
{strip}<div class="menu"><div class="_deco1"></div>

	<ul>
		<li><a href="{$base_path}/admin/">Панель управления</a></li>
	</ul>

</div>
{/strip}
{/if}

