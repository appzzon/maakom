<?php

if (isset($_POST) || isset($_GET) || isset($_FILES)) {
    if (!empty($_POST) || !empty($_GET) || !empty($_FILES)) {
        $request_data = array_merge($_POST, $_GET, $_FILES);
        unset($_POST);
        //unset($_GET);
        unset($_FILES);
        if (isset($request_data['type'])) {
            $proseduer_type = $request_data['type'];
            unset($request_data['type']);
            switch ($proseduer_type) {
                case 'login':
                    $values[] = $request_data['user_name'];
                    $values[] = $request_data['password'];
                    $proceduer_name = 'admin_login';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);
                    $row = mysqli_fetch_array($result);
                    if ($row['u_count'] > 0) {
                        $_SESSION['valid_u'] = TRUE;
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['user_name'] = $row['name'];
                        $_SESSION['user_type'] = $row['user_type'];
                        $_SESSION['privileges'] = $row['privileges'];
                        echo '1';
                    } else {
                        echo 'no';
                    }

                    $get_layout = FALSE;
                    break;
                case 'logout':
                    $_SESSION['valid_u'] = FALSE;
                    unset($_SESSION['user_id']);
                    unset($_SESSION['user_name']);
                    unset($_SESSION['user_type']);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'edit_news':
                    $files['img'] = $request_data['img'];
                    $files['icon'] = $request_data['icon'];
                    include_once __DIR__ . '/uploader.php';
                    $uploader = new N_uploader();
                    $dir_uploads_folder = 'access_files/upload_center/';
                    $files_name = $uploader->upload_files($files, $dir_uploads_folder);
                    if ($files_name['img'] == FALSE) {
                        $files_name['img'] = $request_data['old_img'];
                    }
                    if ($files_name['icon'] == FALSE) {
                        $files_name['icon'] = $request_data['old_icon'];
                    }
                    unset($values);

                    $values[0] = $_SESSION['url_number'];
                    $values[1] = $request_data['title'];
                    $values[2] = $files_name['icon'];
                    $values[3] = $files_name['img'];
                    $values[4] = $request_data['news_txt'];
                    $proceduer_name = 'edit_news';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'add_news':
                    $files['img'] = $request_data['img'];
                    $files['icon'] = $request_data['icon'];
                    include_once __DIR__ . '/uploader.php';
                    $uploader = new N_uploader();
                    $dir_uploads_folder = 'access_files/upload_center/';
                    $files_name = $uploader->upload_files($files, $dir_uploads_folder);
                    if ($files_name['img'] == FALSE) {
                        $files_name['img'] = "";
                    }
                    if ($files_name['icon'] == FALSE) {
                        $files_name['icon'] = "";
                    }
                    unset($values);

                    $values[0] = $request_data['title'];
                    $values[1] = $files_name['icon'];
                    $values[2] = $files_name['img'];
                    $values[3] = $request_data['news_txt'];
                    $values[4] = $_SESSION['user_type'];
                    $proceduer_name = 'add_news';
                    $msg_txt = 'خبر: ' . $request_data['title'];
                    $msg_topic = 'news';
                    send_notification($msg_txt, $msg_topic);

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);



                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'delete_news':

                    $values[0] = $request_data['id'];
                    $proceduer_name = 'delete_news';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'survey_data':
                    $values[0] = $request_data['id'];
                    $proceduer_name = 'get_survey_result';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $i = 0;
                    $arr_page_data['answer_1_count'] = 0;
                    $arr_page_data['answer_2_count'] = 0;
                    $arr_page_data['answer_3_count'] = 0;
                    $arr_page_data['answer_4_count'] = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['answer_id'] == 1)
                            $arr_page_data['answer_1_count']++;
                        else if ($row['answer_id'] == 2)
                            $arr_page_data['answer_2_count']++;
                        else if ($row['answer_id'] == 3)
                            $arr_page_data['answer_3_count']++;
                        else if ($row['answer_id'] == 4)
                            $arr_page_data['answer_4_count']++;
                        $i++;
                    }
                    $arr_page_data['results_count'] = $i;
                    $arr_page_data['answer_persent_1'] = ($arr_page_data['answer_1_count'] / $arr_page_data['results_count']) * 100;
                    $arr_page_data['answer_persent_2'] = ($arr_page_data['answer_2_count'] / $arr_page_data['results_count']) * 100;
                    $arr_page_data['answer_persent_3'] = ($arr_page_data['answer_3_count'] / $arr_page_data['results_count']) * 100;
                    $arr_page_data['answer_persent_4'] = ($arr_page_data['answer_4_count'] / $arr_page_data['results_count']) * 100;

                    $conn->next_result();
                    $proceduer_name = 'get_survey_data';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);
                    $row = mysqli_fetch_array($result);

                    $last_id = $row['id'];
                    $arr_page_data['last_surveys']['question'] = $row['question'];
                    $arr_page_data['last_surveys']['answer1'] = $row['answer1'];
                    $arr_page_data['last_surveys']['answer2'] = $row['answer2'];
                    $arr_page_data['last_surveys']['answer3'] = $row['answer3'];
                    $arr_page_data['last_surveys']['answer4'] = $row['answer4'];
                    include __DIR__ . '/../layouts/sub_pages/survey_statistics.php';

                    $get_layout = FALSE;
                    break;
                case 'edit_tender':

                    unset($values);

                    $values[0] = $_SESSION['url_number'];
                    $values[1] = $request_data['title'];
                    $values[2] = $request_data['date'];
                    $values[3] = $request_data['tender_txt'];
                    $proceduer_name = 'edit_tender';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'edit_municipality_data':

                    unset($values);

                    $values[0] = $_SESSION['user_type'];
                    $values[1] = $request_data['municipality_name'];
                    $values[2] = $request_data['main_phone'];
                    $values[3] = $request_data['report_phone'];
                    $values[4] = $request_data['tender_phone'];
                    $values[5] = $request_data['lat'];
                    $values[6] = $request_data['lng'];
                    $values[7] = $request_data['email'];
                    $proceduer_name = 'edit_municipality_data';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);

                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'add_tender':

                    unset($values);

                    $values[0] = $request_data['title'];
                    $values[1] = $request_data['date'];
                    $values[2] = $request_data['tender_txt'];
                    $values[3] = $_SESSION['user_type'];
                    $proceduer_name = 'add_tender';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    $msg_txt = 'عطاء' . $values[0];
                    $msg_topic = 'tenders';
                    send_notification($msg_txt, $msg_topic);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'delete_tender':

                    $values[0] = $request_data['id'];
                    $proceduer_name = 'delete_tender';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'edit_admin':

                    unset($values);

                    $values[0] = $_SESSION['url_number_user'];
                    $values[1] = $request_data['u_name'];
                    $values[2] = $request_data['phone'];
                    $values[3] = $request_data['u_email'];
                    $values[4] = $request_data['full_name'];
                    $values[5] = $request_data['user_number'];
                    //$values[6] = $request_data['password'];
                    $proceduer_name = 'edit_admin';
                    
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    
                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';
                    
                    $get_layout = FALSE;
                    break;
                case 'edit_user_admin':

                    unset($values);

                    $values[0] = $_SESSION['user_id'];
                    $values[1] = $request_data['u_name'];
                    $values[2] = $request_data['phone'];
                    $values[3] = $request_data['u_email'];
                    $values[4] = $request_data['full_name'];
                    $values[5] = $request_data['user_number'];
                    $proceduer_name = 'edit_admin';
                    
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';
                    
                    $get_layout = FALSE;
                    break;
                case 'add_admin':

                    unset($values);

                    $values[0] = $request_data['u_name'];
                    $values[1] = $request_data['phone'];
                    $values[2] = $request_data['u_email'];
                    $values[3] = $request_data['full_name'];
                    $values[4] = $request_data['user_number'];
                    $values[5] = $request_data['password'];
                    if ($_SESSION['user_type'] == 0) {
                        $values[6] = $request_data['user_type'];
                        $values[7] = '1';
                    } else {
                        $values[6] = $_SESSION['user_type'];
                        $values[7] = $request_data['user_privileges'];
                    }
                    $proceduer_name = 'add_admin';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'add_municipality':

                    unset($values);

                    $values[0] = $request_data['municipality_name'];
                    $proceduer_name = 'add_municipality';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;

                case 'delete_admin':

                    $values[0] = $request_data['id'];
                    $proceduer_name = 'delete_admin';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'add_servey':

                    unset($values);

                    $values[0] = $request_data['question'];
                    $values[1] = $request_data['answer_1'];
                    $values[2] = $request_data['answer_2'];
                    $values[3] = $request_data['answer_3'];
                    $values[4] = $request_data['answer_4'];
                    $values[5] = $_SESSION['user_type'];
                    $proceduer_name = 'add_survey';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'delete_survey':

                    $values[0] = $request_data['id'];
                    $proceduer_name = 'delete_survey';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'delete_municipality':

                    $values[0] = $request_data['id'];
                    $proceduer_name = 'delete_municipality';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'edit_municipality':

                    $values[] = $request_data['id'];
                    $values[] = $request_data['name'];
                    $proceduer_name = 'edit_municipality';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'change_report_status':

                    $values[0] = $request_data['id'];
                    $values[1] = $request_data['status'];
                    $proceduer_name = 'update_report_status';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'change_report_note':

                    $values[0] = $_SESSION['url_number'];
                    ;
                    $values[1] = $request_data['note'];
                    $proceduer_name = 'update_report_note';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'change_in_report_status':

                    $values[] = $_SESSION['url_number'];
                    $values[] = $request_data['status'];
                    $values[] = $_SESSION['user_id'];
                    $proceduer_name = 'update_report_status';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    echo '1';

                    $get_layout = FALSE;
                    break;
                case 'update_profile':

                    $values[] = $_SESSION['user_id'];
                    $values[] = $request_data['user_name'];
                    $values[] = $request_data['phone'];
                    $values[] = $request_data['email'];
                    $values[] = $request_data['full_name'];
                    $values[] = $request_data['user_number'];
                    $values[] = $request_data['pass'];
                    $proceduer_name = 'update_profile';

                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);
                    $i = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $i++;
                    }
                    if ($i > 0)
                        echo '1';
                    else
                        echo 'no';

                    $get_layout = FALSE;
                    break;
                case 'update_profile_pass':
                    if ($request_data['old_pass'] == '' || $request_data['old_pass'] == '' || $request_data['old_pass'] == '') {
                        echo 'fill';
                    } else {
                        $values[] = $_SESSION['user_id'];
                        $values[] = $request_data['old_pass'];
                        $values[] = $request_data['new_pass'];
                        $proceduer_name = 'update_profile_pass';

                        $sql_str = $sql->procedure_str($proceduer_name, $values);
                        $result = $sql->query($sql_str, $conn);
                        $i = 0;
                        while ($row = mysqli_fetch_array($result)) {
                            $i++;
                        }
                        if ($i > 0)
                            echo '1';
                        else
                            echo 'no';
                    }
                    $get_layout = FALSE;
                    break;
                case 'search_reports':
                    $sql_str = 'SELECT reports.id,reports.type,reports.lat ,reports.lng,reports.status,reports.date ,users.number AS phone FROM reports 
                        LEFT JOIN users ON users.user_key = reports.user_key WHERE 1=1';
                    if ($request_data['report_status'] != '-1') {
                        $sql_str .= ' AND `status`=' . $request_data['report_status'];
                    }
                    if ($request_data['report_type'] != '0') {
                        $sql_str .= ' AND `type`=' . $request_data['report_type'];
                    }
                    if ($request_data['report_date'] != '') {
                        $sql_str .= ' AND `date`="' . $request_data['report_date'] . '"';
                    }
                    $sql_str .= ' ORDER BY `reports`.`id` DESC ';
                    $result = $sql->query($sql_str, $conn);

                    echo'<tr id="tr_last_report_titles">
                                    <td id="td_print_reports" class="tds_clicable" title="طباعة" url=""><span class="fa fa-print spans_print spans_header_icons" id="print_reports_icon"></span></td>
                                    <td>
                                        رقم البلاغ
                                    </td>
                                    <td>
                                        نوع البلاغ
                                    </td>
                                    <td>
                                        تاريخ البلاغ
                                    </td>
                                    <td>
                                        رقم المبلغ
                                    </td>
                                    <td>
                                        حالة البلاغ
                                    </td>
                                </tr>';
                    while ($row = mysqli_fetch_array($result)) {
                        $key = $row['id'];

                        echo '<tr>
                           
                            <td title="تفاصيل" class="tds_edit_reports tds_clicable" 
                                url="' . $root_link . $arr_main_pages[2] . '/' . $key . '"> 
                                <span class="spans_edite fa fa-eye"></span>
                            </td>
                            <td>
                                ' . $key . '
                            </td>
                            <td>
                                ' . $arr_report_types['reportTypes'][$row['type']]['name'] . '
                            </td>
                            <td>
                                ' . $row['date'] . '
                            </td>
                            <td>
                                ' . $row['phone'] . '
                            </td>
                            <td>
                                ' . $arr_report_status[$row['status']] . '
                            </td>
                        </tr>';
                    }

                    $get_layout = FALSE;
                    break;
                case 'search_news':
                    $sql_str = 'SELECT * FROM `news` WHERE 1';
                    if ($request_data['news_title'] != '') {
                        $sql_str .= '  AND `title` like"%' . $request_data['news_title'] . '%"';
                    }
                    if ($request_data['news_data'] != '') {
                        $sql_str .= ' AND `date`="' . $request_data['news_data'] . '"';
                    }
                    $result = $sql->query($sql_str, $conn);
                    echo $sql_str;
                    echo'<tr id="tr_last_report_titles">
                            <td class="tds_clicable" title="إضافة" url="<?php echo $root_link.$arr_main_pages[4] ?>"><span class="fa fa-plus spans_add spans_header_icons" id="add_news_icon"></span></td>
                            <td ></td>
                            <td>
                               عنوان الخبر
                            </td>
                            <td>
                                تاريخ الخبر
                            </td>
                            <td>
                                شعار الخبر
                            </td>
                        </tr>';
                    while ($row = mysqli_fetch_array($result)) {
                        $key = $row['id'];
                        echo '<tr class="tr_last_report_content" id="tr_' . $key . '">
                                <td title="حذف" class="tds_delete_news tds_clicable" id="' . $key . '" url=""> 
                                    <span class="spans_delete fa fa-times"></span>
                                </td>
                                <td title="تفاصيل" class="tds_edit_reports tds_clicable" 
                                    url="' . $root_link . $arr_main_pages[4] . '/' . $key . '"> 
                                    <span class="spans_edite fa fa-eye"></span>
                                </td>
                                <td>
                                    ' . $row['title'] . '
                                </td>
                                <td>
                                    ' . $row['date'] . '
                                </td>
                                <td>
                                    <img class="img_news" src="' . $dir_uploads . $row['icon'] . '"/>
                                </td>
                            </tr>';
                    }

                    $get_layout = FALSE;
                    break;
                case 'search_tender':
                    $sql_str = 'SELECT * FROM `tenders` WHERE 1';
                    if ($request_data['tender_title'] != '') {
                        $sql_str .= '  AND `title` like "%' . $request_data['tender_title'] . '%"';
                    }
                    if ($request_data['tender_date'] != '') {
                        $sql_str .= ' AND `date`="' . $request_data['tender_date'] . '"';
                    }
                    $result = $sql->query($sql_str, $conn);
                    echo'<tr id="tr_last_report_titles">
                            <td class="tds_clicable" title="إضافة" url="<?php echo $root_link.$arr_main_pages[6] ?>"><span class="fa fa-plus spans_add spans_header_icons" id="add_news_icon"></span></td>
                            <td></td>

                            <td>
                               اسم العطاء
                            </td>
                            <td>
                                تاريخ العطاء
                            </td>
                        </tr>';
                    while ($row = mysqli_fetch_array($result)) {
                        $key = $row['id'];
                        echo '<tr id="tr_' . $key . '" class="tr_last_report_content">
                            <td title="حذف" class="tds_delete_tenders tds_clicable" id="' . $key . '" url=""> 
                                <span class="spans_delete fa fa-times"></span>
                            </td>
                            <td title="تفاصيل" class="tds_edit_reports tds_clicable" 
                                url="' . $root_link . $arr_main_pages[6] . '/' . $key . '"> 
                                <span class="spans_edite fa fa-eye"></span>
                            </td>
                            <td>
                                ' . $row['title'] . ' 
                            </td>
                            <td>
                                ' . $row['date'] . ' 
                            </td>
                        </tr>';
                    }


                    $get_layout = FALSE;
                    break;
                case 'get_municipalitiy_reports':
                    $values[] = $request_data['municipality_id'];
                    $proceduer_name = 'get_count_reports';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $reports_count_result = $sql->query($sql_str, $conn);


                    $arr_page_data['reports_count']['sum'] = 0;
                    while ($row = mysqli_fetch_array($reports_count_result)) {
                        $arr_page_data['reports_count'][$row['status']] = $row['count'];
                        $arr_page_data['reports_count']['sum'] += $row['count'];
                    }
                    if (!isset($arr_page_data['reports_count'][0])) {
                        $arr_page_data['reports_count'][0] = 0;
                    }
                    if (!isset($arr_page_data['reports_count'][1])) {
                        $arr_page_data['reports_count'][1] = 0;
                    }
                    if (!isset($arr_page_data['reports_count'][2])) {
                        $arr_page_data['reports_count'][2] = 0;
                    }
                    $arr_page_data['reports_count_val']['0'] = ($arr_page_data['reports_count']['0'] / $arr_page_data['reports_count']['sum']) * 100;
                    $arr_page_data['reports_count_val']['1'] = ($arr_page_data['reports_count']['1'] / $arr_page_data['reports_count']['sum']) * 100;
                    $arr_page_data['reports_count_val']['2'] = ($arr_page_data['reports_count']['2'] / $arr_page_data['reports_count']['sum']) * 100;
                    include __DIR__ . '/../layouts/sub_pages/municipality_reports.php';
                    $get_layout = FALSE;
                    break;
                case 'forget_password':
                    $email=$request_data['email'];
                    $values[] = $email;
                    $proceduer_name = 'forget_user';
                    
                    $sql_str = $sql->procedure_str($proceduer_name,$values);
                    $result = $sql->query($sql_str, $conn);
                    
                    $row = mysqli_fetch_array($result);
                    if( $row['name']!="")
                    {
                    $txt = '<div style="text-align: center">
                                    <table class="m_7390791542312790281MsoNormalTable" style="border-collapse:collapse;display: 
                                    inline-block;" cellspacing="0" cellpadding="0" border="0"><tbody><tr>
                                    <td style="padding:0in 0in 0in 0in"><table class="m_7390791542312790281MsoNormalTable" 
                                    style="width:100.0%;background:whitesmoke;border-collapse:collapse" width="100%" 
                                    cellspacing="0" cellpadding="0" border="0"><tbody><tr><td style="padding:0in 15.0pt 0in 15.0pt" 
                                    valign="top"><div align="center"><table class="m_7390791542312790281MsoNormalTable" 
                                    style="width:100.0%;border-collapse:collapse" width="100%" cellspacing="0" cellpadding="0" 
                                    border="0"><tbody><tr style="height:75.0pt"><td style="padding:0in 15.0pt 0in 15.0pt;height:75.0pt">
                                    <p class="MsoNormal" style="text-align:center;line-height:15.0pt" align="center">
                                    <span style="font-size:10.0pt;font-family:Arial&quot;,&quot;sans-serif&quot;;color:#545454">
                                    <img id="m_7390791542312790281_x0000_i1025" alt="Madaresona Logo" class="CToWUd" 
                                    src="' . $dir_files_images . 'tawasol_logo.png' . '"><u></u><u></u></span></p></td></tr>'
                            . '</tbody></table></div><p class="MsoNormal"><u></u>&nbsp;<u></u></p><div align="center">'
                            . '<table class="m_7390791542312790281MsoNormalTable" style="width:100.0%;border-collapse:collapse;background-clip:padding-box" width="100%" cellspacing="0" cellpadding="0" border="0"><tbody>'
                            . '<tr><td style="padding:0in 0in 0in 0in;background-clip:padding-box;overflow:hidden" valign="top">'
                            . '<table class="m_7390791542312790281MsoNormalTable" style="width:100.0%;background:white;background-clip:padding-box;border-radius:3px" '
                            . 'width="100%" cellspacing="0" cellpadding="0" border="0"><tbody><tr><td style="padding:11.25pt 15.0pt 11.25pt 15.0pt"><p '
                            . 'style="margin-right:0in;margin-bottom:7.5pt;margin-left:0in;line-height:15.0pt">'
                            . ''
                            . '<span style="font-size:11.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#545454">Hi, <u>'
                            . '</u><u></u></span></p><p style="margin:0in;margin-bottom:.0001pt;line-height:15.0pt">'
                            . '<span style="font-size:10.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#545454">You have '
                            . '</span></p><p style="margin:0in;margin-bottom:.0001pt;line-height:15.0pt">'
                            . '<span style="font-size:10.5pt;font-family:&quot;Arial&quot;,&quot;sans-serif&quot;;color:#545454">'
                            . '<u></u><u></u></span></p><p style="margin-right:0in;margin-bottom:3.75pt;margin-left:0in;line-height:15.0pt">'
                            . '<span style="color:white;background:#76b51b;text-decoration:none">'
                            . 'Your user name is: ' . $row['name']
                            . '<br>your password is: ' . $row['password'] . '</span></p></td></tr></table>
                                </div>';

                    // Always set content-type when sending HTML email
                    $headers = "MIME-Version: 1.0" . "\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                    // More headers
                    $headers .= 'From: <info@massarapp.com/>' . "\r\n";
                    $headers .= 'Cc: ananejem@gmail.com' . "\r\n";
                    $subject = 'Massar User Data';
                    $to = '';
                    mail($to, $subject, $txt, $headers);

                    $status = '1';

                    }
                    else
                    {
                        $status = '0';
                    }
                    echo $status;
                    $get_layout = FALSE;
                    break;
            }
        }
    }
}

function send_notification($msg_txt, $msg_topic) {
    define('API_ACCESS_KEY', 'AAAAcwSN0I0:APA91bGhJ_y5403htojuxmXAN_EkdSHew1udHx5x4yERFESSLw_BYw83xHxio2hu_vscUWc6D6r18wlSZ0sTjbM5HF05V67ortLZG-cPvTKkNY0l96cRBwA3n-Q4O_x_ag4brzUKx2CE');
    //   $registrationIds = ;
    #prep the bundle
    $msg = array
        (
        'body' => $msg_txt,
        'title' => '',
    );
    $fields = array
        (
        'to' => '/topics/' . $msg_topic,
        'notification' => $msg
    );


    $headers = array
        (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );
#Send Reponse To FireBase Server	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);

    curl_close($ch);
}

/*
    SELECT * FROM markets WHERE markets.location = 3 AND markets.city = 1 AND markets.name_ar LIKE '%%' AND markets.section_id = 2
 * 
 *********************************************************
 * 
 * 
    SELECT products.id,products.name_ar,products.name_en,products.imgs,products.market_id FROM options_data 
LEFT JOIN products ON products.id = options_data.product_id
WHERE ((options_data.option_id=4 AND options_data.name_ar LIKE '%ض%') or (options_data.option_id=5 AND options_data.name_ar > 2014)) AND products.market_id IN (3,5,6)
 */