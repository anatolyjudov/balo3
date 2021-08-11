<h1>{#lang_farch_admin_edit_album_title#}</h1>
<h1 class=sub></h1>

{include file="farch/admin_farch.tpl"}

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{include file="$templates_path/farch/album_form.tpl" form_action="modify"}

</div>
