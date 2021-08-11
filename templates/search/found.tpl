<h1>{#lang_search_results_title#}</h1>

<div class="search_content">

{assign var="select_all" value="on"}
{include file="search/search_form.tpl"}

<div class="search_results" style="padding-top: 10px;">

{if $search eq ""}
<p>{#lang_search_results_empty#}</p>

{else}


{if $total_found eq 0}
{#lang_search_results_notfound#}
{/if}

{if $on_count eq 0}
<p>{#lang_search_results_area_unknown#}</p>
{/if}


{foreach from=$found item=area_results key=area name=areas_loop}

	{if count($area_results) > 0}

		<div class="search_area_results">
		<h4>{$search_areas[$area].name_p} ({if ($found_count[$area] > $max_area_results) && ($on_count > 1)}<a href="{$base_path}/search/found/?search={$search}&{$area}=on">{$found_count[$area]}</a>{else}{$found_count[$area]}{/if})</h4>

		{foreach from=$area_results item=found_item name=items_loop}
			<div class="foundList">
			{if $found_item.title ne ""}
				<a href="{$found_item.path}">{$found_item.title}</a>{*<br />{if $found_item.content ne ""}<p>{$found_item.content|strip_tags|truncate:200:"..."}</p>{/if}*}
			{else}
				<a class="found_link" href="{$found_item.path}">{$found_item.path}</a>
				{if $found_item.content ne ""}<p><a href="{$found_item.path}">{$found_item.content|strip_tags|truncate:200:"..."}</a></p>{/if}
			{/if}
			</div>
		{/foreach}
		</div>

	{/if}

{/foreach}


{/if}	{* if $search ne '' *}

</div>

</div>