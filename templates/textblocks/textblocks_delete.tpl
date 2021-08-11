<h1>{#lang_tb_admin_delete_title#}</h1>

<div class="content">


<p>{#lang_tb_admin_delete_confirm#}<br></p>

<form action="remove/?id={$id}" method="POST">
<table class="ctrl" cellspacing=10>
<tr>
<td><input type="submit" value="{#lang_admin_confirm_remove#}"></td>

<td><input type="button" value="{#lang_admin_cancel_remove#}" onLoad="this.focus();" onClick="history.go(-1);"></td>
</tr>
</table>
</form>
</div>