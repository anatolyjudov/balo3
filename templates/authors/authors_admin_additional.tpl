<ul class="context_menu">
	{if $uri_array[2] ne ''}<li><a href="{$base_path}/admin/authors/" class="ctrl">{#authors_admin_manage_link#}</a></li>{/if}
	{if $uri_array[2] ne 'new'}<li><a href="{$base_path}/admin/authors/new/" class="ctrl">{#authors_admin_manage_new_link#}</a></li>{/if}
</ul>