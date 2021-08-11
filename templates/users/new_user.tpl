<h1>{#lang_users_admin_new_title#}</h1>
<h1 class=sub></h1>

<div class="additional"></div>

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{include file="$templates_path/users/user_form.tpl" form_action="add"}

</div>