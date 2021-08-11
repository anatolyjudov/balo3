<h1>{#lang_html_admin_add_title#}</h1>

<div class="content">
<form action="../add/" method=POST>
<table class=data cellspacing=0>
<tr class=head>
<td width=5%>{#lang_html_admin_table_head_id#}</td>
<td width=30%>{#lang_html_admin_table_head_path#}</td>
<td width=35%>{#lang_html_admin_table_head_placeholder#}</td>
<td width=15%>{#lang_html_admin_table_head_template#}</td>
<td width=15%></td>
</tr>

{foreach from=$manuka_nodes item=node key=node_id name=table}
{if ($node.CONTROLLER_FAMILY ne "staticpages") or ($node.CONTROLLER != "page")}
<tr class=di>
{else}
<tr class='{cycle values="nhl,hl"}'>
{/if}
<td>{$node_id}</td>
<td style="padding-left: {$node.M_TREE_LEVEL*25}px;"><a href="{$base_path}{$node.M_PATH}">{$node.NODE_PATH}</a></td>
<td>{$node.PLACEHOLDER}</td>
<td>{$node.LAYOUT}</td>
<td></td>
<tr>
{if $node_id == $parent_node}
<tr class=new>
<td><input type="hidden" name="parent_node" value="{$parent_node}"><input type=hidden name="controller_family" value="staticpages"><input type=hidden name="controller" value="page"></td>
<td>{$node.NODE_PATH}<input size=9 type=text value="" name="dirname">/</td>
<td><input size=9 type=text name="placeholder" value="{$node.PLACEHOLDER|escape:'html'}"></td>
<td><input size=9 type=text name="layout" value="{$node.LAYOUT|escape:'html'}"></td>
<td><input type="submit" value="{#lang_admin_add_btn_text#}"></td>
</tr>
{/if}
{/foreach}
<tr class=head>
<td colspan=6 height=5></td>
</tr>

</table>
</form>
</div>