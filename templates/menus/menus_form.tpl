{if $menus_farch_component}
<script type="text/javascript">

function foto_choose() {ldelim}
	window.open('{$base_path}/admin/farch/choose/', 'fotochoose', 'width=650,height=450,resizable=yes,scrollbars=yes,toolbar=no,status=no,dependent=yes');
{rdelim}

function foto_clear() {ldelim}
	$("#menu_image_foto_id").val('');
	$("#menu_image").hide();
{rdelim}

function farchRecieveChosenFoto(foto_id, foto_title, foto_src) {ldelim}
	$("#menu_image_foto_id").val(foto_id);
	$("#menu_image").attr("src", foto_src);
	$("#menu_image").show();
{rdelim}

</script>
{/if}

<div class="content">
<form action="{$form_action}/" method=POST>
{if $form_action=="modify"}<input type="hidden" name="id" value="{$info_menu_block.ID}">{/if}
<table class="ctrl" cellspacing="0">

<tr  class="striked" valign="top">
<td><b>{#lang_menus_admin_form_field_title#}</b></td>
<td><input class="multilang" type="text" name="title" value="{$info_menu_block.TITLE}" size=32></td>
</tr>

{if $menus_farch_component}
<tr class="striked">
<td valign=top><b>{#lang_menus_admin_form_field_picture#}</b><br>
<small>{#lang_menus_admin_form_field_picture_note#}</small></td>
<td>
<input type="hidden" id="menu_image_foto_id" name="menu_image_foto_id" value="{$meta_info.head_image.FOTO_ID|escape:'html'}">
<img id="menu_image" src="{$farch_foto_params.folders.link}/{$info_menu_block.image.ALBUM_ID}/{$farch_foto_params.previews.post.prefix}{$info_menu_block.image.FOTO_ID}.{$info_menu_block.image.TECH_INFO.extension}" {if $info_menu_block.image.FOTO_ID eq ''}style="display: none;"{/if}>
<br><input type="button" value="{#lang_farch_admin_choose_foto_btn#}" onClick="foto_choose();"> <input type="button" value="{#lang_farch_admin_clear_foto_btn#}" onClick="foto_clear();">
</td>
</tr>
{/if}

<tr class="striked" valign="top">
<td><b>{#lang_menus_admin_form_field_comment#}</b><br>
<small>{#lang_menus_admin_form_field_comment_note#}</small></td>
<td>
{if $superuser eq true}
<input type="text" name="comment" value="{$info_menu_block.COMMENT}" size=32>
{else}
{$info_menu_block.COMMENT}
{/if}
</td>
</tr>

<tr class="striked" valign="top">
<td><b>{#lang_menus_admin_form_field_path#}</b><br>
<small>{#lang_menus_admin_form_field_path_note#}</small></td>
<td>
{if $superuser eq true}
<input type="text" name="path" value="{$info_menu_block.PATH}" size=32>
{else}
{$info_menu_block.PATH}
{/if}
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