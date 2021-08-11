<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{#lang_files_admin_delete_file_title#}</title>
</head>
<body onLoad="document.getElementById('okbut').focus();">

<h3>{#lang_files_admin_delete_file_confirm#}</h3>
{$fullname}<br><br>
<form action="remove/" method=POST>
<input type=hidden name="fullname" value="{$fullname}">
<input type="submit" id="okbut" value="{#lang_admin_confirm_remove#}">
<input type="button" value="{#lang_admin_cancel_remove#}" onClick="window.close();">
</form>
</body>
</html>
