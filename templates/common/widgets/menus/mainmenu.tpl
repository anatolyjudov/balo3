{foreach from=$menu_items item=menu}
<li {if $uri_array[0] eq $menu.PARAMS}class="context" {assign var="common_context_menu" value=$menu.CHILD_BLOCK_ID}{/if}><a href="{$menu.LINK_ADDRESS}">{$menu.LINK_TEXT}</a></li>
{/foreach}