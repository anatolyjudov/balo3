<h1>{#lang_users_admin_edit_title#}</h1>

<div class="additional"></div>

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{include file="$templates_path/users/user_form.tpl" form_action="modify"}

</div>