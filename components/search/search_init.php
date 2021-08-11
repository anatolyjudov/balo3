<?php

multilang_load_texts("search");

multilang_load_smarty_config("search");

/* программные настройки и глобальные переменные компонента */
require("$components_path/search/search_config.php");

/* функции базы данных компонента */
require("$components_path/search/search_db.php");

/* функции поиска */
require("$components_path/search/search_functions.php");

?>