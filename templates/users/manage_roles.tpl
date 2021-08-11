<script language="javascript">

function switch_role_pan(id) {ldelim}
	if (document.getElementById(id).style.display == 'none') {ldelim}
		document.getElementById(id).style.display = 'block';
		document.getElementById('t'+id).innerHTML = '&lt;&lt;&lt;';
	{rdelim} else {ldelim}
		document.getElementById(id).style.display = 'none';
		document.getElementById('t'+id).innerHTML = '&gt;&gt;&gt;';
	{rdelim}
{rdelim}

function deassign(role, user) {ldelim}
	document.getElementById('rolef').value = role;
	document.getElementById('userf').value = user;
	document.getElementById('deassign').submit();
{rdelim}

function delete_role(role) {ldelim}
	document.getElementById('del_role_id').value = role;
	document.getElementById('delete_role').submit();
{rdelim}

</script>
<h1>Роли на сайте</h1>
<h1 class="sub">группы и входящие в них пользователи</h1>
<div class="content">
{if count($roles_list)>0}
<form action="{$base_path}/admin/roles/deassign/" method=POST id="deassign">
<input type="hidden" id="rolef" name="role_id" value="0">
<input type="hidden" id="userf" name="user_id" value="0">
</form>
<form action="{$base_path}/admin/roles/delete/" method=POST id="delete_role">
<input type="hidden" id="del_role_id" name="role_id" value="0">
</form>

<table cellspacing=1 cellpadding=0>
{foreach from=$roles_list item=role}
<tr>
<td><small>{$role.ROLE_ID}.</small></td>
<td>
<b style="text-decoration: underline;">{$role.ROLE_NAME}</b> <a style="font-size: 70%; text-decoration: none;" href="javascript: delete_role({$role.ROLE_ID});" id="del_{$role.ROLE_ID}">x</a>
</td></tr>
<tr><td style="padding-left: 20px;" colspan=2>
	{foreach from=$role.users item=user}
	{$user.USER_NAME} <a style="font-size: 70%; text-decoration: none;" href="javascript: deassign({$role.ROLE_ID},{$user.USER_ID});" onClick="">x</a>,
	{/foreach}
 <a style="font-size: 70%; text-decoration: none;" href="{$base_path}/admin/roles/deassign/" onClick="switch_role_pan({$role.ROLE_ID}); return false;" id="t{$role.ROLE_ID}">>>></a><br>
<div id="{$role.ROLE_ID}" style="display: none; background-color: #eeeeee; margin: 2px; padding: 6px;"><form action="{$base_path}/admin/roles/assign/" method=POST><span style="font-size: 80%;">назначить юзера:</span><br> <input type="text" name="username" size="12" style="font-size: 70%;"> <input type="submit" style="font-size: 70%;" value="+"> <input type="hidden" name="role_id" value="{$role.ROLE_ID}"></form>
<form action="{$base_path}/admin/roles/modify/" method=POST id="modify_roles">
<span style="font-size: 80%;">переименовать роль:</span><input type=hidden name="role_id" value="{$role.ROLE_ID}"><br>
<input type="text" size="12" style="font-size: 70%;" value="{$role.ROLE_NAME}" name="role_name"> <input type="submit" style="font-size: 70%;" value=">">
</form>
</div><br>
</td></tr>
{/foreach}
</table>


{else}
нет ролей
{/if}

<form action="{$base_path}/admin/roles/add/" method=POST>
<h4>Создать роль</h4>
<input type="text" name="role_name" value="{$post_info.ROLE_NAME}" size=32> <input type="submit" value="создать">
</form>
</div>