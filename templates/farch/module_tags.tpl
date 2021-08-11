{if count($mod_tags_list) > 0}
{*<li><a href="{$base_path}/" onClick="showfotos('all'); return false;">all</a></li>*}
{foreach from=$mod_tags_list item=tag_info key=tag_id}
{if $tag_info.FOTOTAG == 'all'}
<li><a href="{$base_path}/"  onClick="showfotos('all'); return false;" style="{if $tag_info.COLOR ne ""}color: #{$tag_info.COLOR};{/if} {if $tag_info.BGCOLOR ne ""}background-color: #{$tag_info.BGCOLOR};{/if}">{$tag_info.FOTOTAG}</a></li>
{else}
<li><a href="{$base_path}/#t{$tag_id}" onClick="showfotos('tag_{$tag_id}'); return false;" style="{if $tag_info.COLOR ne ""}color: #{$tag_info.COLOR};{/if} {if $tag_info.BGCOLOR ne ""}background-color: #{$tag_info.BGCOLOR};{/if}">{$tag_info.FOTOTAG}</a></li>
{/if}
{/foreach}
{/if}
{*

<div class="left_menu">
<ul>
<li><a href="" onClick="showfotos('all'); return false;">all</a></li>
<li><a href="" onClick="showfotos('tag'); return false;">tag</a></li>
<li><a href="" onClick="showfotos('feature'); return false;">feature</a></li>
<li><a href="" onClick="showfotos('test'); return false;">test</a></li>
</ul>
</div>

*}