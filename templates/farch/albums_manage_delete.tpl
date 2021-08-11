<h1>{#lang_farch_admin_remove_album_title#}</h1>

{include file="farch/admin_farch.tpl"}

<div class="content">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

{#lang_farch_admin_remove_album_warning#}
<form action="{$base_path}/admin/farch/albums/delete/remove/" method="POST">
<input type="hidden" name="album_id" value="{$album_info.ALBUM_ID}">
<p><input type="submit" value="{#lang_admin_confirm_remove#}"> <input type="button" value="{#lang_admin_cancel_remove#}" onClick="document.location='{$base_path}/admin/farch/albums/';">
</form>

</div>
