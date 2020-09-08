<?php
if(!isset($request_data['link']))
{
    $current_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}
 else {
    $current_link = $request_data['link'];
}
$arr_current_link = str_replace("http://$_SERVER[HTTP_HOST]/".$root_folder, "",$current_link);

$arr_link_inputs = explode('/', $arr_current_link);
if(in_array($arr_link_inputs[0], $lang_array))
{
    $language->setLang($arr_link_inputs[0]);
    array_splice($arr_link_inputs, 0, 1);
}
$page_index = 0;
foreach ($arr_link_inputs as $key => $value) {
    if(in_array($value, $arr_main_pages))
    {
        $page_index = array_search($value, $arr_main_pages);
        array_splice($arr_link_inputs, $key, 1);
    }
}

$new_current_link = "http://$_SERVER[HTTP_HOST]/"
                    .$root_folder
                    .$arr_main_pages[$page_index];
$arr_page_data = array();
$arr_page_data['current_page']='main.php';