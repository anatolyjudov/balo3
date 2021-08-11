<h1>{#lang_users_admin_delete_title#}</h1>

<div class="additional"></div>

<div class="content">

<p>{#lang_users_admin_delete_confirm#} {$user_info.USER_NAME}?<br></p>

<form action="{$base_path}/admin/users/delete/remove/" method="POST"><input type="hidden" name="user_id" value="{$user_info.USER_ID}">
<table class="ctrl" cellspacing=10>
<tr>
<td><input type="submit" value="{#lang_admin_confirm_remove#}"></td>

<td><input type="button" value="{#lang_admin_cancel_remove#}" onLoad="this.focus();" onClick="history.go(-1);"></td>
</tr>
</table>
</form>

<p>{#lang_users_admin_delete_warning#}</p>

</div>