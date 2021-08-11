<!doctype html public "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>{#lang_files_admin_new_file_title#}</title>
</head>
<body>

<h3>{#lang_files_admin_new_file_head#}</h3>
<form enctype="multipart/form-data" action="./add/" method=POST>
<input type=hidden name="path" value="{$path}">
<input type="file" name="afile"><br>
<input type="submit" value="{#lang_admin_add_btn_text#}">
</form>

</body>
</html>
