<h1>Восстановление пароля</h1>
{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}
<form action="" method="post">
<label>Введите логин или e-mail </label><input type="text" name="login_or_mail" /><br />
<input type="submit" />
<p>Новый пароль будет выслан на ваш e-mail</p>
</form>