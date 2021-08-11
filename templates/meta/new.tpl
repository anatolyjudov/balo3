<h1>{#lang_meta_admin_new_title#}</h1>
<h1 class=sub></h1>

<div class="additional">{include file="$templates_path/meta/meta_adm_menu.tpl"}</div>

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{include file="$templates_path/meta/meta_form.tpl" form_action="add"}

</div>