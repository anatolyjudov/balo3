<div class="entrypage">
{if $staticpage_info.HTML_PAGE_TITLE ne ""}<h1 class="entrypage_title">{$staticpage_info.HTML_PAGE_TITLE}</h1>{/if}

{if isset($staticpage_info.html_image.FOTO_ID) && ($staticpage_info.html_image.FOTO_ID != '')}
<div class="entrypage_image html_image"><img src="{$farch_foto_params.folders.link}/{$page_info.html_image.ALBUM_ID}/{$farch_foto_params.previews.itempage.prefix}{$page_info.html_image.FOTO_ID}.{$page_info.html_image.TECH_INFO.extension}" alt="{$html_title|escape:'html'}"></div>
{/if}

<div class="entrypage_text">
{$staticpage_info.HTML_CONTENT|balo3_parse_settings_values|balo3_parse_widget_calls}
</div>

{capture name="admin_place_links"}
{if count($this_user_html_actions)>0}
	<div class="admin_place">
		{if isset($this_user_html_actions.MODIFY_HTML)}<a href="{$base_path}{$staticpages_admin_branch}/edit/" onClick="edit_node({$node_id}); return false;">{#lang_html_context_menu_modify#}</a>{/if}
		{if isset($this_user_html_actions.ADD_HTML)}<a href="{$base_path}{$staticpages_admin_branch}/new/" onClick="new_node({$node_id}); return false;">{#lang_html_context_menu_add#}</a>{/if}
		{if isset($this_user_html_actions.DELETE_HTML)}<a href="{$base_path}{$staticpages_admin_branch}/delete/" onClick="delete_node({$node_id}); return false;">{#lang_html_context_menu_delete#}</a>{/if}
		{if isset($this_user_html_actions.CHANGE_METAINFO)}<a href="{$base_path}/admin/meta/?p_filter={$node_path|escape:'url'}">{#lang_html_context_menu_changemeta#}</a>{/if}
		{if isset($this_user_html_actions.access_html_tree)}
			<a href="{$base_path}{$staticpages_admin_branch}/">{#lang_html_context_menu_tree#}</a>
		{/if}
	</div>
	<form action="" method=POST id='manage_form'>
	<input type=hidden name=node id='node' value="">
	</form>

	<script type="text/javascript">

		function new_node(parent) {ldelim}
			document.getElementById('manage_form').action = '{$base_path}{$staticpages_admin_branch}/new/';
			document.getElementById('node').value = parent;
			document.getElementById('manage_form').submit();
		{rdelim}

		function edit_node(node) {ldelim}
			document.getElementById('manage_form').method = 'GET';
			document.getElementById('manage_form').action = '{$base_path}{$staticpages_admin_branch}/edit/';
			document.getElementById('node').value = node;
			document.getElementById('manage_form').submit();
		{rdelim}

		function delete_node(node) {ldelim}
			document.getElementById('manage_form').action = '{$base_path}{$staticpages_admin_branch}/delete/';
			document.getElementById('node').value = node;
			document.getElementById('manage_form').submit();
		{rdelim}

	</script>
{/if}
{/capture}


</div>

{*
{if $uri_array[0] ne "" and $uri_array[1] eq ""}
{insert name="child_pages" script="$mods_path/meta.php"}
{/if}

{if $uri_array[0] ne "" and $uri_array[1] ne ""}
{insert name="neighbour_pages" script="$mods_path/meta.php"}
{/if}

*}