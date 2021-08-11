<h1>{#lang_catalog_admin_new_section_title#}</h1>

<div class="additional">
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{include file="$templates_path/catalog/section_form.tpl" form_action="add"}

</div>
