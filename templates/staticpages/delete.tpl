<h1>{#lang_html_admin_delete_title#}</h1>
<h1 class=sub>{#lang_html_admin_delete_subtitle#}</h1>

<div class="content">
<form action="../remove/" method=POST>
<table class=data cellspacing=0>
<tr class=head>
<td width=5%>{#lang_html_admin_table_head_id#}</td>
<td width=30%>{#lang_html_admin_table_head_path#}</td>
<td width=35%>{#lang_html_admin_table_head_placeholder#}</td>
<td width=15%>{#lang_html_admin_table_head_template#}</td>
<td width=15%></td>
</tr>

<tr class=new>
<td>{$node_info.NODE_ID}<input type=hidden name="node" value="{$node_info.NODE_ID}"></td>
<td style="padding-left: {$node.M_TREE_LEVEL*25}px;">{$node_info.NODE_PATH}</td>
<td>{$controller_info.PLACEHOLDER}</td>
<td>{$node_info.LAYOUT}</td>
<td></td>
</tr>
</table>

{*

<script type="text/javascript">
{literal}
function toggleinnernodes() {
	if (document.getElementById('innernodes').style.display == 'none') {
		document.getElementById('innernodes').style.display = 'block';
		document.getElementById('toggler').innerHTML = '{/literal}{#lang_html_admin_hide_inner_nodes_btn#}{literal} &lt;&lt;';
		return;
	}
	if (document.getElementById('innernodes').style.display == 'block') {
		document.getElementById('innernodes').style.display = 'none';
		document.getElementById('toggler').innerHTML = '{/literal}{#lang_html_admin_show_inner_nodes_btn#}{literal} >>';
		return;
	}
}
{/literal}
</script>


{if count($manuka_nodes)>0}
<br><a href="toggle" OnClick="toggleinnernodes(); return false;" id="toggler">{#lang_html_admin_show_inner_nodes_btn#} >></a><br><br>

<table class=data cellspacing=0 id="innernodes" style="display: none;">
<tr class=head>
<td width=5%>{#lang_html_admin_table_head_id#}</td>
<td width=35%>{#lang_html_admin_table_head_path#}</td>
<td width=20%>{#lang_html_admin_table_head_component#}</td>
<td width=20%>{#lang_html_admin_table_head_file#}</td>
<td width=20%>{#lang_html_admin_table_head_template#}</td>
</tr>
{foreach from=$manuka_nodes item=node key=m_id name=table}
{if ($node.M_COMPONENT != "html") or ($node.M_EXECUTIVE != "html.php")}
<tr class=di>
{else}
<tr class='{cycle values="nhl,hl"}'>
{/if}
<td>{$m_id}</td>
<td style="padding-left: {$node.M_TREE_LEVEL*25}px;"><a href="{$base_path}{$node.M_PATH}">{$node.M_DIRNAME}/</a></td>
<td>{$node.M_COMPONENT}<em><small>({$node.M_INSTANCE})</small></em></td>
<td>{$node.M_EXECUTIVE}</td>
<td>{$node.M_TEMPLATE}</td>
<tr>
{/foreach}
<tr class=head>
<td colspan=5 height=5></td>
</tr>

</table>
{/if}
*}

<br>
<div style="border: solid 7px #eeeeee; padding:5px; margin:0px; overflow: auto;">{include file="$templates_path/staticpages/page.tpl" staticpage_info=$page_info}</div>
<table cellspacing=10>

<tr>
<td>
<input type="submit" value="{#lang_html_admin_remove_node_btn#}">
</td>
</tr>

</table>
</form>
</div>