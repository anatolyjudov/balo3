<?php

require_once("$entrypoint_path/components/test/test_config.php");
require_once("$entrypoint_path/components/test/test_functions.php");

balo3_load_texts("test");
balo3_load_smarty_config("test");

echo "test_init " . $entrypoint_path;

?>