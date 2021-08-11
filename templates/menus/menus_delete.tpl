<h1>{#lang_menus_admin_menus_delete_title#}</h1>

<div class="content">
<p>{#lang_menus_admin_menus_delete_confirm#}<br></p>

<form action="{$menus_admin_branch}/delete/remove/?id={$id}" method="POST">
<table class="ctrl" cellspacing=10>
<tr>
<td><input type="submit" value="{#lang_admin_confirm_remove#}"></td>

<td><input type="button" value="{#lang_admin_cancel_remove#}" onLoad="this.focus();" onClick="history.go(-1);"></td>
</tr>
</table>
</form>

</div>