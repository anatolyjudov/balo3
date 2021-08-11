<h1>{#lang_catalog_admin_remove_section_title#}</h1>

<div class="additional">
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{#lang_catalog_admin_remove_section_warning#}
<form action="{$base_path}/admin/catalog/sections/delete/remove/" method="POST">
<input type="hidden" name="section_id" value="{$section_info.SECTION_ID}">
<p><input type="submit" value="{#lang_admin_confirm_remove#}"> <input type="button" value="{#lang_admin_cancel_remove#}" onClick="document.location='{$base_path}/admin/catalog/sections/';">
</form>

</div>
