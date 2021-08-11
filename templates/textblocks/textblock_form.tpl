<div class="content">
<form action="{$form_action}/" method=POST>
{if $form_action=="modify"}<input type="hidden" name="id" value="{$info_textblock.ID}">{/if}

<script type="text/javascript">

	function insertImage(fullpath, filenam, filenam_escaped) {ldelim}
		uri = '{$base_path}' + fullpath + filenam_escaped;
		AddHTMLtmce(document.getElementById('text'), "<img src='" + uri + "'>");
	{rdelim}

	function insertAnchor(fullpath, filenam, filenam_escaped) {ldelim}
		uri = '{$base_path}' + fullpath + filenam_escaped;
		AddHTMLtmce(document.getElementById('text'), "<a href='" + uri + "'>" + filenam + "</a>");
	{rdelim}

</script>
{if $info_textblock.PLAIN_HTML_EDIT == 0}
{include file="$templates_path/common/tinymce_init.tpl" tinymce_elements="text"}
{/if}

<table class="ctrl" style="width: 100%;">

<tr class="striked" valign="top">
<td><b>{#lang_tb_admin_field_title#}</b></td>
<td><input style="width: 100%;" class="multilang" type="text" name="title" value="{$info_textblock.TITLE|escape:'html'}" size="255">
<br><small>{#lang_tb_admin_field_title_note#}</small></td>
</tr>

{*
<tr class="striked" valign="top">
<td><b>{#lang_tb_admin_field_bgcolor#}</b></td>
<td>#<input type="text" name="bgcolor" value="{$info_textblock.BGCOLOR|escape:'html'}" size=6>
<br><small>{#lang_tb_admin_field_bgcolor_note#}</small></td>
</tr>
*}

<tr class="striked" valign="top">
<td><b>{#lang_tb_admin_field_plainhtmledit#}</b></td>
<td><input type="checkbox" name="plainedit" {if $info_textblock.PLAIN_HTML_EDIT == 1}checked="checked"{/if}>
<br><small>{#lang_tb_admin_field_plainhtmledit_note#}</small></td>
</tr>

<tr valign="top">
<td colspan="2"><b>{#lang_tb_admin_field_text#}</b></td>
</tr>

<tr class="striked" valign="top">
<td colspan="2"><textarea style="width: 100%;" class="multilang" name="text" cols="80" rows="24">{$info_textblock.TEXT|escape:'html'}</textarea>
</td>
</tr>


<tr class="striked" valign="top">
<td><b>{#lang_tb_admin_field_identify#}</b></td>
<td><input type="text" name="name" value="{$info_textblock.NAME|escape:'html'}" size=32>
<br><small>{#lang_tb_admin_field_identify_note#}</small></td>
</tr>

<tr class="striked" valign="top">
<td><b>{#lang_tb_admin_field_comment#}</b></td>
<td>
{if $superuser eq true}
<input style="width: 100%;" type="text" name="comment" value="{$info_textblock.COMMENT|escape:'html'}" size=32>
{else}
{$info_textblock.COMMENT}
{/if}
<br>
<small>{#lang_tb_admin_field_comment_note#}</small>
</td>
</tr>

<tr class="striked" valign="top">
<td><b>{#lang_tb_admin_field_path#}</b></td>
<td>
{if $superuser eq true}
<input type="text" name="path" value="{$info_textblock.PATH|escape:'html'}" size=32>
{else}
{$info_textblock.PATH}
{/if}
<br><small>{#lang_tb_admin_field_path_note#}</small>
</td>
</tr>

<tr class="head">
<td colspan="2"></td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="{#lang_admin_save_btn_text#}"></td>
</tr>

</table>
</form>
</div>