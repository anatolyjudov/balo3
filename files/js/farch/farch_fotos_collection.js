// обновление коллекции
fotosCollection.sUpdateFotos = function() {
	tag_query_str = '';
	album_query_str = '';
	if (fotosCollection.tag_filter.length > 0) {
		tag_query_str = "&tag="+fotosCollection.tag_filter[0];
	}
	if (fotosCollection.album_filter.length > 0) {
		album_query_str = "&album="+fotosCollection.album_filter[0];
	} else {
		fotosCollection.uShowMessage(fotosTextmessages.choose_album);
		return;
	}
	//fotosToolbar.uAlertError('act=fotos_list&skip='+fotosCollection.skip+'&limit='+fotosCollection.limit+tag_query_str+album_query_str+'&'+Math.random());
	//return;
	$.ajax({
		type: "GET",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=fotos_list&skip='+fotosCollection.skip+'&limit='+fotosCollection.limit+tag_query_str+album_query_str+'&'+Math.random(),
		success: function(data) {
			st = data[0];
			if (st == "error") {
				fotosToolbar.uAlertError(data.info.toString());
			}
			if (st == "ok") {
				fotosCollection.uShowFotos(data.info, 'clear');
				fotosCollection.refreshSorting();
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			fotosToolbar.uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});
}

// функция сохранения сортировки
fotosCollection.saveSorting = function (event, ui) {
	//alert("1");

	var fotos_order_info = $(fotosCollection.collection_container_id).sortable('serialize', {key : 'fs[]'});

	//fotosToolbar.uAlertError('act=fotos_save_order&'+fotos_order_info);

	$.ajax({
		type: "GET",
		dataType: "json",
		url: '/admin/farch/fotos/api/',
		data: 'act=fotos_save_order&'+fotos_order_info,
		success: function(data) {
			st = data[0];
			if (st == "error") {
				fotosToolbar.uAlertError(data[1]);
			}
			if (st == "ok") {
			}
		},
		error : function (jqXHR, textStatus, errorThrown) {
			fotosToolbar.uAlertError(jqXHR + " : " + textStatus + " : " + errorThrown);
		}
	});

}

fotosCollection.refreshSorting = function () {

	if (fotosCollection.tag_filter.length > 0) {
		$(fotosCollection.collection_container_id).sortable("disable");
	} else {
		$(fotosCollection.collection_container_id).sortable({
			tolerance: 'pointer',
			update: function(event, ui) {
				fotosCollection.saveSorting(event, ui);
			}
		});
		$(fotosCollection.collection_container_id).sortable("enable");
		$(fotosCollection.collection_container_id).disableSelection();
	}

}

// показать в коллекции информационное сообщение
fotosCollection.uShowMessage = function(message) {

	fotosCollection.uClear();
	fotosCollection.selected_ids = [];
	$(fotosCollection.collection_container_id).append('<div class="fc_message">' + message + '</div>');

}

// отображение фоточек в коллекции
fotosCollection.uShowFotos = function(fotos_list, old_fotos) {

	if (old_fotos == 'clear') {
		fotosCollection.uClear();
	}

	if (fotos_list.length == 0) {
		fotosCollection.uShowMessage(fotosTextmessages.no_fotos_in_album);
	}

	$.each(fotos_list, function(n, foto_info) {
		foto_id = foto_info.FOTO_ID;
		tmp_newfoto_id = 'foto_'+foto_id;
		$(fotosCollection.collection_container_id).append('<div class="foto" id="'+tmp_newfoto_id+'"></div>');
		$("#"+tmp_newfoto_id).append('<img class="preview" src="'+fotosCollection.fotos_link_path+'/'+foto_info.ALBUM_ID+'/'+fotosCollection.fotos_preview_prefix+foto_id+'.'+foto_info.TECH_INFO.extension+'">');
		//alert(tmp_newfoto_id);
		$("#"+tmp_newfoto_id+" img").click(function () {
			if (fotosCollection.isFotoSelected(foto_info.FOTO_ID)) {
				fotosCollection.uUnselectFoto(foto_info.FOTO_ID);
			} else {
				fotosCollection.uSelectFoto(foto_info.FOTO_ID);
			}
		});
		$("#"+tmp_newfoto_id+" img").dblclick(function () {
			fotosCollection.uUnselectAll();
			fotosCollection.uSelectFoto(foto_info.FOTO_ID);
			if (fotosCollection.dbl_click_action == 'choose') {
				fotosToolbar.chooseFoto($(this).offset());
			} else {
				fotosToolbar.sShowEditDialog($(this).offset());
			}
			
		});
	});

}

// очистить коллекцию от всех фоточек
fotosCollection.uClear = function() {
	$(fotosCollection.collection_container_id).empty();
}

// отметить фотографию как выделенную
fotosCollection.uSelectFoto = function (foto_id) {
	//alert(foto_id);
	$("#foto_"+foto_id+" img").addClass("selected");
	if ($.inArray(foto_id, fotosCollection.selected_ids) == -1) {
		fotosCollection.selected_ids[fotosCollection.selected_ids.length] = foto_id;
	}
	fotosToolbar.uRefreshToolbar();
}

// снять выделение с фотографии
fotosCollection.uUnselectFoto = function (foto_id) {
	$("#foto_"+foto_id+" img").removeClass("selected");
	if ($.inArray(foto_id, fotosCollection.selected_ids) > -1) {
		fotosCollection.selected_ids.splice($.inArray(foto_id, fotosCollection.selected_ids), 1);
	}
	fotosToolbar.uRefreshToolbar();
}

// выделить все фото
fotosCollection.uSelectAll = function () {
	$.each($(fotosCollection.collection_container_id+" div.foto"), function () {
		foto_id = $(this).attr('id').slice(5);
		fotosCollection.uSelectFoto(foto_id);
	})
	fotosToolbar.uRefreshToolbar();
}

// снять выделение со всех фото
fotosCollection.uUnselectAll = function () {
	$.each($(fotosCollection.collection_container_id+" div.foto"), function () {
		foto_id = $(this).attr('id').slice(5);
		fotosCollection.uUnselectFoto(foto_id);
	})
	fotosToolbar.uRefreshToolbar();
}

// выделена ли фоточка
fotosCollection.isFotoSelected = function (foto_id) {
	if ($.inArray(foto_id, fotosCollection.selected_ids) > -1) {
		return true;
	} else {
		return false;
	}
}
