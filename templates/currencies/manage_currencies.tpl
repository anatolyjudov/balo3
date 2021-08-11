<h1>Управление валютами</h1>

<div class="content">

{if $currencies_updated == true}
<p class="msg" style="color: green;">Изменения сохранены</p>
{/if}

{if count($currencies) > 0}
<form action="./" method="POST"><input type="hidden" name="send" value="1">
<table cellspacing=0 cellpadding=2 class="ctrl" width=100%>
<tr class="head">
<td>№</td>
<td>Валюта</td>
<td>Обозначение</td>
<td>Курс</td>
</tr>
{foreach from=$currencies item=cur_info key=cur_id}
<tr class="{cycle values='odd,nodd'}">
<td>{$cur_id}</td>
<td>{$cur_info.NAME}</td>
<td>{$cur_info.SIGN}</td>
<td><input type="text" name="cur{$cur_id}" value="{$cur_info.EQUAL|escape:'html'}"></td>
</tr>
{/foreach}
<tr>
<td colspan="3"></td>
<td><input type="submit" value="Сохранить"></td>
</tr>
</table>
</form>
{else}
<p>Нечего показать</p>
{/if}

</div>