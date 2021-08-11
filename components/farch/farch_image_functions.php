<?

function check_image_type($image_info, $fotofile) {
	global $farch_foto_params;

	// сначала просто по расширению
	$allowed_extensions = explode(",", $farch_foto_params['allowed_extensions']);
	if (!preg_match("/^(.*)\.(\w*)$/", $fotofile['name'], $matches)) {
		return "error";
	}
	$extension = $matches[2];
	$extension_ok = false;
	foreach($allowed_extensions as $k=>$v) {
		if (trim(strtolower($v)) == trim(strtolower($extension))) {
			$extension_ok = true;
		}
	}
	if (!$extension_ok) {
		return "error";
	}

	// теперь по типу картинки
	$allowed_picture_formats = explode(",", $farch_foto_params['picture_format_numbers']);
	$picture_format_ok = false;
	foreach ($allowed_picture_formats as $v) {
		if (trim(strtolower($v)) == trim(strtolower($image_info[2]))) {
			$picture_format_ok = true;
		}
	}
	if (!$picture_format_ok) {
		return "error";
	}

	return "ok";
}


function farch_fotos_save_foto($album_id, $foto_id, $file_info) {
	global $farch_foto_params;

	// стандартна€ проверка, залит ли файл
	/*
	if (!is_uploaded_file($file_info['tmp_name'])) {
		return array("error", "BAD_FILE", array());
	}
	*/

	$tech_info = array();

	// проверка, хватит ли пам€ти дл€ обработки картинки
	$image_info = getimagesize ($file_info['tmp_name']);
	$required_memory = $image_info[0] * $image_info[1] * 3;
	if (ini_get('memory_limit') != "") {
		$memory_limit = ini_get('memory_limit');
	} elseif (get_cfg_var('memory_limit') != "") {
		$memory_limit = get_cfg_var('memory_limit');
	}
	if (preg_match("/\d+/", $memory_limit, $matches)) {
		$memory_limit = $matches[0] * 1024 * 1024;		// в байтах
	}
	if ($required_memory > $memory_limit) {
		return array("error", "POSSIBLE_NOT_ENOUGH_MEM", array());
	}

	$tech_info['original_image_info'] = $image_info;

	// проверка на допустимый формат файла
	$status = check_image_type($image_info, $file_info);
	if ($status == "error") {
		return array("error", "BAD_IMAGE_TYPE", array());
	}

	// открываем файл
	if ($image_info[2] == 3) {
		$filetype = "png";
		$im = @imagecreatefrompng ($file_info['tmp_name']);
		$tech_info['extension'] = 'png';
	}
	if ($image_info[2] == 2) {
		$filetype = "jpg";
		$im = @imagecreatefromjpeg ($file_info['tmp_name']);
		$tech_info['extension'] = 'jpg';
	}
	if ($image_info[2] == 1) {
		$filetype = "gif";
		$im = @imagecreatefromgif ($file_info['tmp_name']);
		$tech_info['extension'] = 'gif';
	}
	if (!$im) {
		return array("error", "CANT_READ_IMAGE", array());
	}

	$new_filename = "$foto_id.$filetype";

	// создаЄм папку дл€ фото, если еЄ до сих пор нет
	$path = $farch_foto_params['folders']['file'] . "/" . $album_id . "/";
	$status = common_create_directories($path);
	if ($status == "error") {
		return array("error", "CANT_CREATE_DIRS", array());
	}
	
	// копируем оригинал файла
	@copy($file_info['tmp_name'], $farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $new_filename);
	if (!is_file($farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $new_filename)) {
		return array("error", "CANT_COPY_FILE", array());
	}

	// в цикле создаем все варианты превью
	foreach($farch_foto_params['previews'] as $preview_key => $preview_params) {

		// разбираемс€ с размерами изображени€
		$width = $image_info[0];
		$height = $image_info[1];

		// работаем в зависимости от метода масштабировани€
		if ( (!isset($preview_params['resize_method'])) || ($preview_params['resize_method'] == 'simple') ) {

			if ($width > $preview_params['width']) {
				$height = $height * ( $preview_params['width']/$width);
				$width =  $preview_params['width'];
			}
			if ($height >  $preview_params['height']) {
				$width = $width * ( $preview_params['height']/$height);
				$height =  $preview_params['height'];
			}
			// cоздаем изображение
			$im_thumb = imagecreatetruecolor($width, $height);
			$transparent = imagecolorallocatealpha( $im_thumb, 0, 0, 0, 127 ); 
			imagefill( $im_thumb, 0, 0, $transparent ); 
			imagecopyresampled ($im_thumb, $im, 0, 0, 0, 0, $width, $height, $image_info[0], $image_info[1]);

		} elseif ( $preview_params['resize_method'] == 'fitwidth' ) {

			$new_width = $preview_params['width'];
			$new_height = $height * ( $new_width / $width);

			// cоздаем изображение
			$im_thumb = imagecreatetruecolor($new_width, $new_height);
			$transparent = imagecolorallocatealpha( $im_thumb, 0, 0, 0, 127 ); 
			imagefill( $im_thumb, 0, 0, $transparent ); 
			imagecopyresampled ($im_thumb, $im, 0, 0, 0, 0, $new_width, $new_height, $image_info[0], $image_info[1]);

		} elseif ( $preview_params['resize_method'] == 'square' ) {

			$max_width = $preview_params['width'];
			$max_height = $preview_params['height'];
			if ( ($width/$max_width) >= ($height/$max_height) ) {
				// уменьшаем по высоте
				$width = $width * ($max_height/$height);
				$height = $max_height;
				$dheight = 0;
				$dwidth = $width - $max_width;
			} else {
				// уменьшаем по ширине
				$height = $height * ($max_width/$width);
				$width = $max_width;
				$dwidth = 0;
				$dheight = $height - $max_height;
			}
			// cоздаем изображение
			$im_thumb = imagecreatetruecolor($max_width, $max_height);
			$transparent = imagecolorallocatealpha( $im_thumb, 0, 0, 0, 127 ); 
			imagefill( $im_thumb, 0, 0, $transparent ); 
			imagecopyresampled ($im_thumb, $im, 0, 0, $image_info[0]*($dwidth/$width)/2, $image_info[1]*($dheight/$height)/2, 
				$max_width, $max_height, 
				$image_info[0] - $image_info[0]*($dwidth/$width), 
				$image_info[1] - $image_info[1]*($dheight/$height));

		} elseif ( $preview_params['resize_method'] == 'oriented_crop' ) {

			$long_side = $preview_params['long'];
			$short_side = $preview_params['short'];

			if ($width >= $height) {
				$max_width = $long_side;
				$max_height = $short_side;
			} else {
				$max_width = $short_side;
				$max_height = $long_side;
			}

			if ( ($width/$max_width) >= ($height/$max_height) ) {
				// уменьшаем по высоте
				$width = $width * ($max_height/$height);
				$height = $max_height;
				$dheight = 0;
				$dwidth = $width - $max_width;
			} else {
				// уменьшаем по ширине
				$height = $height * ($max_width/$width);
				$width = $max_width;
				$dwidth = 0;
				$dheight = $height - $max_height;
			}
			// cоздаем изображение
			$im_thumb = imagecreatetruecolor($max_width, $max_height);
			$transparent = imagecolorallocatealpha( $im_thumb, 0, 0, 0, 127 ); 
			imagefill( $im_thumb, 0, 0, $transparent ); 
			imagecopyresampled ($im_thumb, $im, 0, 0, $image_info[0]*($dwidth/$width)/2, $image_info[1]*($dheight/$height)/2, 
				$max_width, $max_height, 
				$image_info[0] - $image_info[0]*($dwidth/$width), 
				$image_info[1] - $image_info[1]*($dheight/$height));

		} else {

			unlink($farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $new_filename);
			return array("error", "UNKNOWN_RESIZE_METHOD", array());

		}


		// сохран€ем картинку
		if ($filetype == "jpg") {
			@imagejpeg ($im_thumb, $farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $preview_params['prefix'] . $new_filename, 100);
		}
		if ($filetype == "png") {
			imagealphablending($im_thumb, false);
			imagesavealpha($im_thumb, true);
			@imagepng ($im_thumb, $farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $preview_params['prefix'] . $new_filename);
		}
		if ($filetype == "gif") {
			@imagegif ($im_thumb, $farch_foto_params['folders']['file'] . "/" . $album_id . "/" .  $preview_params['prefix'] . $new_filename);
		}
		if (!is_file($farch_foto_params['folders']['file'] .  "/" . $album_id . "/" . $preview_params['prefix'] . $new_filename)) {
			unlink($farch_foto_params['folders']['file'] .  "/" . $album_id . "/" . $new_filename);
			return array("error", "CANT_SAVE_PREVIEW", array());
		}

		$tech_info['previews'][$preview_key] = getimagesize ($farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $preview_params['prefix'] . $new_filename);

	}

	// круто, теперь мы сохранили нужные файлы, возвращаемс€
	return array("ok", $filetype, $tech_info);

}

function farch_fotos_delete_foto_files($album_id, $delete_fotos) {
	global $farch_foto_params;

	foreach($delete_fotos as $foto_id=>$foto_info) {
		$filename = $foto_id . "." . $foto_info['TECH_INFO']['extension'];
		// в цикле удал€ем все варианты превью
		foreach($farch_foto_params['previews'] as $preview_params) {
			@unlink($farch_foto_params['folders']['file'] . "/" . $album_id .  "/" . $preview_params['prefix'] . $filename);
			//echo "del ". $farch_foto_params['folders']['file'] .  "/" . $preview_params['prefix'] . $filename;
		}
		// и оригинал
		@unlink($farch_foto_params['folders']['file'] . "/" . $album_id .  "/" . $filename);
		//echo "del ". $farch_foto_params['folders']['file'] .  "/" . $filename;

	}

	return;
}

function farch_move_photo_files($album_id, $new_album_id, $move_photos) {
	global $farch_foto_params;

	$path = $farch_foto_params['folders']['file'] . "/" . $new_album_id . "/";
	$status = create_directories($path);
	if ($status == "error") {
		return array("error", "CANT_CREATE_DIRS");
	}
	foreach($move_photos as $photo_id=>$filename) { 
		// в цикле перемещаем все варианты превью
		foreach($farch_foto_params['previews'] as $preview_params) {						
			copy($farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $preview_params['prefix'] . $filename, $farch_foto_params['folders']['file'] . "/" . $new_album_id . "/" . $preview_params['prefix'] . $filename);
		}
		// и оригинал
		copy($farch_foto_params['folders']['file'] . "/" . $album_id . "/" . $filename, $gallery_photo_params['folders']['file'] . "/" . $new_album_id . "/" . $filename);
	}

	farch_fotos_delete_foto_files($album_id, $move_photos);
	return; 
}

?>