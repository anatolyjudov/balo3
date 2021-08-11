<form action="{$base_path}/search/found/" method="get">
<input type="text" name="search" value="{$search|escape:'html'}"><br>
<input type="hidden" name="all_areas" value="on" />
<input type="submit" value="{#lang_search_search_btn#}">
</form>