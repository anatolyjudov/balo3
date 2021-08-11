{if !$cats}
	<p>{#lang_news_admin_form_nocats#}<br>
	<a href="../news_cats/">{#lang_news_admin_form_create_cat#}</a></p>
{else}

<script type="text/javascript">

function foto_choose() {ldelim}
	window.open('{$base_path}/admin/farch/choose/', 'fotochoose', 'width=650,height=450,resizable=yes,scrollbars=yes,toolbar=no,status=no,dependent=yes');
{rdelim}

function foto_clear() {ldelim}
	$("#news_image_foto_id").val('');
	$("#news_image").hide();
{rdelim}

function farchRecieveChosenFoto(foto_id, foto_title, foto_src) {ldelim}
	$("#news_image_foto_id").val(foto_id);
	$("#news_image").attr("src", foto_src);
	$("#news_image").show();
{rdelim}

</script>

<h1>{if $action eq "edit/modify"}{#lang_news_admin_edit_title#}{else}{#lang_news_admin_new_title#}{/if}</h1>

<div class="content" style="width: 80%;">

{if $errmsg ne ""}<p style="color: red;">{$errmsg}</p>{/if}

<form action="{$base_path}/admin/news/{$action}/" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" value="{$id}">
<input type="hidden" name="confirmed" value="1">

<table class="ctrl" style="width: 100%; max-width: 1200px;">

<tr><td class="striked"><b>{#lang_news_admin_form_field_category#}</b></td>
<td>
	<select style="font-size: 140%;" name="cid">
	{section name=i loop=$cats}
		<option value="{$cats[i][0]}" {if $cats[i][0] eq $cid}selected{/if}>{$cats[i][1]}</option>
	{/section}
	</select>
</td></tr>

<tr><td class="striked"><b>{#lang_news_admin_form_field_title#}</b></td>
<td><input  class="multilang" type="text" name="name" style="width: 100%; font-size: 140%;" value="{$name|escape:'html'}"></td></tr>


<tr><td class="striked"><b>{#lang_news_admin_form_field_date#}</b></td>
<td>
<input type="text" name="news_date" id="news_date" {if $id ne ""}value="{$news_date|date_format:'%d.%m.%Y'}"{else}value="{$today_formatted}"{/if}>
<input type="button" onclick="if(self.gfPop)gfPop.fPopCalendar(document.getElementById('news_date'));" value="{#lang_farch_admin_fotos_manage_choose_btn#}" />
</td></tr>

{*
<tr>
<td valign=top><b>{#lang_news_admin_form_field_picture#}</b></td>
<td>
<input type="hidden" id="news_image_foto_id" name="news_image_foto_id" value="{$news_image.FOTO_ID|escape:'html'}">
<img id="news_image" src="{$farch_foto_params.folders.link}/{$news_image.ALBUM_ID}/{$farch_foto_params.previews.post.prefix}{$news_image.FOTO_ID}.{$news_image.TECH_INFO.extension}" {if $news_image.FOTO_ID eq ''}style="display: none;"{/if}>
<br><input type="button" value="{#lang_farch_admin_choose_foto_btn#}" onClick="foto_choose();"> <input type="button" value="{#lang_farch_admin_clear_foto_btn#}" onClick="foto_clear();">
</td>
</tr>
*}

<tr><td class="striked"><b>{#lang_news_admin_form_field_shorttext#}</b></td>
<td><textarea class="multilang" name="stext" id="short_text_content" style="width: 100%; height: 250px;">{$stext}</textarea><br><small></small>
</td></tr>

<tr><td colspan="2" class="striked"><b>{#lang_news_admin_form_field_text#}</b></td></tr>

<tr><td colspan="2"><textarea  class="multilang" name="text" id="html_content" style="width: 100%; height: 600px;">{$text}</textarea>
</td></tr>

<tr><td class="striked"></td><td><input type="submit" value="{#lang_admin_savechanges_btn_text#}" style="font-size: 140%;"></td></tr>
</table>
</form>

</div>
{/if}

{include file="$templates_path/common/tinymce_init.tpl" tinymce_elements="html_content,short_text_content"}

{assign var="show_calendarxp_code" value=true}
