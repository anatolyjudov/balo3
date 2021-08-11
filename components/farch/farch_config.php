<?php

$farch_album_uri_level_user = 2;

$farch_component = true;

$farch_fotos_table = "FARCH_FOTOS";
$farch_albums_table = "FARCH_ALBUMS";
$farch_tags_table = "FARCH_FOTOTAGS";
$farch_r_fotos_tags_table = "FARCH_R_FOTOS_TAGS";
$farch_rel_posts_table = "R_ALBUMS_POSTS";
$farch_rel_authors_table = "R_AUTHORS_ALBUMS";
$farch_rel_fotos_table = "FARCH_FOTOS_RELATIONS";


# размеры и префиксы картинок-превью
$farch_foto_params = array(
	"previews" => array(
		"big" => array(
			"prefix" => "main_",
			"width" => "1300",
			"height" => "900",
			"resize_method" => "simple"
		),
		"edit" => array(
			"prefix" => "edit_",
			"width" => "700",
			"height" => "400",
			"resize_method" => "simple"
		),
		"list" => array(
			"prefix" => "list_",
			"width" => "220",
			"height" => "300",
			"resize_method" => "square"
		),
		"small" => array(
			"prefix" => "small_",
			"width" => "500",
			"height" => "85",
			"resize_method" => "simple"
		),
		"card" => array(
			"prefix" => "card_",
			"width" => "400",
			"height" => "550",
			"resize_method" => "simple"
		),
		"nlist" => array(
			"prefix" => "nlist_",
			"long" => "300",
			"short" => "200",
			"resize_method" => "oriented_crop"
		),
	),
	"folders" => array(
		"link" => $root_path . "/pictures",
		"file" => $files_path . "/pictures"
	),
	"allowed_extensions" => "gif,jpg,png",
	"picture_format_numbers" => "1,2,3"
);


?>