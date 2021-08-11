{foreach from=$menu_items item=menu}
<li {if $uri_array[1] eq $menu.PARAMS}class="here"{/if}><a href="{$menu.LINK_ADDRESS}">{$menu.LINK_TEXT}</a></li>
{/foreach} 