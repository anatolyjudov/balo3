<h1>{#lang_html_admin_tree_title#}</h1>
<h1 class=sub>{#lang_html_admin_tree_subtitle#}</h1>

<div class="additional">
	<form action="{$staticpages_admin_branch}/" method="GET">
	<p style="font-weight: bold;">{#lang_html_admin_tree_filter_title#}</p>
	<p style="margin: 10px 0 5px;">{#lang_html_admin_tree_filter_path#}: <input type="text" name="path_filter" value="{$path_filter|escape:'html'}" size=24 style=""></p>
	<input name="show_admin" type="checkbox" {if $show_admin}checked{/if}> {#lang_html_admin_tree_filter_branches#}<br>
	<input name="not_htmls" type="checkbox" {if $not_htmls}checked{/if}> {#lang_html_admin_tree_filter_nonhtml#}<br>
	<input name="show_full_info" type="checkbox" {if $show_full_info}checked{/if}> {#lang_html_admin_tree_filter_info#}<br>
	<input type="submit" value="{#lang_admin_apply_btn_text#}" style="margin: 10px 0 0;">
	</p>
	</form>
</div>

<div class="content">

	<form action="" method=POST id='manage_form'>
	<input type=hidden name=node id='node' value="">
	{if $show_admin}<input name="show_admin" type="hidden" value="on">{/if}
	{if $not_htmls}<input name="not_htmls" type="hidden" value="on">{/if}
	</form>

	<script language="javascript">
	{literal}
		function new_node(parent) {
			document.getElementById('manage_form').action = 'new/';
			document.getElementById('node').value = parent;
			document.getElementById('manage_form').submit();
		}
		function edit_node(node) {
			document.getElementById('manage_form').method = 'GET';
			document.getElementById('manage_form').action = 'edit/';
			document.getElementById('node').value = node;
			document.getElementById('manage_form').submit();
		}
		function delete_node(node) {
			document.getElementById('manage_form').action = 'delete/';
			document.getElementById('node').value = node;
			document.getElementById('manage_form').submit();
		}
	{/literal}
	</script>

<table class="data">

<tr class=head>
{if $show_full_info}
<td width=5%>{#lang_html_admin_table_head_id#}</td>
<td width=25%>{#lang_html_admin_table_head_path#}</td>
<td width=20%>{#lang_html_admin_table_head_component#}</td>
<td width=20%>{#lang_html_admin_table_head_script#}</td>
<td width=20%>{#lang_html_admin_table_head_template#}</td>
<td width=10%></td>
{else}
<td width=5%>{#lang_html_admin_table_head_id#}</td>
<td width=25%>{#lang_html_admin_table_head_path#}</td>
<td width=40%>{#lang_html_admin_table_head_title#}</td>
<td width=20%>{#lang_html_admin_table_head_inner_title#}</td>
<td width=10%></td>
{/if}
</tr>

{foreach from=$manuka_nodes item=node key=node_id name=table}
{if ($node.CONTROLLER_FAMILY ne "staticpages") or ($node.CONTROLLER ne "page")}
<tr class=di>
{else}
<tr class='{cycle values="hl,nhl"}'>
{/if}
<td>{$node_id}</td>
<td style="padding-left: {$node.M_TREE_LEVEL*25}px;">
{if $node.user_rights.ACCESS_NODE == 1}
<a href="{$base_path}{$node.NODE_PATH}">{$node.NODE_PATH}</a>
{else}
{$node.NODE_PATH}
{/if}
</td>
{if $show_full_info}
<td>{$node.CONTROLLER_FAMILY}{if $node.CONTROLLER_ARGS ne ""} <em><small>({$node.CONTROLLER_ARGS})</small></em>{/if}</td>
<td>{$node.CONTROLLER}</td>
<td>{$node.LAYOUT}</td>
{else}
<td>{$node.TITLE}</td>
<td>{$node.INNER_TITLE}</td>
{/if}
<td>
<div class="actions">
{if $node.user_rights.ADD_HTML == 1}<img src="{$pics_path}/add.png" border=0 alt="{#lang_admin_add_alt_text#}" onLoad="this.style.cursor='hand';" onClick="new_node({$node_id});">{/if}
{if ($node.CONTROLLER_FAMILY eq "staticpages") and ($node.CONTROLLER eq "page")}
{if $node.user_rights.MODIFY_HTML == 1}<img src="{$pics_path}/edit.png" border=0 alt="{#lang_admin_edit_alt_text#}" onLoad="this.style.cursor='hand';" onClick="edit_node({$node_id});">{/if}
{if $node.user_rights.DELETE_HTML == 1}<img src="{$pics_path}/delete.png" border=0 alt="{#lang_admin_remove_alt_text#}" onLoad="this.style.cursor='hand';" onClick="delete_node({$node_id});">{/if}
{/if}
</div>
</td>
<tr>
{/foreach}
</table>

</div>