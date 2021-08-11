<script type="text/javascript">
{literal}
function foto_choose(foto_type) {
	window.open('{/literal}{$base_path}{literal}/admin/farch/choose/?foto_type='+foto_type, 'fotochoose', 'width=650,height=450,resizable=yes,scrollbars=yes,toolbar=no,status=no,dependent=yes');
}

function foto_clear(foto_type) {
	if (foto_type == 'head') {
		$("#meta_head_image_foto_id").val('');
		$("#meta_head_image").hide();
	} else {
		$("#meta_image_foto_id").val('');
		$("#meta_image").hide();
	}
}

function farchRecieveChosenFoto(foto_id, foto_title, foto_src, foto_type) {
	if (foto_type == 'head') {
		$("#meta_head_image_foto_id").val(foto_id);
		$("#meta_head_image").attr("src", foto_src);
		$("#meta_head_image").show();
	} else {
		$("#meta_image_foto_id").val(foto_id);
		$("#meta_image").attr("src", foto_src);
		$("#meta_image").show();
	}
}
{/literal}
</script>

<form method="POST" 
{if $form_action eq "add"}
action="{$base_path}/admin/meta/new/add/"
{else}
action="{$base_path}/admin/meta/edit/modify/"
{/if}
>
{if $form_action ne "add"}
<input type="hidden" name="meta_id" value="{$meta_info.META_ID}">
{/if}

<table class="ctrl" cellspacing=0 cellpadding=4>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_path#}</b></td>
<td>
{if $form_action eq "add"}
<input type="text" style="width: 450px;" name="uri" value="{$meta_info.URI|escape:'html'}">
{else}
{$meta_info.URI}
{/if}
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_title#}</b><br><small>{#lang_meta_form_field_title_note#}</small></td>
<td><input class="multilang" type="text" style="width: 450px;" name="title" value="{$meta_info.TITLE|escape:'html'}"></td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_text#}</b><br><small>{#lang_meta_form_field_text_note#}</small></td>
<td><input class="multilang" type="text" style="width: 450px;" name="inner_title" value="{$meta_info.INNER_TITLE|escape:'html'}"></td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_admin_form_field_picture#}</b></td>
<td>
<input type="hidden" id="meta_image_foto_id" name="meta_image_foto_id" value="{$meta_info.meta_image.FOTO_ID|escape:'html'}">
<img id="meta_image" src="{$farch_foto_params.folders.link}/{$meta_info.meta_image.ALBUM_ID}/{$farch_foto_params.previews.post.prefix}{$meta_info.meta_image.FOTO_ID}.{$meta_info.meta_image.TECH_INFO.extension}" {if $meta_info.meta_image.FOTO_ID eq ''}style="display: none;"{/if}>
<br><input type="button" value="{#lang_farch_admin_choose_foto_btn#}" onClick="foto_choose('meta');"> <input type="button" value="{#lang_farch_admin_clear_foto_btn#}" onClick="foto_clear('meta');">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_html_admin_form_field_head_picture#}</b></td>
<td>
<input type="hidden" id="meta_head_image_foto_id" name="meta_head_image_foto_id" value="{$meta_info.head_image.FOTO_ID|escape:'html'}">
<img id="meta_head_image" src="{$farch_foto_params.folders.link}/{$meta_info.head_image.ALBUM_ID}/{$farch_foto_params.previews.post.prefix}{$meta_info.head_image.FOTO_ID}.{$meta_info.head_image.TECH_INFO.extension}" {if $meta_info.head_image.FOTO_ID eq ''}style="display: none;"{/if}>
<br><input type="button" value="{#lang_farch_admin_choose_foto_btn#}" onClick="foto_choose('head');"> <input type="button" value="{#lang_farch_admin_clear_foto_btn#}" onClick="foto_clear('head');">
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_keywords#}</b><br><small>{#lang_meta_form_field_keywords_note#}</small></td>
<td><textarea class="multilang"  style="width: 450px; height: 75px;" name="keywords">{$meta_info.KEYWORDS}</textarea></td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_description#}</b><br><small>{#lang_meta_form_field_description_note#}</small></td>
<td><textarea class="multilang" style="width: 450px; height: 75px;" name="description">{$meta_info.DESCRIPTION}</textarea></td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_og#}</b><br><small>{#lang_meta_form_field_og_note#}</small></td>
<td><textarea class="multilang" style="width: 450px; height: 120px;" name="og_meta_tags">{$meta_info.OG_META_TAGS}</textarea>
<textarea readonly style="width: 450px; height: 50px; color: #999;">
<meta property="og:title" content=""/>
<meta property="og:type" content="product,company,article"/>
<meta property="og:url" content="{$domain}"/>
<meta property="og:image" content="{$domain}"/>
<meta property="og:site_name" content="{$simple_domain}"/>
<meta property="og:description" content=""/>
<meta property="fb:admins" content=""/>
</textarea>
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_access_local#}</b><br><small>{#lang_meta_form_field_access_local_note#}</small></td>
<td>
<select name="error_state_local">
{foreach from=$meta_error_states item=state_descr key=state}
<option value="{$state}" {if $meta_info.ERROR_STATE_LOCAL eq $state}selected{/if}>{$state_descr}</option>
{/foreach}
</select>
</td>
</tr>

<tr class="striked">
<td valign=top><b>{#lang_meta_form_field_access_outside#}</b><br><small>{#lang_meta_form_field_access_outside_note#}</small></td>
<td>
<select name="error_state_inet">
{foreach from=$meta_error_states item=state_descr key=state}
<option value="{$state}" {if $meta_info.ERROR_STATE_INET eq $state}selected{/if}>{$state_descr}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td></td>
<td><input type="submit" value="{#lang_admin_save_btn_text#}"></td>
</tr>
</table>

</form>