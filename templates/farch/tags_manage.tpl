<h1>Тэги для фотографий</h1>


<div class="additional">{insert script="$mods_path/menus_show.php" name="menus_show" menu_block_id=17 menu_block_template="ar_admin_right.tpl" menu_cache="off"}</div>

<div class="content">

{if count($tags_list) > 0}
<form action="{$admin_path}/tags/update/" method="POST">
{if isset($errmsg_modify)}<p class="errmsg">{$errmsg_modify}</p>{/if}
{if count($errmsg_modify_list)}{foreach from=$errmsg_modify_list item=errmsg_modify_item}<p class="errmsg">{$errmsg_modify_item}</p>{/foreach}{/if}
{if isset($msg_modify)}<p class="msg">{$msg_modify}</p>{/if}
<table class="ctrl">
<tr class="head">
<td>#</td>
<td>тэг</td>
<td>цвет</td>
<td>цвет фона</td>
<td>сортировочное поле</td>
<td>удалить</td>
</tr>
{foreach from=$tags_list item=fototag_info key=fototag_id}
<tr>
<td>{$fototag_id}</td>
<td><input type="text" style="width: 16em;" name="fototag_{$fototag_id}" value="{$fototag_info.FOTOTAG|escape:'html'}"></td>
<td><input type="text" style="width: 5em; font-family: Consolas, 'Courier New';" name="color_{$fototag_id}" value="{$fototag_info.COLOR|escape:'html'}" maxlength="7" onChange="document.getElementById('sample_{$fototag_id}').style.color = '#'+this.value;"></td>
<td><input type="text" style="width: 5em; font-family: Consolas, 'Courier New';" name="bgcolor_{$fototag_id}" value="{$fototag_info.BGCOLOR|escape:'html'}" maxlength="7" onChange="document.getElementById('sample_{$fototag_id}').style.backgroundColor = '#'+this.value;"> <span id="sample_{$fototag_id}" style="color: #{$fototag_info.COLOR|default:'333'}; background-color: #{$fototag_info.BGCOLOR|default:'ddd'}; padding: 2px 10px; border: 1px solid #ccc; text-decoration: underline;">link</span></td>
<td><input type="text" style="width: 2em;" name="sort_value_{$fototag_id}" value="{$fototag_info.SORT_VALUE|escape:'html'}"></td>
<td><input type="checkbox" name="delete_{$fototag_id}"></td>
</tr>
{/foreach}
<tr>
<td></td>
<td colspan="5"><input type="submit" value="сохранить изменения"></td>
</tr>
</table>
</form>
{else}
<p>Нет ни одного тэга, добавьте</p>
{/if}

<hr>

<h2>Добавить тэг</h2>

<form action="{$admin_path}/tags/add/" method="POST">
{if isset($errmsg_add)}<p class="errmsg">{$errmsg_add}</p>{/if}
{if isset($msg_add)}<p class="msg">{$msg_add}</p>{/if}

<table class="ctrl">
<tr class="head">
<td>тэг</td>
<td>цвет</td>
<td>сортировочное поле</td>
</tr>
<tr>
<td><input type="text" style="width: 16em;" name="fototag_new" value="{$posted_info.FOTOTAG|escape:'html'}"></td>
<td><input type="text" style="width: 5em; font-family: Consolas, 'Courier New';"  name="color_new" maxlength="7" value="{$posted_info.COLOR|escape:'html'}"></td>
<td><input type="text" style="width: 2em;" name="sort_value_new" value="{$posted_info.SORT_VALUE}"></td>
</tr>
<tr>
<td colspan="3"><input type="submit" value="добавить тэг"></td>
</tr>
</table>

</form>

</div>