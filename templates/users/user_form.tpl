<form method="POST" 
{if $form_action eq "add"}
action="{$base_path}/admin/users/new/add/"
{else}
action="{$base_path}/admin/users/edit/modify/"
{/if}
>
{if $form_action ne "add"}
<input type="hidden" name="user_id" value="{$user_info.USER_ID}">
{/if}

<table class="ctrl" cellspacing=0 cellpadding=4>

<tr class="striked">
<td valign=top><b>{#lang_users_admin_form_field_login#}</b></td>
<td>
<input type="text" style="width: 450px; font-size: 140%;" name="user_name" maxlength=128 value="{$user_info.USER_NAME|escape:'html'}">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_users_admin_form_field_auth#}</b></td>
<td>
<select name="auth_type">
<option value="db" {if $user_info.AUTH_TYPE eq 'db'}selected{/if}>db</option>
{*<option value="ldap" {if $user_info.AUTH_TYPE eq 'ldap'}selected{/if}>ldap</option>*}
</select>
</td>
</tr>

<tr >
<td valign=top><b>{#lang_users_admin_form_field_newpass#}</b>{*<br><small>{#lang_users_admin_form_field_newpass_note#}</small>*}</td>
<td>
<input type="text" style="width: 250px;" name="user_password" value="">
</td>
</tr>

<tr class="head">
<td colspan="2" valign=top>{#lang_users_admin_form_userinfo#}</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_users_admin_form_field_nickname#}</b></td>
<td>
<input type="text" style="width: 450px; font-size: 140%;" name="user_nickname" maxlength=24 value="{$user_info.USER_NICKNAME|escape:'html'}">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_users_admin_form_field_realname#}</b></td>
<td>
<input type="text" style="width: 450px;" name="user_real_name" maxlength=24 value="{$user_info.USER_REAL_NAME|escape:'html'}">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_users_admin_form_field_email#}</b></td>
<td>
<input type="text" style="width: 250px;" name="user_email" maxlength=24 value="{$user_info.USER_EMAIL|escape:'html'}">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_users_admin_form_field_icq#}</b></td>
<td>
<input type="text" style="width: 150px;" name="user_icq" maxlength=24 value="{$user_info.USER_ICQ|escape:'html'}">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_users_admin_form_field_active#}</b></td>
<td>
<input type="radio" id="activated" name="activated" value="1" {if $user_info.ACTIVE == 1}checked{/if}><label for="activated">{#lang_users_admin_form_field_active_yes#}</label>
<input type="radio" id="noactivated" name="activated" value="0" {if $user_info.ACTIVE == 0 or !$user_info.ACTIVE}checked{/if}><label for="noactivated">{#lang_users_admin_form_field_active_no#}</label>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="{#lang_admin_save_btn_text#}"></td>
</tr>

</table>

</form>