<h1>{#lang_search_results_title#}</h1>

{include file="search/search_form.tpl"}
{foreach name=outer_found from=$found item=found_item key=area}


{foreach name=found_count from=$found_count item=count key=areaname}
	{if $areaname eq $area}
	<p><b>Результаты поиска {$areaname} ({$count.count})</b><br>
	{/if}
{/foreach}


{foreach name=inner_found from=$found_item item=found_out}

	<div class="foundList">
	{if $found_out.title ne ""}
	<a href="{$found_out.path}">{$found_out.title}</a><br />{if $found_out.content ne ""}<p>{$found_out.content|truncate:250:"..."}</p>{/if}
	{else}
	{if $found_out.content ne ""}<p><a href="{$found_out.path}">{$found_out.content|truncate:250:"..."}</a></p>{/if}
	{/if}
	</div>

{/foreach}
{/foreach}

{include file="search/pagination.tpl"}
