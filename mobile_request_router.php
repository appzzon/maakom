<?php

                    ini_set('soap.wsdl_cache_enabled',0);
                    ini_set('soap.wsdl_cache_ttl',0);
if (isset($_POST) || isset($_GET) || isset($_FILES)) {
    if (!empty($_POST) || !empty($_GET) || !empty($_FILES)) {
        $request_data = array_merge($_POST, $_GET, $_FILES);
        //unset($_POST);
        unset($_GET);
        unset($_FILES);
        if (isset($request_data['type'])) {
            $arr_mobile_data = array();
            $proseduer_type = $request_data['type'];
            unset($request_data['type']);
            switch ($proseduer_type) {
                case 'enterNumber':
                    $random = rand(1000, 9999);
		    //$random = '1111';
		   $login = 'moma_api';
                    $password = 'dFe32$s@1';
                    $headers = array(
                        'Authorization: Basic '. base64_encode($login.':'.$password),
                    );
                    $fields = array(
                            'mobile_number'=>"962".ltrim($request_data['phone'], '0') ,
                            'msg'=>$random,
                            'from'=>'maakom',
                            'tag'=>'3'
                    );

                    $postvars='';
                    $sep='';
                    foreach($fields as $key=>$value)
                    {
                            $postvars.= $sep.urlencode($key).'='.urlencode($value);
                            $sep='&';
                    }
                    $url = 'https://bulksms.arabiacell.net/index.php/api/send_sms/send';
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL,$url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
                    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                    curl_setopt($ch, CURLOPT_USERPWD, base64_encode("$login:$password"));
                    curl_setopt($ch, CURLOPT_POST, count($fields));
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
                    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers
                    );
                    //$result = curl_exec($ch);
                    curl_close($ch);
                    unset($arr_data);
                    $values[] = $request_data['phone'];
                    $proceduer_name = 'check_user_number';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);
                    $row = mysqli_fetch_array($result);

                    unset($values);
                    $name="";
                    try {
                        $wsse_header = new WsseAuthHeader();
    
    $soapclient 
    = new SoapClient("wsdl.wsdl",
    array(
        "stream_context" => stream_context_create(
            array(
                'ssl' => array(
                    'verify_peer'       => false,
                )
            )
        )
    ) 
);

    $soapclient->__setSoapHeaders($wsse_header);
    $nationalNo = '9901016452';
    $param = array('nationalNo' => $nationalNo);
    
    $response = $soapclient->gePersonal($param);
    
    $array = json_decode(json_encode($response), true);
    $name='';
    function dispaly_array_recursive($array_rec) {
        if ($array_rec) {
            //print_r($array_rec);
            /*echo $array_rec['gePersonalResult']['ANAME1'].' '
                    .$array_rec['gePersonalResult']['ANAME2'].' '
                    .$array_rec['gePersonalResult']['ANAME3'].' '
                    .$array_rec['gePersonalResult']['ANAME4'].' ';
            */      
            global $name;
                     $name=$array_rec['ANAME1'].' '.$array_rec['ANAME2'].' '.$array_rec['ANAME3'].' '.$array_rec['ANAME4'];
                    
                    if($array_rec['ANAME1']!='') 
                    {
                        $arr_data['status'] = '-1';
                        global $request_data,$years,$months,$days,$random,$conn,$row,$sql;
                        $values[] = $name;
                        $values[] = $request_data['phone'];
                        $values[] = $years . '-' . $months . '-' . $days;
                        $values[] = $random;
                        $values[] = $request_data['userNumber'];
                        $values[] = $request_data['municipality_id'];

                        $conn->next_result();
                        if ($row['users_counts'] > 0) {
                            unset($values);
                            $values[] = $request_data['userNumber'];
                            $values[] = $name;
                            $values[] = $row['id'];
                            $values[] = $random;
                            $values[] = $request_data['municipality_id'];
                            $proceduer_name = 'add_user_tem';
                            $sql_str = $sql->procedure_str($proceduer_name, $values);
                            $result = $sql->query($sql_str, $conn);

                            $arr_data['id'] = $row['id'];
                        } else {
                            $proceduer_name = 'enter_number';
                            $sql_str = $sql->procedure_str($proceduer_name, $values);
                            $result = $sql->query($sql_str, $conn);

                            $row = mysqli_fetch_array($result);
                            $arr_data['id'] = $row['last_id'];
                        }
                        $arr_data['status'] = 1;
                        echo json_encode($arr_data);
		    
                    }
            foreach ($array_rec as $key => $value) {
                 if (is_array($value)) {
                    dispaly_array_recursive($value);
                } else {
                    //echo $key.' : '.$value . '<br>';
                }
            }
            
        }
    }
    dispaly_array_recursive($array);
    
                    
                    } catch (Exception $e) {
                        echo $e->getMessage();
                    }
                    
                    break;
                case 'verifyCode':
                    $values[0] = $request_data['user_id'];
                    $values[1] = $request_data['code'];

                    $proceduer_name = 'verify_code';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $row = mysqli_fetch_array($result);
                    if ($row['u_count'] == 1) {
                        $arr_data['status'] = '1';
                        $arr_data['userKey'] = $row['user_key'];

                        if ($arr_data['userKey'] == '') {
                            $arr_data['userKey'] = md5(uniqid());
                        }
                        unset($values);
                        $values[0] = $request_data['user_id'];
                        $values[1] = $arr_data['userKey'];

                        $conn->next_result();
                        $proceduer_name = 'insert_user_key';
                        $sql_str = $sql->procedure_str($proceduer_name, $values);
                        $result = $sql->query($sql_str, $conn);
                    } else {
                        $arr_data['status'] = '0';
                    }
                    echo json_encode($arr_data);

                    break;
                case 'login':
                    $values[0] = $request_data['userId'];
                    $values[1] = $request_data['userKey'];

                    $proceduer_name = 'login_users';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $arr_data['status'] = -1;

                    while ($row = mysqli_fetch_array($result)) {
                        $arr_data['status'] = '1';
                    }

                    echo json_encode($arr_data);

                    break;
                case 'mainNews':

                    $proceduer_name = 'get_main_news';
                    $values[0] = $request_data['municipality_id'];
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $i = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $arr_data['status'] = '1';
                        $arr_data['news'][$i]['id'] = $row['id'];
                        $arr_data['news'][$i]['img'] = $dir_uploads . $row['icon'];
                        $arr_data['news'][$i++]['title'] = $row['title'];
                    }

                    echo json_encode($arr_data);

                    break;
                case 'getReportsList':

                    $values[0] = $request_data['userKey'];

                    $proceduer_name = 'get_reports_list';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    unset($arr_data);
                    $i = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $arr_data['status'] = '1';
                        $arr_data['reports'][$i]['id'] = $row['id'];
                        $arr_data['reports'][$i]['reportStatus'] = $row['status'];
                        $arr_data['reports'][$i]['date'] = $row['date'];
                        $arr_data['reports'][$i]['number'] = intval($row['id']);
                        $arr_data['reports'][$i]['type'] = $row['type'];
                        $i++;
                    }
                    if ($i == 0) {
                        $arr_data['status'] = '0';
                    }
                    echo json_encode($arr_data);



                    break;
                case 'getReport':

                    $values[0] = $request_data['userKey'];
                    $values[1] = $request_data['reportId'];

                    $proceduer_name = 'get_report';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    unset($arr_data);
                    $i = 0;
                    $row = mysqli_fetch_array($result);
                    $arr_data['status'] = '1';
                    $arr_data['report']['text'] = $row['text'];
                    $arr_data['report']['type'] = $arr_report_types['reportTypes'][$row['type']]['name'];
                    $arr_data['report']['vidoe'] = "";
                    $arr_data['report']['img'] = "";
                    $arr_data['report']['voice'] = "";
                    if ($row['video'] != "")
                        $arr_data['report']['vidoe'] = $dir_uploads . $row['video'];
                    if ($row['img'] != "")
                        $arr_data['report']['img'] = $dir_uploads . $row['img'];
                    if ($row['voice'] != "")
                        $arr_data['report']['voice'] = $dir_uploads . $row['voice'];
                    $arr_data['report']['lat'] = $row['lat'];
                    $arr_data['report']['lng'] = $row['lng'];
                    $arr_data['report']['date'] = $row['date'];
                    $arr_data['report']['reportStatus'] = $row['status'];
                    $arr_data['report']['rate'] = $row['mark'];
                    $i++;


                    echo json_encode($arr_data);

                    break;
                case 'getNewsList':

                    $proceduer_name = 'get_news_list';
                    $values[] = $request_data['municipality_id'];
                    $sql_str = $sql->procedure_str($proceduer_name);
                    $result = $sql->query($sql_str, $conn);

                    $i = 0;
                    $arr_data['status'] = '-1';
                    while ($row = mysqli_fetch_array($result)) {
                        $arr_data['status'] = '1';
                        $arr_data['news'][$i]['id'] = $row['id'];
                        $arr_data['news'][$i]['img'] = $dir_uploads . $row['img'];
                        $arr_data['news'][$i++]['title'] = $row['title'];
                    }

                    echo json_encode($arr_data);

                    break;
                case 'setReport':
                    $files['video'] = $request_data['video'];
                    $files['voice'] = $request_data['voice'];
                    $files['img'] = $request_data['img'];
                    include_once __DIR__ . '/uploader.php';
                    $uploader = new N_uploader();
                    $dir_uploads = '../baladiyat2/access_files/upload_center/';
                    $files_name = $uploader->upload_files($files, $dir_uploads);

                    if ($files_name['video'] == FALSE) {
                        $files_name['video'] = '';
                    }
                    if ($files_name['voice'] == FALSE) {
                        $files_name['voice'] = '';
                    }
                    if ($files_name['img'] == FALSE) {
                        $files_name['img'] = '';
                    }

                    $values[0] = $request_data['userKey'];
                    $values[1] = $request_data['text'];
                    $values[2] = $request_data['reportType'];
                    $values[3] = $files_name['video'];
                    $values[4] = $files_name['img'];
                    $values[5] = $files_name['voice'];
                    $values[6] = $request_data['lat'];
                    $values[7] = $request_data['lng'];
                    $values[8] = $request_data['municipality_id'];

                    $proceduer_name = 'set_report';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $arr_data['status'] = '1';
                    echo json_encode($arr_data);
                    break;
                case 'getNews':

                    $values[0] = $request_data['newsId'];

                    $proceduer_name = 'get_news';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    unset($arr_data);

                    $row = mysqli_fetch_array($result);
                    $arr_data['status'] = '1';
                    $arr_data['news']['title'] = $row['title'];
                    $arr_data['news']['img'] = $dir_uploads . $row['img'];
                    $arr_data['news']['date'] = $row['date'];
                    $arr_data['news']['htmlText'] = $row['text'];

                    echo json_encode($arr_data);
                    break;
                case 'getSurvey':
                    unset($arr_data);
                    $proceduer_name = 'get_survey';
                    unset($values);
                    $values[] = $request_data['municipality_id'];
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $row = mysqli_fetch_array($result);
                    $arr_data['survey']['id'] = $row['id'];
                    $arr_data['survey']['question'] = $row['question'];
                    $arr_data['survey']['answer1'] = $row['answer1'];
                    $arr_data['survey']['answer2'] = $row['answer2'];
                    $arr_data['survey']['answer3'] = $row['answer3'];
                    $arr_data['survey']['answer4'] = $row['answer4'];

                    $conn->next_result();
                    $values[0] = $request_data['userKey'];

                    $proceduer_name = 'check_survey';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $i = 0;
                    $row = mysqli_fetch_array($result);
                    $last_survey_id = $arr_data['survey']['id'];

                    $conn->next_result();
                    unset($values);

                    $values[0] = $last_survey_id;

                    $proceduer_name = 'get_survey_result';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $i = 0;
                    $arr_data['answer_1_count'] = 0;
                    $arr_data['answer_2_count'] = 0;
                    $arr_data['answer_3_count'] = 0;
                    $arr_data['answer_4_count'] = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        if ($row['answer_id'] == 1)
                            $arr_data['answer_1_count']++;
                        else if ($row['answer_id'] == 2)
                            $arr_data['answer_2_count']++;
                        else if ($row['answer_id'] == 3)
                            $arr_data['answer_3_count']++;
                        else if ($row['answer_id'] == 4)
                            $arr_data['answer_4_count']++;
                        $i++;
                    }
                    $arr_data['results_count'] = $i;

                    echo json_encode($arr_data);

                    break;
                case 'setSurvey':

                    $values[0] = $request_data['userKey'];
                    $values[1] = $request_data['surveyId'];
                    $values[2] = $request_data['answerNumber'];

                    $proceduer_name = 'set_survey_result';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    $arr_data['status'] = 1;
                    echo json_encode($arr_data);

                    break;
                case 'setReportEvaluation':

                    $values[0] = $request_data['reportId'];
                    $values[1] = $request_data['userKey'];
                    $values[2] = $request_data['reportMark'];

                    $proceduer_name = 'update_report_mark';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);
                    $arr_data['status'] = 1;
                    echo json_encode($arr_data);

                    break;
                case 'setEvaluation':

                    $values[0] = $request_data['userKey'];
                    $values[1] = $request_data['reportMark'];

                    $proceduer_name = 'edit_evaluation';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    $arr_data['status'] = 1;
                    echo json_encode($arr_data);

                    break;
                case 'editNewsNotify':

                    $values[0] = $request_data['userKey'];
                    $values[1] = $request_data['status'];

                    $proceduer_name = 'edit_news_notify';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    $arr_data['status'] = 1;
                    echo json_encode($arr_data);

                    break;
                case 'editTendersNotify':

                    $values[0] = $request_data['userKey'];
                    $values[1] = $request_data['status'];

                    $proceduer_name = 'edit_tenders_notify';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->non_query($sql_str, $conn);

                    $arr_data['status'] = 1;
                    echo json_encode($arr_data);

                    break;
                case 'resendSMS':
                    /*
                      $values[0]= $request_data['userId'];

                      $proceduer_name = 'get_code';
                      $sql_str = $sql->procedure_str($proceduer_name,$values);
                      $result = $sql->non_query($sql_str, $conn);
                     */
                    $arr_data['status'] = 1;
                    echo json_encode($arr_data);

                    break;
                case 'getTendersList':

                    unset($values);
                    $proceduer_name = 'get_tenders_list';

                    $values[] = $request_data['municipality_id'];
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    $i = 0;
                    while ($row = mysqli_fetch_array($result)) {
                        $arr_data['status'] = '1';
                        $arr_data['tenders'][$i]['id'] = $row['id'];
                        $arr_data['tenders'][$i]['title'] = $row['title'];
                        $arr_data['tenders'][$i++]['date'] = $row['date'];
                    }

                    if ($i == 0) {
                        $arr_data['status'] = '0';
                    }
                    echo json_encode($arr_data);

                    break;
                case 'getTender':

                    $values[0] = $request_data['tenderId'];

                    $proceduer_name = 'get_tender';
                    $sql_str = $sql->procedure_str($proceduer_name, $values);
                    $result = $sql->query($sql_str, $conn);

                    unset($arr_data);
                    $i = 0;
                    $row = mysqli_fetch_array($result);
                    $arr_data['status'] = '1';
                    $arr_data['tender']['title'] = $row['title'];
                    $arr_data['tender']['date'] = $row['date'];
                    $arr_data['tender']['htmlText'] = $row['text'];
                    $i++;

                    echo json_encode($arr_data);

                    break;
                case 'getReportsTypes':

                    $arr_data['status'] = '1';

                    $arr_data['reportTypes'] = array_values($arr_report_types['reportTypes']);
                    echo json_encode($arr_data);

                    break;
                case 'getMunicipality':

                    $proceduer_name = 'get_app_municipality_list';
                    $sql_str = $sql->procedure_str($proceduer_name);
                    
                    $reports_result = $sql->query($sql_str, $conn);
                    $index = 0;
                    
                    while ($row = mysqli_fetch_array($reports_result)) {
                        $arr_data['municipalities'][$index]['id'] = $row['id'];
                        $arr_data['municipalities'][$index++]['name'] = $row['name'];
                    }
$arr_data['status'] = '1';
                    echo json_encode($arr_data);

                    break;
            }
        }
    }
}

function generatePassword($length = 8) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $count = mb_strlen($chars);

    for ($i = 0, $result = ''; $i < $length; $i++) {
        $index = rand(0, $count - 1);
        $result .= mb_substr($chars, $index, 1);
    }

    return $result;
}
class WsseAuthHeader extends SoapHeader
{
    private $wssNs = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    private $wsuNs = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd';
    private $passType = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText';
    private $nonceType = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary';
    private $username = 'MOLA';
    private $password = 'P@ss@20@20';


    function __construct()
    {
        $created = gmdate('Y-m-d\TH:i:s\Z');
        $nonce = mt_rand();
        $encodedNonce = base64_encode(pack('H*', sha1(pack('H*', $nonce) . pack('a*', $created) . pack('a*', $this->password))));
        
        // Creating WSS identification header using SimpleXML
        $root = new SimpleXMLElement('<root/>');
        
        $security = $root->addChild('wsse:Security', null, $this->wssNs);
        $usernameToken = $security->addChild('wsse:UsernameToken', null, $this->wssNs);
        $usernameToken->addChild('wsse:Username', $this->username, $this->wssNs);
        $passNode = $usernameToken->addChild('wsse:Password', htmlspecialchars($this->password, ENT_XML1, 'UTF-8'), $this->wssNs);
        $passNode->addAttribute('Type', $this->passType);
        
        $nonceNode = $usernameToken->addChild('wsse:Nonce', $encodedNonce, $this->wssNs);
        
        $nonceNode->addAttribute('EncodingType', $this->nonceType);
        $usernameToken->addChild('wsu:Created', $created, $this->wsuNs);
        
        // Recovering XML value from that object
        $root->registerXPathNamespace('wsse', $this->wssNs);
        
        $full = $root->xpath('/root/wsse:Security');
        $auth = $full[0]->asXML();
        
        parent::SoapHeader($this->wssNs, 'Security', new SoapVar($auth, XSD_ANYXML), true);

    }
};


/*
 sms link
  http://josmsservice.com/smsonline/msgservicejo.cfm?numbers=962788891089&senderid=Madaresona&AccName=Madaresona&AccPass=Madaresona123&msg=1526
 */
