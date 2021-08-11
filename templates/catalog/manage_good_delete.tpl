<h1>Лот <em>{$good_info.TITLE}</em></h1>

<div class="additional">
{include file="catalog/admin_catalog_good.tpl"}
{include file="catalog/admin_catalog_section.tpl"}
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if isset($errmsg)}<p style="color: red;">{$errmsg}</p>{/if}
{if isset($msg)}<p class="msg">{$msg}</p>{/if}

<p>Вы собираетесь удалить лот! Вся информация будет удалена, восстановленить её будет невозможно.</p>

<p>Действительно удалить?</p>

<form action="{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/delete/" method="POST">
<input type="hidden" name="confirm" value="on">
<p><input type="submit" value="{#lang_admin_confirm_remove#}"> <input type="button" value="{#lang_admin_cancel_remove#}" onClick="document.location='{$base_path}/admin/catalog/sections/{$section_id}/goods/manage/{$good_id}/';">
</form>

</div>