<form action="{$base_path}/search/found/" method="get" class="searchForm">
<div class="request"><input type="text" style="width: 300px;" name="search" value="{$search|escape:'html'}" class="request"> <input type="submit" value="{#lang_search_search_btn#}" class="submit button grey"></div>
{if $select_all eq 'on'}
<input type="hidden" name="all_areas" value="on" />
{else}
{foreach name=areas from=$search_areas item=area key=areaname}
<input type="checkbox" name="{$areaname}" id="{$areaname}" {if $checked_areas[$areaname] == "on" or $select_all == "on"}checked{/if} class="area">
<label for={$areaname}>{$area.name_p}</label><br>
{/foreach}
{/if}

</form>