<h1>{#lang_users_admin_manage_title#}</h1>

<div class="additional">
<img src="{$pics_path}/add.png" alt="" style="position: relative; top: 3px; margin-right: 3px;"><a href="{$base_path}/admin/users/new/" class="ctrl">{#lang_users_admin_manage_addlink#}</a>
</div>

<div class="content">

{if count($users_list) > 0}
{include file="$templates_path/common/pagination.tpl"}

<table cellspacing=0 cellpadding=2 class="data" width=100%>
<tr class="head">
<td>{#lang_users_admin_manage_head_id#}</td>
<td>{#lang_users_admin_manage_head_login#}</td>
<td>{#lang_users_admin_manage_head_auth#}</td>
<td></td>
</tr>
{foreach from=$users_list item=user_info key=user_id}
<tr class="{cycle values='hl,nhl'}">
<td>{$user_id}</td>
<td>{$user_info.USER_NAME}</td>
<td>{if $user_id != -1}<small><em>
{if $user_info.AUTH_TYPE eq 'db'}db{/if}
{if $user_info.AUTH_TYPE eq 'ldap'}ldap{/if}
</em></small>{/if}
</td>
{if $user_id != -1}
<td><a href="{$base_path}/admin/users/edit/?user_id={$user_id}"><img src="{$pics_path}/edit.png" alt="{#lang_admin_edit_alt_text#}"></a><a href="{$base_path}/admin/users/delete/?user_id={$user_id}"><img src="{$pics_path}/delete.png" alt="{#lang_admin_remove_alt_text#}"></a></td>
{else}
<td colspan="2"></td>
{/if}
</tr>
{/foreach}
</table>
{else}
<p>{#lang_users_admin_manage_notfound#}</p>
{/if}

</div>