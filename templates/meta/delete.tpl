<h1>{#lang_meta_admin_delete_title#}</h1>
<h1 class=sub></h1>

<div class="additional">{include file="$templates_path/meta/meta_adm_menu.tpl"}</div>

<div class="content">

<p>{#lang_meta_admin_delete_confirm#} <a href="{$base_path}{$meta_info.URI}">{$meta_info.URI}</a> {#lang_meta_admin_delete_confirm1#}</p>
<p>{#lang_meta_admin_delete_confirm2#}</p>

<form action="{$base_path}/admin/meta/delete/remove/" method=POST>
<input type=hidden name="meta_id" value="{$meta_id}">
<input type="submit" value="{#lang_admin_confirm_remove#}"> &nbsp;&nbsp; <input type="button" value="{#lang_admin_cancel_remove#}" OnClick="history.go(-1);">
</form>

</div>