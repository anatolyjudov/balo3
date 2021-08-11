<?php

error_reporting(E_ALL ^ E_NOTICE ^ E_STRICT ^ E_DEPRECATED);

setlocale (LC_ALL, 'ru_RU.utf-8', 'rus_RUS.utf-8', 'ru_RU.utf8');
mb_internal_encoding('UTF-8');

/* файловый путь к расположению entrypoint.php */
$entrypoint_path = substr($_SERVER['SCRIPT_FILENAME'], 0, -1*strlen($_SERVER['SCRIPT_NAME']));

require("$entrypoint_path/balo3/init.php");


?>