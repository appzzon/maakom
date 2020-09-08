<?php

Class getPagesData {

    public static function get_header_data() {
        global $arr_page_data;
        global $sql;
        global $conn;

        $conn->next_result();

        $values[] = $_SESSION['user_type'];
        $proceduer_name = 'get_sections_count';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $section_result = $sql->query($sql_str, $conn);

        $row = mysqli_fetch_array($section_result);
        $arr_page_data['reports_header_count'] = $row['report_count'];
        $arr_page_data['tenders_header_count'] = $row['tenders_count'];
        $arr_page_data['news_header_count'] = $row['news_count'];
        $arr_page_data['survey_header_count'] = $row['survey_count'];
        $arr_page_data['users_header_count'] = $row['user_count'];

        $arr_page_data['municipality_count'] = $row['municipality_count'];
        $arr_page_data['all_users_count'] = $row['all_users_count'];
        $arr_page_data['admins_count'] = $row['admins_count'];
        $arr_page_data['marks_counts'] = $row['marks_counts'];

        $conn->next_result();
        $proceduer_name = 'get_notify_count';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $result = $sql->query($sql_str, $conn);
        $row = mysqli_fetch_array($result);

        $arr_page_data['notify_count'] = $row['notfy_count'];
    }

    public static function get_main_data() {
        global $arr_page_data;
        global $sql;
        global $conn;
        global $arr_report_types;

        $conn->next_result();
        $values[] = 10;
        $values[] = $_SESSION['user_type'];
        $proceduer_name = 'get_last_reports';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_result = $sql->query($sql_str, $conn);

        while ($row = mysqli_fetch_array($reports_result)) {
            $arr_page_data['reports'][$row['id']]['type'] = $arr_report_types['reportTypes'][$row['type']]['name'];
            $arr_page_data['reports'][$row['id']]['date'] = $row['date'];
            $arr_page_data['reports'][$row['id']]['phone'] = $row['phone'];
            $arr_page_data['reports'][$row['id']]['status'] = $row['status'];
            $arr_page_data['reports'][$row['id']]['lat'] = $row['lat'];
            $arr_page_data['reports'][$row['id']]['lng'] = $row['lng'];
        }

        unset($values);
        $conn->next_result();
        $values[] = $_SESSION['user_type'];
        $proceduer_name = 'get_count_reports';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_count_result = $sql->query($sql_str, $conn);


        $arr_page_data['reports_count']['sum'] = 0;
        while ($row = mysqli_fetch_array($reports_count_result)) {
            $arr_page_data['reports_count'][$row['status']] = $row['count'];
            $arr_page_data['reports_count']['sum'] += $row['count'];
        }
        $arr_page_data['reports_count_val']['0'] = ($arr_page_data['reports_count']['0'] / $arr_page_data['reports_count']['sum']) * 100;
        $arr_page_data['reports_count_val']['1'] = ($arr_page_data['reports_count']['1'] / $arr_page_data['reports_count']['sum']) * 100;
        $arr_page_data['reports_count_val']['2'] = ($arr_page_data['reports_count']['2'] / $arr_page_data['reports_count']['sum']) * 100;
        $conn->next_result();

        unset($values);
        $values[] = $_SESSION['user_type'];
        $proceduer_name = 'get_users_marks';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        if ($_SESSION['user_type'] == 0) {
            $proceduer_name = 'get_all_users_marks';
            $sql_str = $sql->procedure_str($proceduer_name);
        }
        $reports_count_result = $sql->query($sql_str, $conn);
        $arr_page_data['marks_count']['sum'] = 0;
        $arr_page_data['marks_count']['count'] = 0;
        while ($row = mysqli_fetch_array($reports_count_result)) {

            $arr_page_data['marks_count'][$row['mark']] = $row['count'];
            if ($row['mark'] > 0) {
                $arr_page_data['marks_count']['sum'] += ($row['count'] * $row['mark']);
                $arr_page_data['marks_count']['count'] += $row['count'];
            }
        }
        $arr_page_data['marks_count_val']['avg'] = round($arr_page_data['marks_count']['sum'] / $arr_page_data['marks_count']['count']);
        $arr_page_data['marks_count_val']['5'] = intval(($arr_page_data['marks_count']['5']));
        $arr_page_data['marks_count_val']['4'] = intval(($arr_page_data['marks_count']['4']));
        $arr_page_data['marks_count_val']['3'] = intval(($arr_page_data['marks_count']['3']));
        $arr_page_data['marks_count_val']['2'] = intval(($arr_page_data['marks_count']['2']));
        $arr_page_data['marks_count_val']['1'] = intval(($arr_page_data['marks_count']['1']));

        $conn->next_result();
        $proceduer_name = 'reports_marks';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        if ($_SESSION['user_type'] == 0) {
            $proceduer_name = 'reports_all_marks';
            $sql_str = $sql->procedure_str($proceduer_name);
        }
        $reports_count_result = $sql->query($sql_str, $conn);

        $arr_page_data['users_marks_count']['sum'] = 0;
        $arr_page_data['users_marks_count']['count'] = 0;
        while ($row = mysqli_fetch_array($reports_count_result)) {

            $arr_page_data['users_marks_count'][$row['mark']] = $row['count'];
            $arr_page_data['users_marks_count']['sum'] += ($row['count'] * $row['mark']);
            $arr_page_data['users_marks_count']['count'] += $row['count'];
        }
        $arr_page_data['user_marks_count_val']['avg'] = round($arr_page_data['users_marks_count']['sum'] / $arr_page_data['users_marks_count']['count']);
        $arr_page_data['user_marks_count_val']['5'] = intval(($arr_page_data['users_marks_count']['5']));
        $arr_page_data['user_marks_count_val']['4'] = intval(($arr_page_data['users_marks_count']['4']));
        $arr_page_data['user_marks_count_val']['3'] = intval(($arr_page_data['users_marks_count']['3']));
        $arr_page_data['user_marks_count_val']['2'] = intval(($arr_page_data['users_marks_count']['2']));
        $arr_page_data['user_marks_count_val']['1'] = intval(($arr_page_data['users_marks_count']['1']));
    }

    public static function get_reports_list() {
        $page_count = 10;
        global $arr_page_data;
        global $sql;
        global $conn;
        global $arr_report_types;

        $pagedata = new getPagesData();
        $page = $pagedata->get_url_number();

        $conn->next_result();
        $values[1] = $page_count;
        $values[0] = $page_count * $page;
        $values[2] = $_SESSION['user_type'];
        $proceduer_name = 'get_web_reports_list';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_result = $sql->query($sql_str, $conn);
        while ($row = mysqli_fetch_array($reports_result)) {
            $arr_page_data['reports'][$row['id']]['type'] = $arr_report_types['reportTypes'][$row['type']]['name'];
            ;
            $arr_page_data['reports'][$row['id']]['date'] = $row['date'];
            $arr_page_data['reports'][$row['id']]['phone'] = $row['phone'];
            $arr_page_data['reports'][$row['id']]['status'] = $row['status'];
            $arr_page_data['reports'][$row['id']]['lat'] = $row['lat'];
            $arr_page_data['reports'][$row['id']]['lng'] = $row['lng'];
            $arr_page_data['reports'][$row['id']]['seen'] = $row['report_seen'];
        }
    }

    public static function get_municipality_list() {
        global $arr_page_data;
        global $sql;
        global $conn;
        global $arr_report_types;
        
        $pagedata = new getPagesData();
        $page = $pagedata->get_url_number();

        $ii = 0;
        $conn->next_result();
        $proceduer_name = 'get_municipality_list';
        $sql_str = $sql->procedure_str($proceduer_name);

        $reports_result = $sql->query($sql_str, $conn);
        $arr_page_data['municipalities_sum']['status_sum1'] = 0;
        $arr_page_data['municipalities_sum']['status_sum2'] = 0;
        $arr_page_data['municipalities_sum']['status_sum3'] = 0;
        $arr_page_data['municipalities_sum']['status_sum'] = 0;
        while ($row = mysqli_fetch_array($reports_result)) {
            $arr_page_data['municipalities'][$row['id']]['name'] = $row['name'];
            if ($row['status'] == '0') {
                $arr_page_data['municipalities'][$row['id']]['status_1']++;
                $arr_page_data['municipalities'][$row['id']]['status_sum']++;
                $arr_page_data['municipalities_sum']['status_sum1']++;
                $arr_page_data['municipalities_sum']['status_sum']++;
            } else if ($row['status'] == '1') {
                $arr_page_data['municipalities'][$row['id']]['status_2']++;
                $arr_page_data['municipalities'][$row['id']]['status_sum']++;
                $arr_page_data['municipalities_sum']['status_sum2']++;
                $arr_page_data['municipalities_sum']['status_sum']++;
            } else if ($row['status'] == '2') {
                $arr_page_data['municipalities'][$row['id']]['status_3']++;
                $arr_page_data['municipalities'][$row['id']]['status_sum']++;
                $arr_page_data['municipalities_sum']['status_sum3']++;
                $arr_page_data['municipalities_sum']['status_sum']++;
            }
        }
        $arr_page_data['municipalities_sum']['sum']=count($arr_page_data['municipalities']);
    }

    public static function get_reports_data() {

        global $arr_page_data;
        global $sql;
        global $conn;
        global $arr_report_types;

        $pagedata = new getPagesData();
        $id = $pagedata->get_url_number();

        $conn->next_result();
        $proceduer_name = 'get_web_reports_data';
        $sql_str = $sql->procedure_str($proceduer_name, $id);

        $reports_result = $sql->query($sql_str, $conn);
        $row = mysqli_fetch_array($reports_result);
        $arr_page_data['reports']['id'] = $row['id'];
        $arr_page_data['reports']['date'] = $row['date'];
        $arr_page_data['reports']['type'] = $arr_report_types['reportTypes'][$row['type']]['name'];
        ;
        $arr_page_data['reports']['status'] = $row['status'];
        $arr_page_data['reports']['lat'] = $row['lat'];
        $arr_page_data['reports']['lng'] = $row['lng'];
        $arr_page_data['reports']['text'] = $row['text'];
        $arr_page_data['reports']['video'] = $row['video'];
        $arr_page_data['reports']['img'] = $row['img'];
        $arr_page_data['reports']['mark'] = $row['mark'];
        $arr_page_data['reports']['user_name'] = $row['name'];
        $arr_page_data['reports']['user_phone'] = $row['phone'];
        $arr_page_data['reports']['user_number'] = $row['number'];
        $arr_page_data['reports']['note'] = $row['note'];
    }

    public static function get_municipality_data() {

        global $arr_page_data;
        global $sql;
        global $conn;
        global $arr_report_types;

        $pagedata = new getPagesData();
        $id = $pagedata->get_url_number();

        $conn->next_result();
        $proceduer_name = 'get_municipality_data';
        $sql_str = $sql->procedure_str($proceduer_name, $_SESSION['user_type']);

        $reports_result = $sql->query($sql_str, $conn);
        $row = mysqli_fetch_array($reports_result);
        $arr_page_data['municipality']['id'] = $row['id'];
        $arr_page_data['municipality']['name'] = $row['name'];
        $arr_page_data['municipality']['logo'] = $row['logo'];
        ;
        $arr_page_data['municipality']['main_phone'] = $row['main_phone'];
        $arr_page_data['municipality']['reports_phone'] = $row['reports_phone'];
        $arr_page_data['municipality']['tenders_phone'] = $row['tenders_phone'];
        $arr_page_data['municipality']['email'] = $row['email'];
        $arr_page_data['municipality']['lat'] = $row['lat'];
        $arr_page_data['municipality']['lng'] = $row['lng'];
    }

    public static function get_news_list() {
        $page_count = 10;
        global $arr_page_data;
        global $sql;
        global $conn;

        $pagedata = new getPagesData();
        $page = $pagedata->get_url_number();

        $conn->next_result();
        $values[1] = $page_count;
        $values[0] = $page_count * $page;
        $values[2] = $_SESSION['user_type'];
        $proceduer_name = 'get_web_news_list';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_result = $sql->query($sql_str, $conn);
        while ($row = mysqli_fetch_array($reports_result)) {
            $arr_page_data['news'][$row['id']]['title'] = $row['title'];
            $arr_page_data['news'][$row['id']]['icon'] = $row['icon'];
            $arr_page_data['news'][$row['id']]['date'] = $row['date'];
        }
    }

    public static function get_news_data() {
        global $arr_page_data;
        global $sql;
        global $conn;
        $pagedata = new getPagesData();
        $id = $pagedata->get_url_number();
        if ($id > 0) {
            $conn->next_result();
            $proceduer_name = 'get_web_news_data';
            $sql_str = $sql->procedure_str($proceduer_name, $id);

            $reports_result = $sql->query($sql_str, $conn);
            $row = mysqli_fetch_array($reports_result);
            $arr_page_data['news']['id'] = $row['id'];
            $arr_page_data['news']['title'] = $row['title'];
            $arr_page_data['news']['icon'] = $row['icon'];
            $arr_page_data['news']['img'] = $row['img'];
            $arr_page_data['news']['text'] = $row['text'];
            $arr_page_data['news']['date'] = $row['date'];
        }
    }

    public static function get_tenders_list() {
        $page_count = 10;
        global $arr_page_data;
        global $sql;
        global $conn;

        $pagedata = new getPagesData();
        $page = $pagedata->get_url_number();

        $conn->next_result();
        $values[1] = $page_count;
        $values[0] = $page_count * $page;
        $values[2] = $_SESSION['user_type'];
        $proceduer_name = 'get_web_tenders_list';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_result = $sql->query($sql_str, $conn);

        while ($row = mysqli_fetch_array($reports_result)) {
            $arr_page_data['tender'][$row['id']]['title'] = $row['title'];
            $arr_page_data['tender'][$row['id']]['date'] = $row['date'];
        }
    }

    public static function get_tender_data() {
        global $arr_page_data;
        global $sql;
        global $conn;
        $pagedata = new getPagesData();
        $id = $pagedata->get_url_number();
        if ($id > 0) {
            $conn->next_result();
            $proceduer_name = 'get_web_tender_data';
            $sql_str = $sql->procedure_str($proceduer_name, $id);

            $reports_result = $sql->query($sql_str, $conn);
            $row = mysqli_fetch_array($reports_result);
            $arr_page_data['tender']['date'] = $row['date'];
            $arr_page_data['tender']['title'] = $row['title'];
            $arr_page_data['tender']['text'] = $row['text'];
        }
    }

    public static function get_surveys_list() {
        $page_count = 10;
        global $arr_page_data;
        global $sql;
        global $conn;

        $pagedata = new getPagesData();
        $page = $pagedata->get_url_number();

        $conn->next_result();
        $values[1] = $page_count;
        $values[0] = $page_count * $page;
        $values[2] = $_SESSION['user_type'];
        $proceduer_name = 'get_web_surveys_list';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_result = $sql->query($sql_str, $conn);

        $last_id = 0;
        while ($row = mysqli_fetch_array($reports_result)) {
            if ($last_id == 0) {
                $last_id = $row['id'];
                $arr_page_data['last_surveys']['question'] = $row['question'];
                $arr_page_data['last_surveys']['answer1'] = $row['answer1'];
                $arr_page_data['last_surveys']['answer2'] = $row['answer2'];
                $arr_page_data['last_surveys']['answer3'] = $row['answer3'];
                $arr_page_data['last_surveys']['answer4'] = $row['answer4'];
            }
            $arr_page_data['surveys'][$row['id']]['question'] = $row['question'];
            $arr_page_data['surveys'][$row['id']]['answer1'] = $row['answer1'];
            $arr_page_data['surveys'][$row['id']]['answer2'] = $row['answer2'];
            $arr_page_data['surveys'][$row['id']]['answer3'] = $row['answer3'];
            $arr_page_data['surveys'][$row['id']]['answer4'] = $row['answer4'];
        }
        if ($last_id != 0) {
            $conn->next_result();
            unset($values);
            $values[0] = $last_id;
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
        }
    }

    public static function get_survey_data() {
        global $arr_page_data;
        global $sql;
        global $conn;
        $pagedata = new getPagesData();
        $id = $pagedata->get_url_number();
        if ($id > 0) {
            $conn->next_result();
            $proceduer_name = 'get_web_survey_data';
            $sql_str = $sql->procedure_str($proceduer_name, $id);

            $reports_result = $sql->query($sql_str, $conn);
            $row = mysqli_fetch_array($reports_result);
            $arr_page_data['user']['name'] = $row['name'];
            $arr_page_data['user']['password'] = $row['password'];
            $arr_page_data['user']['phone'] = $row['phone'];
            $arr_page_data['user']['email'] = $row['email'];
            $arr_page_data['user']['full_name'] = $row['full_name'];
            $arr_page_data['user']['admin_id'] = $row['admin_id'];
        }
    }

    public static function get_users_list() {
        $page_count = 10;
        global $arr_page_data;
        global $sql;
        global $conn;

        $pagedata = new getPagesData();
        $page = $pagedata->get_url_number();

        $conn->next_result();
        $values[1] = $page_count;
        $values[0] = $page_count * $page;
        $proceduer_name = 'get_all_web_users_list';

        if ($_SESSION['user_type'] != 0) {
            $values[2] = $_SESSION['user_type'];
            $proceduer_name = 'get_web_users_list';
        }
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_result = $sql->query($sql_str, $conn);

        while ($row = mysqli_fetch_array($reports_result)) {
            $arr_page_data['users'][$row['id']]['name'] = $row['name'];
        }
    }

    public static function get_user_data() {
        global $arr_page_data;
        global $sql;
        global $conn;
        $pagedata = new getPagesData();
        $id = $pagedata->get_url_number();
        echo $_SESSION['url_number'];
        if ($id > 0) {
            $conn->next_result();
            $proceduer_name = 'get_web_user_data';
            $sql_str = $sql->procedure_str($proceduer_name, $id);

            $reports_result = $sql->query($sql_str, $conn);
            $row = mysqli_fetch_array($reports_result);
            $arr_page_data['user']['name'] = $row['name'];
            $arr_page_data['user']['password'] = $row['password'];
            $arr_page_data['user']['phone'] = $row['phone'];
            $arr_page_data['user']['email'] = $row['email'];
            $arr_page_data['user']['full_name'] = $row['full_name'];
            $arr_page_data['user']['admin_id'] = $row['admin_id'];
            $arr_page_data['user']['type'] = $row['user_type'];
            $arr_page_data['user']['user_privileges'] = $row['privileges'];
        }
    }

    public static function get_app_user_data() {
        $page_count = 10;
        global $arr_page_data;
        global $sql;
        global $conn;

        $pagedata = new getPagesData();
        $page = $pagedata->get_url_number();

        $conn->next_result();
        $values[1] = $page_count;
        $values[0] = $page_count * $page;
        $proceduer_name = 'get_web_app_users';
        $sql_str = $sql->procedure_str($proceduer_name, $values);
        $reports_result = $sql->query($sql_str, $conn);

        while ($row = mysqli_fetch_array($reports_result)) {
            $arr_page_data['app_users'][$row['id']]['name'] = $row['name'];
            $arr_page_data['app_users'][$row['id']]['number'] = $row['number'];
            $arr_page_data['app_users'][$row['id']]['user_id_number'] = $row['user_id_number'];
        }
    }

    public static function get_profile_data() {
        global $arr_page_data;
        global $sql;
        global $conn;

        $id = $_SESSION['user_id'];
        if ($id > 0) {
            $conn->next_result();
            $proceduer_name = 'get_web_user_data';
            $sql_str = $sql->procedure_str($proceduer_name, $id);

            $reports_result = $sql->query($sql_str, $conn);
            $row = mysqli_fetch_array($reports_result);
            $arr_page_data['user']['name'] = $row['name'];
            $arr_page_data['user']['password'] = $row['password'];
            $arr_page_data['user']['phone'] = $row['phone'];
            $arr_page_data['user']['email'] = $row['email'];
            $arr_page_data['user']['full_name'] = $row['full_name'];
            $arr_page_data['user']['admin_id'] = $row['admin_id'];
        }
    }

    public static function get_user_setting_data() {
        global $arr_page_data;
        global $sql;
        global $conn;

        $id = $_SESSION['user_id'];
        if ($id > 0) {
            $conn->next_result();
            $proceduer_name = 'get_web_user_data';
            $sql_str = $sql->procedure_str($proceduer_name, $id);

            $reports_result = $sql->query($sql_str, $conn);
            $row = mysqli_fetch_array($reports_result);
            $arr_page_data['user']['name'] = $row['name'];
            $arr_page_data['user']['password'] = $row['password'];
            $arr_page_data['user']['phone'] = $row['phone'];
            $arr_page_data['user']['email'] = $row['email'];
            $arr_page_data['user']['full_name'] = $row['full_name'];
            $arr_page_data['user']['admin_id'] = $row['admin_id'];
        }
    }

    public function get_url_number() {
        global $arr_link_inputs;
        $id = '0';
        foreach ($arr_link_inputs as $key => $value) {
            if (is_numeric($value)) {
                $id = $value;
                array_splice($arr_link_inputs, $key, 1);
                $_SESSION['url_number'] = $id;
                $_SESSION['url_num'] = $id;
                return $id;
            }
        }
        $_SESSION['url_number'] = $id;
        $_SESSION['url_num'] = $id;
        return $id;
    }

    public function shuffle_assoc($list) {
        if (!is_array($list))
            return $list;

        $keys = array_keys($list);
        shuffle($keys);
        $random = array();
        foreach ($keys as $key) {
            $random[$key] = $list[$key];
        }
        return $random;
    }

}
