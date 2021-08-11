<!DOCTYPE html>
<html>
<head>
<title>{$meta_title}</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="{$meta_description}">
<meta name="keywords" content="{$meta_keywords}">
<link rel="stylesheet" type="text/css" href="{$base_path}/js/lib/fancybox-2.1.5/jquery.fancybox.css?v=2.1.5" media="screen" />
<link rel="stylesheet" href="{$base_path}/css/admin_main.css" type="text/css">
<link rel="stylesheet" href="{$base_path}/css/admin_common.css" type="text/css">
<link rel="stylesheet" href="{$base_path}/css/fonts.css"/>
{if $farch_component}<link rel="stylesheet" href="{$base_path}/css/farch.css" type="text/css">{/if}
<script type="text/javascript" src="{$base_path}/js/lib/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="{$base_path}/js/lib/jquery-ui-1.11.4.custom.min.js"></script>
<script type="text/javascript" src="{$base_path}/js/lib/jquery.upload-1.0.2.min.js"></script>
{if $farch_component}<link rel="stylesheet" type="text/css" href="/js/lib/uploadify/uploadify-custom.css" />{/if}
<link rel="stylesheet" type="text/css" href="/js/lib/dropzone/dropzone.min.css" />
</head>
<body>
<div id="omni">

<div id="topline">
	<div class="navstr"><a href="{$base_path}/" id="sitename">{$common_simple_domain}</a> / <a href="{$base_path}/admin/">{#lang_control_panel#}</a></div>
</div>

{if $ml_multilang_mode}
<div id="langs">
	<ul>
	{foreach from=$ml_langs_list item=lang_info key=lang_id}
		<li {if $ml_current_language_id eq $lang_id}class="selected"{/if}><a href="http://{$lang_info.domains[0]}{$common_strpath}">{$lang_info.name_switcher}</a></li>
	{/foreach}
	</ul>
</div>
{/if}

<div id="hmenu">
<ul class="nav">
{balo3_widget family="menus" widget="menus_show" menu_block_id=20 menu_block_template="cpmenu.tpl" menu_cache="off"}
{*{insert script="$mods_path/menus_show.php" name="menus_show" menu_block_id=3 menu_block_template="cpmenu.tpl" menu_cache="off"}*}
</ul>
<div class="lgout"><a href="{$base_path}/logout/">{#lang_logout#}</a></div>
</div>

<div class="mainbox">

{*{balo3_widget family="textblocks" widget="textblocks_show" textblock_name="test" textblock_template="clean.tpl"}*}

{balo3_placeholder name="main"}
</div>

<br clear="all">
<br><br>



{if $show_calendarxp_code == 1}
<iframe width=174 height=189 name="gToday:contrast:agenda.js" id="gToday:contrast:agenda.js" src="{$base_path}/js/lib/calendarxp/ipopeng.htm" scrolling="no" frameborder="0" style="visibility:visible; z-index:999; position:absolute; top:-500px; left:-500px;">
</iframe>
{/if}

<div class="footerpush"></div>
</div> {* omni *}

<div id="bottomline">
<div class="contacts">{#lang_admin_feedback#}</div>
</div>
<script type="text/javascript" src="{$base_path}/js/lib/fancybox-2.1.5/jquery.fancybox.pack.js?v=2.1.5"></script>
<script type="text/javascript" src="{$base_path}/js/lib/fancybox-2.1.5/helpers/jquery.fancybox-media.js?v=1.0.6"></script>
<script type="text/javascript" src="{$base_path}/js/admin.js"></script>
<script type="text/javascript" src="{$base_path}/js/script.js"></script>
{literal}
<script type="text/javascript">
$(document).ready(function() {
	$("a#good").fancybox();
});
</script>
{/literal}
</body>
</html>