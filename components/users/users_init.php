<?php


/* программные настройки и глобальные переменные компонента */
require("$components_path/users/users_config.php");

/* функции базы данных компонента */
require("$components_path/users/users_texts.php");

/* утилиты */
require("$components_path/users/users_functions.php");

/* функции базы данных */
require("$components_path/users/users_db.php");

multilang_load_smarty_config("users");

/* инициализируем сессию */
users_init_session();

// ПРОВЕРКА ПРАВ ДОСТУПА, С УЧЕТОМ ПРАВ РОЛЕЙ ПОЛЬЗОВАТЕЛЯ
// проверка на наличие у пользователя прав на доступ к этой ветке
if ( !users_db_check_rights( users_get_user_id(), 'ACCESS_NODE', $balo3_node_info['node_path'] ) ) {
	balo3_error403();
	exit;
}

?>
