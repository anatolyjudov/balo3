<form method="post" 
{if $form_action eq "register"}
action="{$base_path}/login/register/proceed/"
{/if}
{if $form_action eq "modify"}
action="{$base_path}/login/register/modify/"
{/if}
>

<table cellspacing=5 cellpadding=5 class="userform">

{*{if $form_action eq "register"}
<tr>
<td valign="top">Логин</td>
<td>
{if $user_info.AUTH_TYPE eq 'ldap'}{$user_info.USER_NAME}{/if}
{if $user_info.AUTH_TYPE eq 'db'}
{if $form_action eq "modify"}{$user_info.USER_NAME}
{else}<input type="text" style="width: 450px; font-size: 18px;" name="user_name" maxlength="24" value="{$user_info.USER_NAME|escape:'html'}">{/if}
{/if}
</td>
</tr>
{/if}

{if $form_action eq "modify"}
<tr>
<td valign="top">Логин</td>
<td>
{$user_info.USER_NAME}
</td>
</tr>
{/if}

<tr>
<td valign="top">Никнейм</td>
<td>
<input type="text" style="width: 450px; font-size: 18px;" name="user_nickname" maxlength="255" value="{$user_info.USER_NICKNAME|escape:'html'}">
</td>
</tr>

*}
{if $form_action eq "register"}
<tr>
<td valign="top">Адрес e-mail</td>
<td>
<input type="text" style="width: 450px; font-size: 18px;"  name="user_email" maxlength="24" value="{$user_info.USER_EMAIL|escape:'html'}">
</td>
</tr>
{/if}

{if $form_action eq "modify"}
<tr>
<td valign="top">Адрес e-mail</td>
<td>
{$user_info.USER_EMAIL|escape:'html'}
</td>
</tr>
{/if}

{if $user_info.AUTH_TYPE eq 'db'}
<tr>
<td valign="top">{if $form_action eq "modify"}Новый пароль{else}Пароль{/if}</td>
<td>
<input type="password" style="width: 250px;" name="user_password" value="">
</td>
</tr>
<tr>
<td valign="top">Повтор пароля</td>
<td>
<input type="password" style="width: 250px;" name="user_password2" value="">
</td>
</tr>
{/if}

<tr>
<td colspan="2" valign="top"><b>информация о вас</b></td>
</tr>

<tr>
<td valign="top">ФИО</td>
<td>
<input type="text" style="width: 450px;" name="user_real_name" maxlength="24" value="{$user_info.USER_REAL_NAME|escape:'html'}">
</td>
</tr>

<tr>
<td valign="top">Адрес доставки</td>
<td>
<textarea style="width: 450px;" name="user_address" maxlength="256">{$user_info.USER_ADDRESS|escape:'html'}</textarea>
</td>
</tr>

<tr>
<td valign="top">Номер телефона</td>
<td>
<input type="text" style="width: 150px;" name="user_phone" maxlength="24" value="{$user_info.USER_PHONE|escape:'html'}">
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="отправить"></td>
</tr>

</table>

</form>