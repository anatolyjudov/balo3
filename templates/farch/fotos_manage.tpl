<script type="text/javascript" src="/js/lib/dropzone/dropzone.min.js"></script>

<h1>{#lang_farch_admin_fotos_manage_title#}</h1>

{include file="$templates_path/farch/admin_farch.tpl"}

<div class="content">

<div id="toolbar">

<select id="tb_album_filter_select" onChange="fotosToolbar.SetAlbumFilter();">
<option value="0">-- {#lang_farch_admin_choose_album_option#}</option>
{foreach from=$albums_info.plain item=parent_album_id}
<option value="{$parent_album_id}" {if $parent_album_id == $force_album_id}selected{/if}>{section name=marginlevel loop=$albums_info.list[$parent_album_id].TREE_LEVEL start=0 step=1}&mdash;{/section} {$albums_info.list[$parent_album_id].ALBUM_NAME}</option>
{/foreach}
</select> <br>

{*
<select id="tb_filter_select" onChange="fotosToolbar.SetTagFilter();">
<option value="0">-- {#lang_admin_all_option#}</option>
{foreach from=$fototags_list key=fototag_id item=fototag_info}
<option value="{$fototag_id}">{$fototag_info.FOTOTAG}</option>
{/foreach}
</select>
*}

{*<input type="button" id="tb_upload_btn" value="{#lang_farch_admin_fotos_manage_add_btn#}" onClick="fotosToolbar.uShowDialog('upload', $(this).offset());">*}
<input type="button" id="tb_uploadMass_btn" value="{#lang_farch_admin_fotos_manage_add_btn#}" onClick="fotosToolbar.uShowDialog('uploadMass', $(this).offset());">
<input type="button" id="tb_edit_btn" value="{#lang_farch_admin_fotos_manage_edit_btn#}" disabled onClick="fotosToolbar.sShowEditDialog($(this).offset());">
<input type="button" id="tb_remove_btn" value="{#lang_farch_admin_fotos_manage_delete_btn#}" disabled onClick="fotosToolbar.uShowDialog('remove', $(this).offset());">
{*<input type="button" id="tb_tags_btn" value="{#lang_farch_admin_fotos_manage_tags_btn#" disabled onClick="fotosToolbar.uShowTagsDialog($(this).offset());">*}
<input type="button" id="tb_selectall_btn" value="{#lang_farch_admin_fotos_manage_selectall_btn#}" onClick="fotosCollection.uSelectAll();"> <input type="button" id="tb_selectall_btn" value="{#lang_farch_admin_fotos_manage_unselectall_btn#}" onClick="fotosCollection.uUnselectAll();">
</div>

<div id="collection" class=""></div>

<script type="text/javascript">

// ????????????, ?????????????????????? ???????????????????????? ??????????????????
var fotosCollection = {ldelim}
	collection_container_id : 'div#collection',
	selected_ids : [],
	skip : 0,
	limit : 0,
	tag_filter : [],
	album_filter : [],
	fotos_link_path : '{$farch_foto_params.folders.link}',
	fotos_preview_prefix : '{$farch_foto_params.previews.small.prefix}'
{rdelim}

// ????????????, ?????????????????????? ?????????????????????????? ?? ????????????????????
var fotosToolbar = {ldelim}
	toolbar_container_id : 'div#toolbar',
	fotos_link_path :  '{$farch_foto_params.folders.link}',
	fotos_dialog_preview_prefix :  '{$farch_foto_params.previews.edit.prefix}'
{rdelim}

// 
var fotosTextmessages = {ldelim}
	choose_album : '{#lang_farch_admin_fotos_choose_album#}',
	no_fotos_in_album : '{#lang_farch_admin_fotos_no_fotos_in_album#}',
	foto_not_chosen : '{#lang_farch_admin_fotos_foto_not_chosen#}',
	choosed_more_than_one : '{#lang_farch_admin_fotos_choosed_more_than_one#}',
	album_not_chosen : '{#lang_farch_admin_fotos_album_not_chosen#}',
	file_upload_error : '{#lang_farch_admin_fotos_file_upload_error#}'
{rdelim}

</script>

<script type="text/javascript" src="{$base_path}/js/farch/farch_fotos_collection.js"></script>
<script type="text/javascript" src="{$base_path}/js/farch/farch_fotos_toolbar.js"></script>

<script type="text/javascript">

$(document).ready(function() {ldelim}

	{if isset($force_album_id)}
	fotosToolbar.SetAlbumFilter();
	{/if}

	fotosCollection.sUpdateFotos();
	fotosToolbar.uRefreshToolbar();

{rdelim});

</script>

{*
<div id="w_tags_dialog" class="w_dialog" style="display: none;">
	<h3>{#lang_farch_admin_fotos_manage_tags_title#}</h3>
	<input type="hidden" id="ta_fotos_ids" value="">
	<p>{#lang_farch_admin_fotos_manage_tags_chosen#} <span id="ta_fotos_count"></span> {#lang_farch_admin_fotos_manage_tags_chosen2#}</p>
	<div id="ta_form" class="w_dialog_form">
		{foreach from=$fototags_list key=fototag_id item=fototag_info}
		<div id="ta_checkbox_{$fototag_id}" class="ar_checkbox" onClick="fotosToolbar.toggleCheckboxState('ta_checkbox_{$fototag_id}');"></div> {$fototag_info.FOTOTAG}<br>
		{/foreach}
		<div id="ta_form_buttons" class="w_dialog_form_buttons">
			<input type="button" id="ta_remove_btn" style="font-weight: bold;" value="{#lang_admin_savechanges_btn_text#}" onClick="fotosToolbar.sSaveFotosTags(); fotosToolbar.uHideDialog('tags');"> <input type="button" id="ta_cancel_btn" value="{#lang_admin_cancel_btn_text#}" onClick="fotosToolbar.uHideDialog('tags');">
		</div>
	</div>
</div>
*}

<div id="w_uploadMass_dialog" class="w_dialog" style="display: none;">
	<h3>{#lang_farch_admin_fotos_manage_addfotos_title#}</h3>
	<div id="uf_form">

		
		<div id="photoUpload" class="dropzone"></div>

		{literal}
		<script type="text/javascript">
		Dropzone.options.photoUpload = {
			url: "{/literal}{$domain}{$base_path}/admin/farch/fotos/api/{literal}",
			paramName : 'Filedata',
			params : function() {
				return {
					act : 'foto_upload',
					album_foto : album_id
				}
			},
			clickable : true,
			dictDefaultMessage : '???????????????????? ?????????? ?????? ???????????????? ?????? ????????????????????',
			acceptedFiles : '.jpg,.jpeg,.png',
			createImageThumbnails : false,
			drop : farch_uploadStart,
			queuecomplete : farch_queueComplete,
			success : farch_uploadSuccess
		};
		/*
		$(function() {
			$('#file_upload').uploadify({
				'swf'      : '/js/lib/uploadify/uploadify.swf',
				'uploader' : '',
				'buttonText': '{/literal}{#lang_admin_addfiles_btn_text#}{literal}',
				'queueID'  : 'uf_queue_div',
				'fileTypeDesc' : 'Image Files',
				'fileTypeExts' : '*.gif; *.jpg; *.png',
				'removeTimeout': 0,
				// Put your options here
				'onUploadSuccess' : farch_uploadSuccess,
				'onUploadStart' : farch_uploadStart,
				'onQueueComplete' : farch_queueComplete
			});
		});
		*/
		</script>
		{/literal}
	</div>
</div>

{*
<div id="w_upload_dialog" class="w_dialog" style="display: none;">
	<h3>{#lang_farch_admin_fotos_manage_addfoto_title#}</h3>
	<div id="uf_form">
		<div style="padding: 0; margin: 0;"><input type="file" id="uf_upload_file" name="uf_upload_file0"></div>
		<div id="uf_form_buttons" class="w_dialog_form_buttons">
		<input type="button" id="uf_upload_btn" style="font-weight: bold;" value="{#lang_farch_admin_fotos_manage_addfoto_title#}" onClick="fotosToolbar.uUploadFoto();"> <input type="button" id="ta_cancel_btn" value="{#lang_admin_cancel_btn_text#}" onClick="fotosToolbar.uHideDialog('upload');"> 
		</div>
	</div>
</div>
*}

<div id="w_edit_dialog" class="w_dialog" style="display: none;">
<h3>{#lang_farch_admin_fotos_manage_editfoto_title#}</h3>
<img id="ed_image" src=""><input type="hidden" id="ed_foto_id" value="">

<div id="ed_form" class="w_dialog_form">
<div style="padding: 5px 0;"><label for="ed_foto_link">???????????? ???? ????????????????</label> <input type="text" value="" id="ed_link" class="w_dialog_form_text" readonly /></div>
<label for="ed_foto_title">{#lang_farch_admin_fotos_manage_editfoto_descr#}</label> <input id="ed_foto_title" name="ed_foto_title" class="w_dialog_form_text multilang" type="text" value="">
<div id="ed_form_buttons" class="w_dialog_form_buttons">
<input type="button" id="ed_remove_btn" style="font-weight: bold;" value="{#lang_admin_savechanges_btn_text#}" onClick="fotosToolbar.sSaveNewFotoInfo(); fotosToolbar.uHideDialog('edit');"> <input type="button" id="ed_cancel_btn" value="{#lang_admin_cancel_btn_text#}" onClick="fotosToolbar.uHideDialog('edit');">
</div>
</div>

</div>

<div id="w_remove_dialog" class="w_dialog" style="display: none;">
<h3>{#lang_farch_admin_fotos_manage_removefoto_title#}</h3>
<input type="button" id="rd_remove_btn" value="{#lang_admin_confirm_remove#}" onClick="fotosToolbar.sRemoveSelectedFotos(); fotosToolbar.uHideDialog('remove');"> <input type="button" id="rd_cancel_btn" style="font-weight: bold;" value="{#lang_admin_cancel_remove#}" onClick="fotosToolbar.uHideDialog('remove');">
</div>

<div id="w_alerterror_dialog" class="w_dialog" style="display: none;">
<h3>{#lang_error_title#}</h3>
<p id="aed_message"></p>
<input type="button" id="aed_remove_btn" value="????" onClick="fotosToolbar.uHideDialog('alerterror');">
</div>


</div>


{*
<form action="http://inversia.local.ru/admin/farch/fotos/api/" method="POST" enctype="multipart/form-data">
<input type="hidden" name="act" value="foto_upload">
<input type="hidden" name="album_foto" value="18">
<input type="file" name="uf_upload_file0">
<input type="submit">
</form>
*}