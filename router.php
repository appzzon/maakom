<?php

if ($_SESSION['valid_u']) {

    $selected_menu['main'] = '';
    $selected_menu['reports'] = '';
    $selected_menu['news'] = '';
    $selected_menu['tenders'] = '';
    $selected_menu['survey'] = '';
    $selected_menu['users'] = '';
    $selected_menu['rates'] = '';
    $selected_menu['settings'] = '';
    $selected_menu['municipality'] = '';
    $selected_menu['municipality_data'] = '';

    include_once __DIR__ . '/get_pages_data.php';
    switch ($arr_main_pages[$page_index]) {

        case $arr_main_pages[0]:
            $selected_menu['main'] = 'selected_menu';
            getPagesData::get_municipality_list();
            getPagesData::get_main_data();
            $arr_page_data['current_page'] = 'main.php';
            break;
        case $arr_main_pages[1]:
            getPagesData::get_reports_list();
            $selected_menu['reports'] = 'selected_menu';
            $arr_page_data['current_page'] = 'reports.php';
            break;
        case $arr_main_pages[2]:
            getPagesData::get_reports_data();
            $selected_menu['reports'] = 'selected_menu';
            $arr_page_data['current_page'] = 'reports_data.php';
            break;
        case $arr_main_pages[3]:
            getPagesData::get_news_list();
            $selected_menu['news'] = 'selected_menu';
            $arr_page_data['current_page'] = 'news.php';
            break;
        case $arr_main_pages[4]:
            getPagesData::get_news_data();
            $selected_menu['news'] = 'selected_menu';
            $arr_page_data['current_page'] = 'news_data.php';
            break;
        case $arr_main_pages[5]:
            getPagesData::get_tenders_list();
            $selected_menu['tenders'] = 'selected_menu';
            $arr_page_data['current_page'] = 'tenders.php';
            break;
        case $arr_main_pages[6]:
            getPagesData::get_tender_data();
            $selected_menu['tenders'] = 'selected_menu';
            $arr_page_data['current_page'] = 'tenders_data.php';
            break;
        case $arr_main_pages[7]:
            getPagesData::get_surveys_list();
            $selected_menu['survey'] = 'selected_menu';
            $arr_page_data['current_page'] = 'surveys.php';
            break;
        case $arr_main_pages[8]:
            $arr_page_data['current_page'] = 'surveys_data.php';
            break;
        case $arr_main_pages[9]:
            getPagesData::get_users_list();
            $selected_menu['users'] = 'selected_menu';
            $arr_page_data['current_page'] = 'users.php';
            break;
        case $arr_main_pages[10]:
            getPagesData::get_main_data();
            $selected_menu['rates'] = 'selected_menu';
            $arr_page_data['current_page'] = 'rates.php';
            break;
        case $arr_main_pages[11]:
            $selected_menu['settings'] = 'selected_menu';
            getPagesData::get_profile_data();
            $arr_page_data['current_page'] = 'settings.php';
            break;
        case $arr_main_pages[12]:

            getPagesData::get_user_data();
            getPagesData::get_municipality_list();
            $arr_page_data['current_page'] = 'user_data.php';
            break;
        case $arr_main_pages[13]:
            $selected_menu['app_users'] = 'selected_menu';
            getPagesData::get_app_user_data();
            getPagesData::get_municipality_list();
            $arr_page_data['current_page'] = 'app_user_data.php';

            break;
        case $arr_main_pages[14]:
            $selected_menu['municipality'] = 'selected_menu';
            getPagesData::get_municipality_list();
            $arr_page_data['current_page'] = 'municipality.php';

            break;
        case $arr_main_pages[15]:
            $selected_menu['municipality_data'] = 'selected_menu';
            getPagesData::get_municipality_data();
            $arr_page_data['current_page'] = 'municipality_data.php';

            break;
    }
    getPagesData::get_header_data();
} else
    $arr_page_data['current_page'] = 'login.php';

