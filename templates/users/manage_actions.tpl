<h1>Права доступа</h1>
<h1 class="sub">управление правами групп</h1>

<div class="content">

<form action="{$base_path}/admin/actions/" method=GET>
<table cellspacing=20 cellpadding=5 style="background-color: #eeeeee;">
<tr><td>
роль: <select name="role_id">
{foreach from=$roles_list item=role}
{if $role_id == $role.ROLE_ID}
<option value="{$role.ROLE_ID}" selected>{$role.ROLE_NAME}</option>
{else}
<option value="{$role.ROLE_ID}">{$role.ROLE_NAME}</option>
{/if}
{/foreach}
</select>
</td><td>
действие:
<select name="action_id">
{foreach from=$actions_list item=action}
{if $action_id == $action.ACTION_ID}
<option value="{$action.ACTION_ID}" selected>{$action.ACTION_NAME}</option>
{else}
<option value="{$action.ACTION_ID}">{$action.ACTION_NAME}</option>
{/if}
{/foreach}
</select>
</td>
<td>
<input type="submit" value="показать">
</td>
</tr>
</table>
</form>

<b>ветки:</b><br>

<form action="{$base_path}/admin/actions/update/" method=POST>
<input type=hidden value="{$role_id}" name="role_id">
<input type=hidden value="{$action_id}" name="action_id">
<table cellspacing=0 cellpadding=2 class="ctrl">
<tr class=head>
<td>#</td>
<td>путь</td>
<td>запрет</td>
<td>действие</td>
</tr>
{foreach from=$branches item=branch name=brs}
<tr class="{cycle values='odd,nodd'}">
<td>{$smarty.foreach.brs.iteration}</td>
<td><input type="text" size="32" value="{$branch.AFFECTED_BRANCH}" name="br_{$smarty.foreach.brs.iteration}"></td>
<td>
{if $branch.RIGHT_TYPE == 0}
<input type="checkbox" name="type_{$smarty.foreach.brs.iteration}" checked>
{else}
<input type="checkbox" name="type_{$smarty.foreach.brs.iteration}">
{/if}
</td>
<td>удал. <input type="checkbox" name="del_{$smarty.foreach.brs.iteration}"></td>
</tr>
{/foreach}
<tr>
<td></td>
<td><input type="text" size="32" value="" name="new_branch" autocomplete="off"></td>
<td ><input type="checkbox" name="type_new_branch"  autocomplete="off"></td>
<td></td>
</tr>
<tr>
<td style="border-style: solid; border-color: #eeeeee; border-width: 4px 0px 0px;" colspan=4><input type="submit" value="отправить"></td>
</tr>
</table>
</form>

</div>