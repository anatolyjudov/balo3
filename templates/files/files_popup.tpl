<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title> добавление файла </title>
<link rel="stylesheet" href="{$base_path}/css/admin_main.css" type="text/css">
</head>
<body style="padding: 10px; margin: 10px;">

<script language="javascript">

function show_add_dialog() {ldelim}

	window.open('./new/?path={$path}', 'add', 'width=350,height=230,resizeable=no,toolbar=no,status=no,dependent=yes');

{rdelim}

function show_delete_dialog(filenam) {ldelim}

	window.open('./delete/?fullname={$path}' + filenam, 'delete', 'width=350,height=230,resizeable=no,toolbar=no,status=no,dependent=yes');

{rdelim}


function renew() {ldelim}
	window.location.reload();
{rdelim}

function insert_file(filenam, filenam_escaped) {ldelim}
	window.opener.{if $callback_func ne ''}{$callback_func}{else}insertAnchor{/if}('{$path}', filenam, filenam_escaped);
	window.close();
{rdelim}

function insert_img(filenam, filenam_escaped) {ldelim}
	window.opener.{if $callback_func ne ''}{$callback_func}{else}insertImage{/if}('{$path}', filenam, filenam_escaped);
	window.close();
{rdelim}

</script>
<h3 style="margin-bottom: 10px;">{#lang_files_admin_filebrowser_title#}</h3>
<small>{$path}</small><br><br>

<table cellspacing=0 cellpadding=0 class="data" width=100%>
<tr class="head">
<td></td>
<td>{#lang_files_admin_filebrowser_head_name#}</td>
<td>{#lang_files_admin_filebrowser_head_size#}</td>
<td>{#lang_files_admin_filebrowser_head_date#}</td>
<td>{#lang_files_admin_filebrowser_head_delete#}</td>
</tr>
{if $parent!=""}
<tr class="nhl">
<td>D</td>
<td><a href="./?path={$parent}&popup=on{if $callback_func ne ''}&callback_func={$callback_func}{/if}">..</a></td>
<td></td>
<td></td>
<td></td>
</tr>
{/if}

{if count($files_info)>0}
{foreach from=$files_info item=fi}
{if $fi.type=="dir"}<tr class="nhl">{else}<tr class="hl">{/if}
<td>
{if $fi.type=="dir"}D
{else}
{if ($fi.ext == "jpg") or ($fi.ext == "jpeg") or ($fi.ext == "gif") or ($fi.ext == "png")}
<a href="insert/" onClick="insert_img('{$fi.filename}', '{$fi.filename|escape:'url'}'); return false;"><img src="{$pics_path}/add.png" style="border-style: none;"></a>
{else}
<a href="insert/" onClick="insert_file('{$fi.filename}', '{$fi.filename|escape:'url'}'); return false;"><img src="{$pics_path}/add.png" style="border-style: none;"></a>
{/if}
{/if}
</td>
<td>
{if $fi.type=="dir"}
<a href="./?path={$path}{$fi.filename|escape:'url'}/&popup=on{if $callback_func ne ''}&callback_func={$callback_func}{/if}">{$fi.filename}</a>
{else}
	<a href="{$base_path}{$path}{$fi.filename|escape:'url'}" target="preview_">{$fi.filename}</a>
{/if}
</td>
<td><small>{$fi.size}</small></td>
<td><small>{$fi.last_modify|date_format:"%H:%M:%S %d-%m-%Y"}</small></td>
<td>{if $fi.type=="file"}<a href="./delete/" onClick="show_delete_dialog('{$fi.filename}'); return false;"><img src="{$pics_path}/delete.png" alt="{#lang_admin_remove_alt_text#}"></a>{/if}</td>
</tr>
{/foreach}
{/if}

</table>

<p style="margin-top: 20px;">
<img src="{$pics_path}/add.png" alt="" style="position: relative; top: 3px; margin-right: 3px;"><a href="./new/" onClick="show_add_dialog(); return false;">{#lang_admin_addfile_btn_text#}</a>
</p>

</body>
</html>
