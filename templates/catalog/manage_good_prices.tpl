<h1>Цены для лота <em>{$good_info.TITLE}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog_good.tpl"}
{include file="catalog/admin_catalog_section.tpl"}
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

<p style="margin-bottom: 21px;">Вы можете добавить разные цены в разных валютах, либо указать только одну в любой валюте.<Br>Если ни одной цены для лота не указано, на сайте будет отображаться "цена по запросу".</p>

{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}
{if isset($msg)}<p class="msg">{$msg}</p>{/if}


<form action="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/prices/" method="POST">
<input type="hidden" name="send" value="1">
<table class="ctrl" >
<tr class="head">
<td style="width: 20px;"></td>
<td>Цена</td>
<td>Валюта</td>
<td>Тип</td>
<td>Удалить</td>
</tr>

{if count($good_prices_list) > 0}
{foreach from=$good_prices_list item=price_info key=price_id}
<tr class="{cycle values='odd,nodd'}">
<td>{*{$price_id}*}<input type="hidden" name="{$price_id}" value="{$price_id}"></td>
<td><input style="width: 100px; font-size: 140%;" name="price_{$price_id}" type="text" value="{$price_info.PRICE|escape:'html'}"></td>
<td>
<select style="font-size: 140%;"  name="currency_id_{$price_id}">
{foreach from=$currencies key=currency_id item=currency}
<option value="{$currency_id}" {if $price_info.CURRENCY_ID == $currency_id}selected{/if}>{$currency.SIGN}</option>
{/foreach}
</select>
</td>
<td>
	{if $price_info.TYPE eq 'simple'}обычная{/if}
	{if $price_info.TYPE eq 'hidden'}скрытая{/if}
</td>
<td><input type="checkbox" name="del_{$price_id}"></td>
</tr>
{/foreach}
{/if}

<tr style="border-width: 1px 0 0; border-style: solid; border-color: #ccc;">
<td></td>
<td colspan="4">Добавить цену</td>
</tr>
<tr>
<td></td>
<td><input style="width: 100px; font-size: 140%;"  name="price_new" type="text" value="{$new_price_info.PRICE|escape:'html'}"></td>
<td>
<select style="font-size: 140%;" name="currency_id_new">
{foreach from=$currencies key=currency_id item=currency}
<option value="{$currency_id}" {if $new_price_info.CURRENCY_ID == $currency_id}selected{/if}>{$currency.SIGN}</option>
{/foreach}
</select>
</td>
<td>
	<select name="type_new">
		<option value="simple">Обычная цена</option>
		<option value="hidden">Скрытая цена</option>
	</select>
</td>
<td></td>
</tr>
<td></td>
<td colspan="4"><input type="submit" value="Сохранить изменения"></td>
</tr>

</table>
</form>


</div>