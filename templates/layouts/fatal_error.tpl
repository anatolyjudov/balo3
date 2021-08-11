<!DOCTYPE html>
<html>
<head>
{include file="$templates_path/common/common_title.tpl"}
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="{$base_path}/css/main.css" type="text/css">
{*<link rel="icon" href="{$domain}{$base_path}/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="{$domain}{$base_path}/favicon.ico" type="image/x-icon">*}
</head>
<body>

	{if $common_error_template ne ""}
		{include file="$templates_path/common/`$common_error_template`"}
	{else}
		{include file="$templates_path/common/fatal_error.tpl"}
	{/if}

</body>
</html>