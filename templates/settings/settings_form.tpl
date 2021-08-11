<h1>Настройки</h1>

<div class="content">

<form action="{$settings_admin_branch}/save/" method="POST">
<input type="hidden" name="save" value="1">
<table class="ctrl">
<tr class="head">
<td></td>
<td></td>
<td></td>
</tr>
{foreach from=$settings_list key=setting item=setting_info}
<tr class="{cycle values='odd,nodd'}">
<td>{$setting_info.COMMENT}</td>
<td><input type="text" size="32" name="setting_{$setting}" value="{$setting_info.VALUE_STRING|escape:'html'}" maxlength="255"></td>
<td style="color: #ccc;">{$setting}</td>
</tr>
{/foreach}
<tr>
<td></td>
<td colspan="2"><input type="submit" value="Сохранить"></td>
</tr>
</table>
</form>


</div>