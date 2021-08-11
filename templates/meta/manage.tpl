<h1>{#lang_meta_admin_manage_title#}</h1>
<h1 class=sub>{#lang_meta_admin_manage_subtitle#}</h1>

<div class="additional">{include file="$templates_path/meta/meta_adm_menu.tpl"}</div>

<div class="content">

<form action="./" method="GET">
<table cellspacing=0 class=ctrl>
<tr>
<td>{#lang_meta_admin_manage_filter_title#}:</td>
<td><input name="p_filter" value="{$p_filter}" type="text" size="20"></td>
<td><input type="submit" value="{#lang_meta_admin_manage_filter_apply#}"></td>
</tr>
</table>
</form>

{if count($meta_info) > 0}

<table class=data cellspacing=0 cellpadding=4 width="100%">
<tr class=head>
<td colspan="6">{#lang_meta_admin_manage_head_path#}</td>
</tr>
<tr class=head>
<td width="4%"></td>
<td width="23%">{#lang_meta_admin_manage_head_link#}</td>
<td width="23%">{#lang_meta_admin_manage_head_title#}</td>
<td width="23%">{#lang_meta_admin_manage_head_keywords#}</td>
<td width="23%">{#lang_meta_admin_manage_head_description#}</td>
<td width="4%"></td>
</tr>

{foreach from=$meta_info item=meta_row key=meta_id}
<tr class="{cycle values='nhl,hl' advance=false}">
<td colspan="4"><input type="hidden" name="{$meta_id}" value="{$meta_row.URI}"><a href="{$base_path}{$meta_row.URI}">{$meta_row.URI}</a></td>
<td>{if $meta_row.ERROR_STATE_LOCAL ne "none"}<small><i>{#lang_meta_admin_manage_local#}: {$meta_row.ERROR_STATE_LOCAL}{if $meta_row.ERROR_STATE_INET ne "none"},{/if}</i></small>{/if} {if $meta_row.ERROR_STATE_INET ne "none"}<small><i>{#lang_meta_admin_manage_outside#}: {$meta_row.ERROR_STATE_INET}</i></small>{/if}</td>
<td rowspan="2"><a href="{$base_path}/admin/meta/edit/?meta_id={$meta_id}"><img src="{$pics_path}/edit.png" style="border-style: none;"></a><a href="{$base_path}/admin/meta/delete/?meta_id={$meta_id}"><img src="{$pics_path}/delete.png" style="border-style: none;"></a></td>
</tr>

<tr class="{cycle values='nhl,hl'}">
<td></td>
<td valign=top>{$meta_row.INNER_TITLE|default:'-'}</td>
<td valign=top>{$meta_row.TITLE|default:'-'}</td>
<td valign=top>{$meta_row.KEYWORDS|default:'-'}</textarea></td>
<td valign=top>{$meta_row.DESCRIPTION|default:'-'}</textarea></td>
</tr>
{/foreach}

</table>

{else}
<p>{#lang_meta_admin_manage_notfound#}</p>
{/if}


</div>