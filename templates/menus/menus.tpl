<h1>{#lang_menus_admin_menus_title#}</h1>

{if $superuser eq true}
<div class="additional">
<img src="{$base_path}/images/add.png" alt="" style="position: relative; top: 3px; margin-right: 3px;"><a href="./new/">{#lang_menus_admin_menus_add_menublock_link#}</a>
</div>
{/if}

<div class="content">
{if $menu_blocks eq 0}
{#lang_menus_admin_menus_no_menus#}
{else}
<table class="data" cellspacing="0">
<tr class="head">
<td>{#lang_menus_admin_table_head_id#}</td>
<td>{#lang_menus_admin_table_head_path#}</td>
<td>{#lang_menus_admin_table_head_comment#}</td>
<td></td>
<td></td>
</tr>
{foreach from=$menu_blocks item=block}
<tr class={cycle values="hl, nhl"}>
<td>{$block.ID}</td>
<td>{$block.PATH}</td>
<td>{$block.COMMENT}</td>
<td><a href="manage/?id={$block.ID}">{#lang_menus_admin_table_head_elements#}</a></td>
<td>
<a href="edit/?id={$block.ID}"><img src="{$base_path}/images/edit.png" alt="{#lang_admin_edit_alt_text#}"></a>
{if $superuser eq true}<a href="delete/?id={$block.ID}"><img src="{$base_path}/images/delete.png" alt="{#lang_admin_remove_alt_text#}"></a>{/if}</td>
</tr>
{/foreach}
</table>
{/if}
</div>