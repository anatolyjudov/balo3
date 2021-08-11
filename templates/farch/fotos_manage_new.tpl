<h1>Фотографии</h1>


<div class="additional">
{insert script="$mods_path/menus_show.php" name="menus_show" menu_block_id=18 menu_block_template="ar_admin_right2.tpl" menu_cache="off"}
{insert script="$mods_path/menus_show.php" name="menus_show" menu_block_id=17 menu_block_template="ar_admin_right.tpl" menu_cache="off"}
</div>

<div class="content">

<h2>Добавить фотографии</h2>
{if isset($errmsg_add)}<p class="errmsg">{$errmsg_add}</p>{/if}
{if isset($msg_add)}<p class="msg">{$msg_add}</p>{/if}
<form action="{$admin_path}/fotos/new/add/" method="POST" enctype="multipart/form-data" id="fform">
<table class="ctrl">
<tr>
<td id="input_container">
{section loop=4 name=i}
<input size="40" style="border-width: 0; border-style: solid; border-color: #fff;" type="file" id="d{$smarty.section.i.iteration}" name="f{$smarty.section.i.iteration}"><br>
{/section}
</td>
</tr>
<tr>
<td>
<input type="submit" value="Добавить">
</td>
</tr>
</table>
<input type="hidden" name="upload" value="on">
</form>

</div>