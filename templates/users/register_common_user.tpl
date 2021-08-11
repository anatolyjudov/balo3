

<h1>Регистрация</h1>
{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{include file="$tpls_path/users/common_user_form_self.tpl" form_action="register"}

