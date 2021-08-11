// обновить кнопки
fotosToolbar.uRefreshToolbar = function() {
	if (fotosCollection.album_filter.length == 0) {
		$("#tb_upload_btn").attr("disabled", "disabled");
		$("#tb_uploadMass_btn").attr("disabled", "disabled");
	} else {
		$("#tb_upload_btn").removeAttr("disabled");
		$("#tb_uploadMass_btn").removeAttr("disabled");
	}
	if (fotosCollection.selected_ids.length == 0) {
		$("#tb_edit_btn").attr("disabled", "disabled");
		$("#tb_choose_btn").attr("disabled", "disabled");
		$("#tb_remove_btn").attr("disabled", "disabled");
		$("#tb_tags_btn").attr("disabled", "disabled");
	}
	if (fotosCollection.selected_ids.length > 0) {
		$("#tb_edit_btn").removeAttr("disabled");
		$("#tb_remove_btn").removeAttr("disabled");
		$("#tb_tags_btn").removeAttr("disabled");
	}
	if (fotosCollection.selected_ids.length > 0) {
		$("#tb_choose_btn").removeAttr("disabled");
		$("#tb_remove_btn").removeAttr("disabled");
		$("#tb_tags_btn").removeAttr("disabled");
	}
	if (fotosCollection.selected_ids.length > 1) {
		$("#tb_edit_btn").attr("disabled", "disabled");
		$("#tb_choose_btn").attr("disabled", "disabled");
		$("#tb_remove_btn").removeAttr("disabled");
		$("#tb_tags_btn").removeAttr("disabled");
	}
}

// показать диалог
fotosToolbar.uShowDialog = function(dlg, offset) {
	$("#w_"+dlg+"_dialog").show();
	$("#w_"+dlg+"_dialog").offset({ top: offset.top+35, left: offset.left-10 });
	//$("#w_"+dlg+"_dialog").offset({ bottom: 0, left: 0 });
}

// скрыть диалог
fotosToolbar.uHideDialog = function(dlg) {
	$("#w_"+dlg+"_dialog").hide();
}

// показать диалог ошибки
fotosToolbar.uAlertError = function(error) {
	$("#aed_message").html(error);
	fotosToolbar.uShowDialog('alerterror', $("#collection").offset());
}

fotosToolbar.uUploadFoto = function () {

	$("#uf_upload_file").upload(
		'/admin/farch/fotos/api/?act=foto_upload',
		{
			'tag_foto': fotosCollection.tag_filter,
			'album_foto': fotosCollection.album_filter
		},
		function(res) {
			if (res[0] == 'error') {
				fotosToolbar.uAlertError(res.info[0]);
			} else {
				$("#uf_upload_file").val("");
				fotosCollection.sUpdateFotos();
				fotosToolbar.uRefreshToolbar();
			}
		}, 'json');

}

// функция показывает диалог тэгов
fotosToolbar.uShowTagsDialog = function(offset) {

	if (fotosCollection.selected_ids.length == 0) {
		fotosToolbar.uAlertError(fotosTextmessages.foto_not_chosen);
		return;
	}

	$("#ta_fotos_ids").val(fotosCollection.selected_ids.join(','));
	$("#ta_fotos_count").html(fotosCollection.selected_ids.length);

	$.ajax({
		type: "POST",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=fotos_get_tags&fotos_ids='+fotosCollection.selected_ids.join(','),
		success: function(data) {
			st = data[0];
			if (st == "error") {
				fotosToolbar.uAlertError(data.info);
			}
			if (st == "ok") {
				r_fotos_tags_info = data.info;
				$.each(r_fotos_tags_info, function(tag_id, tag_info) {
					if (tag_info.FOTOTAG_STATE == 'clear') {
						fotosToolbar.setCheckboxState('ta_checkbox_'+tag_id, 'clear');
						//$("#ta_selector_"+tag_id).removeProp("checked");
						//$("#ta_selector_"+tag_id).removeProp("indeterminate");
					}
					if (tag_info.FOTOTAG_STATE == 'checked') {
						fotosToolbar.setCheckboxState('ta_checkbox_'+tag_id, 'checked');
						//$("#ta_selector_"+tag_id).prop("checked", "checked");
						//$("#ta_selector_"+tag_id).removeProp("indeterminate");
					}
					if (tag_info.FOTOTAG_STATE == 'mixed') {
						fotosToolbar.setCheckboxState('ta_checkbox_'+tag_id, 'indeterminated');
						//$("#ta_selector_"+tag_id).removeProp("checked");
						//$("#ta_selector_"+tag_id).prop("indeterminate", "indeterminate");
					}
				});
				fotosToolbar.uShowDialog("tags", offset);
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			fotosToolbar.uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});


}

// сохранение и применение фильтра тэгов
fotosToolbar.SetTagFilter = function() {
	tag_id = $("#tb_filter_select").val();
	if (tag_id == 0) {
		fotosCollection.tag_filter = [];
	} else {
		fotosCollection.tag_filter[0] = tag_id;
	}
	fotosCollection.uUnselectAll();
	fotosCollection.sUpdateFotos();
	fotosToolbar.uRefreshToolbar();
}

// сохранение и применение фильтра альбомов
fotosToolbar.SetAlbumFilter = function() {
	album_id = $("#tb_album_filter_select").val();
	if (album_id == 0) {
		fotosCollection.album_filter = [];
	} else {
		fotosCollection.album_filter[0] = album_id;
	}
	fotosCollection.uUnselectAll();
	fotosCollection.sUpdateFotos();
	fotosToolbar.uRefreshToolbar();
}

fotosToolbar.chooseFoto = function(offset) {
	if (fotosCollection.selected_ids.length == 0) {
		fotosToolbar.uAlertError(fotosTextmessages.foto_not_chosen);
		return;
	}
	if (fotosCollection.selected_ids.length > 1) {
		fotosToolbar.uAlertError(fotosTextmessages.choosed_more_than_one);
		return;
	}
	foto_id = fotosCollection.selected_ids[0];
	$.ajax({
		type: "POST",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=foto_info&foto_id='+foto_id,
		success: function(data) {
			st = data[0];
			if (st == "error") {
				fotosToolbar.uAlertError(data.info);
			}
			if (st == "ok") {
				foto_info = data.info;
				foto_title = foto_info.FOTO_TITLE;
				foto_src = fotosToolbar.fotos_link_path+'/'+fotosCollection.album_filter[0]+'/'+fotosToolbar.fotos_chosen_preview_prefix+foto_id+'.'+foto_info.TECH_INFO.extension;
				if (fotosToolbar.choose_foto_type != '') {
					window.opener.farchRecieveChosenFoto(foto_id, foto_title, foto_src, fotosToolbar.choose_foto_type);
				} else {
					window.opener.farchRecieveChosenFoto(foto_id, foto_title, foto_src);
				}
				window.close();
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});

}


// функция заполняет диалог редактирования фоточки и включает его
fotosToolbar.sShowEditDialog = function(offset) {
	if (fotosCollection.selected_ids.length == 0) {
		fotosToolbar.uAlertError(fotosTextmessages.foto_not_chosen);
		return;
	}
	if (fotosCollection.selected_ids.length > 1) {
		fotosToolbar.uAlertError(fotosTextmessages.choosed_more_than_one);
		return;
	}
	foto_id = fotosCollection.selected_ids[0];
	$.ajax({
		type: "POST",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=foto_info&foto_id='+foto_id,
		success: function(data) {
			st = data[0];
			if (st == "error") {
				fotosToolbar.uAlertError(data.info);
			}
			if (st == "ok") {
				foto_info = data.info;
				$("#ed_foto_id").val(foto_id);
				$("#ed_foto_title").val(foto_info.FOTO_TITLE);
				$("#ed_image").attr("src", "");
				$("#ed_image").attr("src", fotosToolbar.fotos_link_path+'/'+fotosCollection.album_filter[0]+'/'+fotosToolbar.fotos_dialog_preview_prefix+foto_id+'.'+foto_info.TECH_INFO.extension);
				fotosToolbar.uShowDialog("edit", offset);
				$("#ed_link").val(fotosToolbar.fotos_link_path+'/'+fotosCollection.album_filter[0]+'/'+fotosToolbar.fotos_dialog_preview_prefix+foto_id+'.'+foto_info.TECH_INFO.extension);
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});
}

// функция сохраняет инфу по фоточке
fotosToolbar.sSaveNewFotoInfo = function() {
	foto_id = $("#ed_foto_id").val();
	foto_title = $("#ed_foto_title").val();
	$.ajax({
		type: "POST",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=foto_info_update&foto_id='+foto_id+'&foto_title='+foto_title,
		success: function(data) {
			st = data[0];
			if (st == "error") {
				fotosToolbar.uAlertError(data.info);
			}
			if (st == "ok") {
				fotosCollection.selected_ids = [];
				fotosCollection.sUpdateFotos();
				fotosToolbar.uRefreshToolbar();
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			fotosToolbar.uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});
}

// удаление выбранных фоточек
fotosToolbar.sRemoveSelectedFotos = function () {
	if (fotosCollection.selected_ids.length == 0) {
		fotosToolbar.uAlertError(fotosTextmessages.foto_not_chosen);
		return;
	}
	//fotosToolbar.uAlertError('act=remove_fotos&fotos_ids='+fotosCollection.selected_ids.join(','));
	//return;
	$.ajax({
		type: "POST",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=fotos_remove&fotos_ids='+fotosCollection.selected_ids.join(','),
		success: function(data) {
			st = data[0];
			if (st == "error") {
				fotosToolbar.uAlertError(data.info);
			}
			if (st == "ok") {
				fotosCollection.selected_ids = [];
				fotosCollection.sUpdateFotos();
				fotosToolbar.uRefreshToolbar();
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			fotosToolbar.uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});
}



// функция сохранения тэгов
fotosToolbar.sSaveFotosTags = function () {

	// соберем информацию о том, над какими фоточками производится действие
	fotos_ids = $("#ta_fotos_ids").val().split(',');
	if (fotos_ids.length == 0) {
		fotosToolbar.uAlertError(fotosTextmessages.foto_not_chosen);
		return;
	}

	// соберем информацию о том, какие тэги и как будут изменены
	tags_str = [];
	$.each($("#ta_form .ar_checkbox"), function () {
		//alert($(this).prop("indeterminate"));
		state = fotosToolbar.getCheckboxState($(this).attr('id'));
		if (state != 'indeterminated') {	// неопределённые значения не трогаем вообще
			tag_id = $(this).attr('id').substr(12);	// remove first "ta_checkbox_" letters
			tags_str[tags_str.length] = "fototag_id_" + tag_id + "=" + state;
		}
	});

	$.ajax({
		type: "POST",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=fotos_save_tags&fotos_ids='+fotos_ids+'&'+tags_str.join('&'),
		success: function(data) {
			st = data[0];
			if (st == "error") {
				uAlertError(data.info);
			}
			if (st == "ok") {
				fotosCollection.selected_ids = [];
				fotosCollection.sUpdateFotos();
				fotosToolbar.uRefreshToolbar();
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			fotosToolbar.uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});

}

fotosToolbar.setCheckboxState = function(checkbox_id, state) {

	if (state == 'clear') {
		$("#"+checkbox_id).addClass("ar_checkbox_clear");
		$("#"+checkbox_id).removeClass("ar_checkbox_checked");
		$("#"+checkbox_id).removeClass("ar_checkbox_indeterminated");
	}

	if (state == 'checked') {
		$("#"+checkbox_id).addClass("ar_checkbox_checked");
		$("#"+checkbox_id).removeClass("ar_checkbox_clear");
		$("#"+checkbox_id).removeClass("ar_checkbox_indeterminated");
	}

	if (state == 'indeterminated') {
		$("#"+checkbox_id).addClass("ar_checkbox_indeterminated");
		$("#"+checkbox_id).removeClass("ar_checkbox_checked");
		$("#"+checkbox_id).removeClass("ar_checkbox_clear");
	}

}

fotosToolbar.getCheckboxState = function(checkbox_id) {

	if ($("#"+checkbox_id).hasClass('ar_checkbox_checked')) {
		return 'checked';
	}

	if ($("#"+checkbox_id).hasClass('ar_checkbox_indeterminated')) {
		return 'indeterminated';
	}

	return 'clear';

}

fotosToolbar.toggleCheckboxState = function(checkbox_id) {

	state = fotosToolbar.getCheckboxState(checkbox_id);

	if (state == 'indeterminated') {
		fotosToolbar.setCheckboxState(checkbox_id, 'checked');
		return;
	}
	if (state == 'checked') {
		fotosToolbar.setCheckboxState(checkbox_id, 'clear');
		return;
	}
	if (state == 'clear') {
		fotosToolbar.setCheckboxState(checkbox_id, 'checked');
		return;
	}

}

// handler, который делает проверки при отправке фоточки
function farch_uploadStart() {
	if (fotosCollection.album_filter.length > 0) {
		album_id = fotosCollection.album_filter[0];
	} else {
		fotosToolbar.uAlertError(fotosTextmessages.album_not_chosen);
		return;
	}
}

// handler, который вызывается после загрузки всех файлов
function farch_queueComplete(numFilesUploaded) {
	this.removeAllFiles();
	fotosToolbar.uHideDialog('uploadMass');
	fotosCollection.sUpdateFotos();
	fotosToolbar.uRefreshToolbar();
}

// handler, который вызывается после получения ответа от скрипта, закачавшего фото
function farch_uploadSuccess(file, data) {
	if (data != '1') {
		fotosToolbar.uAlertError(fotosTextmessages.file_upload_error + file.name + "<br><br>" + data);
	}
}

function farch_stopAllUploading() {
	$('#file_upload').uploadify('cancel', '*');
	$('#umf_cancelAll_btn').attr('disabled', 'disabled');
}