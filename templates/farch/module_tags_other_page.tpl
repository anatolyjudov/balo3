{if count($mod_tags_list) > 0}
{foreach from=$mod_tags_list item=tag_info key=tag_id}
{if $tag_info.FOTOTAG == 'all'}
<li><a href="{$base_path}/" style="{if $tag_info.COLOR ne ""}color: #{$tag_info.COLOR};{/if} {if $tag_info.BGCOLOR ne ""}background-color: #{$tag_info.BGCOLOR};{/if}">{$tag_info.FOTOTAG}</a></li>
{else}
<li><a href="{$base_path}/#t{$tag_id}" style="{if $tag_info.COLOR ne ""}color: #{$tag_info.COLOR};{/if} {if $tag_info.BGCOLOR ne ""}background-color: #{$tag_info.BGCOLOR};{/if}">{$tag_info.FOTOTAG}</a></li>
{/if}
{/foreach}
{/if}