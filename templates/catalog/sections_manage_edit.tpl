<h1>{#lang_catalog_admin_edit_section_title#}</h1>
<h1 class=sub></h1>

<div class="additional">
{include file="catalog/admin_catalog.tpl"}
</div>

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{include file="$templates_path/catalog/section_form.tpl" form_action="modify"}

</div>
