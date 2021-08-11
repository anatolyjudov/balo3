<h1>{#lang_users_login_title#}</h1>
{if !$users_logged_in}
<p>{#lang_users_login_enter_title#}</p>
<form action="{$base_path}/login/" method=POST>
<table style="border-style: none; width: 230px; font-size: 100%;">
<tr>
<td>{#lang_users_login_enter_field_login#}:</td>
<td><input id="login_input"  type="text" style="width: 120px;" name="login"></td>
</tr>
<tr>
<td>{#lang_users_login_enter_field_pass#}:</td>
<td><input type="password" style="width: 120px;" name=pass></td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" value="{#lang_users_login_enter_submit_btn#}" style="border-width: 1px; margin: 10px 0 0;">
</td>
</tr>
</table>
</form>
<script language="javascript">
document.getElementById('login_input').focus();
</script>

{else}
<p>{#lang_users_login_enter_you_are_now#}{$users_username}.</p>
<form action="{$base_path}/logout/" method=POST>
<input type="submit" value="{#lang_users_login_enter_logout_btn#}">
</form>
{/if}