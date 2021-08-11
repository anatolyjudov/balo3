<h1>{#lang_menus_admin_menus_elements_title#}</h1>

<div class="content" style="width: 90%;">
<p class="data" style="margin-bottom: 20px;"><a href="../">&larr; {#lang_menus_admin_menus_elements_return_link#}</a></p>

{if $error eq "deny"}
{#lang_menus_admin_menus_elements_ad#}
{else}

<table class="data">
<tr class="head">
<td>{#lang_menus_admin_menus_elements_id#}</td>
<td>{#lang_menus_admin_menus_elements_link#}</td>
<td>{#lang_menus_admin_menus_elements_url#}</td>
<td>{#lang_menus_admin_menus_elements_addit#}</td>
<td>{#lang_menus_admin_menus_elements_submenu#}</td>
<td>{#lang_menus_admin_menus_elements_sort#}</td>
<td>{#lang_menus_admin_menus_elements_visible#}</td>
<td>{#lang_menus_admin_menus_elements_delete#}</td>
</tr>

{if $menu_items eq 0}
<tr><td colspan="8">{#lang_menus_admin_menus_elements_empty#}</td></tr>
{else}

<form action="modify/" method="post">

{foreach from=$menu_items item=item}
<tr class={cycle values="hl, hl"}>
<td>{$item.ID}</td>
<td><input type="text" class="multilang" name="{$item.ID},linktext" value="{$item.LINK_TEXT}" size="42"></td>
<td><input type="text" name="{$item.ID},linkaddr" value="{$item.LINK_ADDRESS}" size="22"></td>
<td><input type="text" name="{$item.ID},params" value="{$item.PARAMS}" size="22"></td>
<td>
<select name="{$item.ID},child_block_id" style="width: 150px;">
<option value=0>--</option>
{foreach from=$menus_blocks item=block}
<option value="{$block.ID}" {if $item.CHILD_BLOCK_ID == $block.ID}selected{/if}>{$block.TITLE} ({$block.COMMENT})</option>
{/foreach}
</select>
</td>
<td><input type="text" name="{$item.ID},sort" value="{$item.SORT}" size="3"></td>

<td>
<select name="{$item.ID},visible">
{foreach from=$visibility item=visible}
<option value="{$visible}" {if $item.VISIBLE eq $visible}selected{/if}>{$visible}
{/foreach}
</select>
</td>

<td><input type="checkbox" name="{$item.ID},delete"></td>
</tr>
{/foreach}

<tr>
<td></td>
<td colspan="7"><input type="submit" value="{#lang_admin_savechanges_btn_text#}"></td>
</tr>

</form>
{/if}

</table>

<br><br>
<h2>{#lang_menus_admin_elements_add_title#}</h2>
<form action="add/" method="post">
<input type="hidden" name="block_id" value="{$block_id}">
<table class="ctrl" cellspacing="0">

<tr class="striked" valign="top">
<td><b>{#lang_menus_admin_elements_field_text#}</b></td>
<td>
<input class="multilang" type="text" name="link_text" size=32>
</td>
</tr>

<tr class="striked" valign="top">
<td><b>{#lang_menus_admin_elements_field_url#}</b></td>
<td>
<input type="text" name="link_address" size=32>
</td>
</tr>

<tr class="striked" valign="top">
<td><b>{#lang_menus_admin_elements_field_addit#}</b><br><small>{#lang_menus_admin_elements_field_addit_note#}</small></td>
<td>
<input type="text" name="params" size=32>
</td>
</tr>

<tr class="striked" valign="top">
<td><b>{#lang_menus_admin_elements_field_sort#}</b></td>
<td>
<input type="text" name="sort" size=6>
</td>
</tr>

<tr class="striked" valign="top">
<td><b>{#lang_menus_admin_elements_field_visible#}</b></td>
<td>
<select name="visible">
{foreach from=$visibility item=visible}
<option value="{$visible}">{$visible}
{/foreach}
</select>
</td>
</tr>

<tr class="head">
<td colspan="2"></td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="{#lang_admin_add_btn_text#}"></td>
</tr>
</table>
</form>

{/if}

</div>