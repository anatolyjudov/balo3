<?

# meta db table
$meta_table = "META_META";

$meta_component = true;

$meta_title_concating = false;
$meta_title_concating_string = " / ";

$meta_error_states = array(
'none' => "defined by parent",
'403' => "page not accessible (error 403)",
'404' => "page not exists (error 404)",
'+403' => "page and its branch not accessible (error 403)",
'+404' => "page and its branch not exists (ошибка 404)",
'-403' => "override 403 error for page and its branch",
'-404' => "override 404 error for page and its branch"
);


// подключать или не подключать компонент farch
$meta_farch_component = true;


?>