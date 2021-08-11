<?php

$catalog_sections_table = "CATALOG_SECTIONS";
$catalog_goods_table = "CATALOG_GOODS";
$catalog_r_goods_sections_table = "CATALOG_R_GOODS_SECTIONS";
$catalog_prices_table = "CATALOG_PRICES";
$catalog_fotos_table = "CATALOG_FOTOS";
$catalog_tags_table = "CATALOG_TAGS";
$catalog_r_tags_goods_table = "CATALOG_R_TAGS_GOODS";

$catalog_component = true;


$catalog_admin_uri = "/admin/catalog/";
$catalog_sections_uri = "/salon/";
$catalog_collection_uri = "/collection/";


$catalog_preferred_currency = 0;
$catalog_autorecount_prices = true;

$catalog_price_rules = array(
	"preferred_currency" => $catalog_preferred_currency,
	"recount_price" => $catalog_autorecount_prices
);


$catalog_specials_count = 3;
$catalog_specials_lasttime_file = $tmpfiles_path . "/catalog_specials_lasttime";
$catalog_specials_include_sections = array(45);

?>