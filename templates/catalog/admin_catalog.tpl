<h4>Каталог</h4>
<ul class="context_menu">
	<li><a href="{$base_path}/admin/catalog/sections/" class="ctrl">{#lang_catalog_admin_manage_sections_link#}</a></li>
	<li><a href="{$base_path}/admin/catalog/sections/new/" class="ctrl">{#lang_catalog_admin_new_section_link#}</a></li>
</ul>

{* поиск по разделу отключим пока, пусть везде будет поиск по всему *}
{if false and $uri_array[2] eq 'sections' and $uri_array[3] != ""}
	<h4>Найти в разделе</h4>
	<form action="./" method="GET">
	<input type="text" name="search_str" value="{$catalog_search_str|escape:'html'}">
	<input type="submit" value="Найти">
	</form>
{else}
	<h4>Найти лот</h4>
	<form action="{$base_path}/admin/catalog/goods/" method="GET">
	<input type="text" name="search_str" value="{$catalog_search_str|escape:'html'}">
	<input type="submit" value="Найти">
	</form>
{/if}