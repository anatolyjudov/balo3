<h1>{#lang_html_admin_edit_title#}</h1>

<div class="content" style="width: 80%;">

<form action="../modify/" method=POST>

<script language="javascript">

function insertImage(fullpath, filenam, filenam_escaped) {ldelim}
	if (fullpath == '{$node_info.M_PATH|escape:'url'}') {ldelim}
		uri = filenam_escaped;
	{rdelim} else {ldelim}
		uri = '{$base_path}' + fullpath + filenam_escaped;
	{rdelim}
	AddHTML(document.getElementById('html_content'), "<img src='" + uri + "'>");
{rdelim}

function insertAnchor(fullpath, filenam, filenam_escaped) {ldelim}
	if (fullpath == '{$node_info.M_PATH|escape:'url'}') {ldelim}
		uri = filenam_escaped;
	{rdelim} else {ldelim}
		uri = '{$base_path}' + fullpath + filenam_escaped;
	{rdelim}
	AddHTML(document.getElementById('html_content'), "<a href='" + uri + "'>" + filenam + "</a>");
{rdelim}


function open_browser() {ldelim}
	window.open('{$base_path}/admin/files/?path={$node_info.M_PATH}&popup=on', 'browser', 'width=450,height=300,resizable=yes,scrollbars=yes,toolbar=no,status=no,dependent=yes');
{rdelim}

{literal}
function foto_choose(foto_type) {
	window.open('{/literal}{$base_path}{literal}/admin/farch/choose/?foto_type='+foto_type, 'fotochoose', 'width=650,height=450,resizable=yes,scrollbars=yes,toolbar=no,status=no,dependent=yes');
}

function foto_clear(foto_type) {
	if (foto_type == 'meta') {
		$("#meta_head_image_foto_id").val('');
		$("#meta_head_image").hide();
	} else {
		$("#html_image_foto_id").val('');
		$("#html_image").hide();
	}
}

function farchRecieveChosenFoto(foto_id, foto_title, foto_src, foto_type) {

	if (foto_type == 'meta') {
		$("#meta_head_image_foto_id").val(foto_id);
		$("#meta_head_image").attr("src", foto_src);
		$("#meta_head_image").show();
	} else {
		$("#html_image_foto_id").val(foto_id);
		$("#html_image").attr("src", foto_src);
		$("#html_image").show();
	}
}
{/literal}
</script>

{if $page_info.PLAIN_HTML_EDIT != 1}
{include file="$templates_path/common/tinymce_init.tpl" tinymce_elements="html_content"}
{/if}

<table class="ctrl" style="width: 100%;">

<tr>
<td><b>{#lang_html_admin_form_field_title#}</b><br>{#lang_html_admin_form_field_title_note#}</td>
<td><input class="multilang" style="width: 100%; font-size: 140%;" type="text" size=36 value="{$page_info.HTML_PAGE_TITLE|escape:'html'}" name="html_page_title" maxlength=255></td>
</tr>

{*
{if $staticpages_farch_component}
<tr>
<td valign=top><b>{#lang_html_admin_form_field_picture#}</b></td>
<td>
<input type="hidden" id="html_image_foto_id" name="html_image_foto_id" value="{$page_info.html_image.FOTO_ID|escape:'html'}">
<img id="html_image" src="{$farch_foto_params.folders.link}/{$page_info.html_image.ALBUM_ID}/{$farch_foto_params.previews.post.prefix}{$page_info.html_image.FOTO_ID}.{$page_info.html_image.TECH_INFO.extension}" {if $page_info.html_image.FOTO_ID eq ''}style="display: none;"{/if}>
<br><input type="button" value="{#lang_farch_admin_choose_foto_btn#}" onClick="foto_choose('html');"> <input type="button" value="{#lang_farch_admin_clear_foto_btn#}" onClick="foto_clear('html');">
</td>
</tr>
{/if}
*}

<tr>
<td><b>{#lang_html_admin_form_field_plainhtmledit#}</b><br>{#lang_html_admin_form_field_plainhtmledit_note#}</td>
<td><input type="checkbox" {if $page_info.PLAIN_HTML_EDIT == 1}checked="checked"{/if} name="plain_html_edit"></td>
</tr>


<tr>
<td colspan=2>
<textarea class="multilang" style="width: 100%; height: 600px;" id="html_content" name="html_content">{$page_info.HTML_CONTENT}</textarea>
</td>
</tr>

{*
{if $staticpages_farch_component}
<tr>
<td valign=top><b>{#lang_html_admin_form_field_head_picture#}</b></td>
<td>
<input type="hidden" id="meta_head_image_foto_id" name="meta_head_image_foto_id" value="{$meta_info.head_image.FOTO_ID|escape:'html'}">
<img id="meta_head_image" src="{$farch_foto_params.folders.link}/{$meta_info.head_image.ALBUM_ID}/{$farch_foto_params.previews.post.prefix}{$meta_info.head_image.FOTO_ID}.{$meta_info.head_image.TECH_INFO.extension}" {if $meta_info.head_image.FOTO_ID eq ''}style="display: none;"{/if}>
<br><input type="button" value="{#lang_farch_admin_choose_foto_btn#}" onClick="foto_choose('meta');"> <input type="button" value="{#lang_farch_admin_clear_foto_btn#}" onClick="foto_clear('meta');">
</td>
</tr>
{/if}
*}

<tr>
<td valign=top><b>{#lang_html_admin_form_field_meta_title#}</b><br><small>{#lang_html_admin_form_field_meta_title_note#}</small></td>
<td><input type="hidden" name="meta_uri" value="{$meta_info.uri}">
<input class="multilang" type="text" style="width: 100%;" name="meta_title" value="{$meta_info.TITLE|escape:'html'}"></td>
</tr>
<tr>
<td valign=top><b>{#lang_html_admin_form_field_meta_inner_title#}</b><br><small>{#lang_html_admin_form_field_meta_inner_title_note#}</small></td>
<td><input class="multilang" type="text" style="width: 100%;" name="meta_link" value="{$meta_info.INNER_TITLE|escape:'html'}"></td>
</tr>
<tr>
<td valign=top><b>{#lang_html_admin_form_field_meta_keywords#}</b><br><small>{#lang_html_admin_form_field_meta_keywords_note#}</small></td>
<td><textarea class="multilang" style="width: 100%; height: 75px;" name="meta_keywords">{$meta_info.KEYWORDS}</textarea></td>
</tr>
<tr>
<td valign=top><b>{#lang_html_admin_form_field_meta_description#}</b><br><small>{#lang_html_admin_form_field_meta_description_note#}</small></td>
<td><textarea class="multilang" style="width: 100%; height: 75px;" name="meta_description">{$meta_info.DESCRIPTION}</textarea></td>
</tr>

<tr>
<td></td>
<td>
<input type="hidden" name="node" value="{$node_info.NODE_ID}">
<input type="submit" value="{#lang_admin_savechanges_btn_text#}" style="font-size: 140%;">
</td>
</tr>
</table>

</form>
</div>