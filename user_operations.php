<?php
class N_user_operations
{
    function new_user($name,$password,$email)
    {
        include_once 'n_mysqli.php';
        $mySqli = new N_MySqli();
        $conn = $mySqli->db_conn();

         $t_name = 'user';
        $cols_name =NULL;
        $condition = ' user="'.$name.'"';
        $str_string = $mySqli->select_str($t_name, $cols_name, $condition);
        $i=0;
        
        $result = $mySqli->query($str_string, $conn);
        while ($row = mysqli_fetch_array($result))
        {
            $i++;
        }
        $condition = ' email="'.$email.'"';
        $str_string = $mySqli->select_str($t_name, $cols_name, $condition);
        $j=0;
        
        $result = $mySqli->query($str_string, $conn);
        while ($row = mysqli_fetch_array($result))
        {
            $j++;
        }
        if($i<=0 && $j<=0)
        {
            $key = hash("gost",$name,0);
            $t_name = 'user';
            $cols_name[0] = 'user';
            $cols_name[1] = 'password';
            $cols_name[2] = 'email';
            $cols_name[3] = 'key';

            $cols_var[0] = $name;
            $cols_var[1] = $password;
            $cols_var[2] = $email;
            $cols_var[3] =  $key;
            $sql_string=$mySqli->insert_str($t_name, $cols_name, $cols_var);
            $subject = 'كود التفعيل';
            $message='كود التفعيل: http://wawlearning.wawtechno.com/?key='.$key;
             mail($email,$subject,$message);
            
            $mySqli->non_query($sql_string, $conn);
            $success = "لتفعيل حسابك يرجى مراجعة البريد الالكتروني";
            include_once './layouts/success.php';
        }
        else 
        {
            $error = "";
            if($i>0)
                $error = "اسم المستخدم موجود مسبقا يرجى تغييره";
            else if($j>0)
                 $error .= "البريد الالكتروني موجود سابقا يرجى تغييره";
            
             include_once './layouts/errors.php';
        }
            
            $mySqli->closeConn($conn);
    }
    
    function confirm_user($key)
    {
        include_once 'n_mysqli.php';
        $mySqli = new N_MySqli();
        $conn = $mySqli->db_conn();
        
         $t_name = 'user';
         $cols_name[0]='block_tag';
         $cols_var[0]='1';
         $condition = ' `key` = "'.$key.'"';
         $str_string_select = $mySqli->select_str($t_name, $cols_name, $condition);
         $result = $mySqli->query($str_string_select, $conn);
        $rows = mysqli_fetch_array($result);
        if($rows['block_tag']!=3)
        {
            $str_string = $mySqli->update_str($t_name, $cols_name, $cols_var,$condition);

            $mySqli->non_query($str_string, $conn);

            if(count($rows)>0)
            {
                 $success = "تم تفعيل حسابك سيتم عرض الدروس قريبا... تابعونا";
                include_once './layouts/success.php';
            }
            else{
                $error = 'الرابط المدخل غير صحيح';
                include_once './layouts/errors.php';
            }
        }else{
                $error = 'حسابك محظور عذرا لا يمكنك تفعيله!';
                include_once './layouts/errors.php';
            }
        $mySqli->closeConn($conn);
    }
    function login($user,$password)
    {
        include_once 'n_mysqli.php';
        $mySqli = new N_MySqli();
        $conn = $mySqli->db_conn();
        
         $t_name = 'user';
         $cols_name=NULL;
         $condition = ' (`user` = "'.$user.'" or `email` = "'.$user.'") '
                 . 'and password="'.$password.'"' ;
         $str_string_select = $mySqli->select_str($t_name, $cols_name, $condition);
         
         $result = $mySqli->query($str_string_select, $conn);
         
         $arr = mysqli_fetch_array($result);
         if(count($arr['block_tag'])==1 )
         {
             if( $arr['block_tag'] ==1)
             {
                $_SESSION['login_flag'] = FALSE;
                $_SESSION['login_id'] = $arr['id'];
                $_SESSION['user_name'] = $arr['user'];
             }
            else 
            {
                $error ='يجب عليك تفعيل حسابك ارجع لبريدك الالكتروني لتفعيل حسابك';
                    include_once './layouts/errors.php';
                    $email = $arr['email'];
                    $subject = 'كود التفعيل';
                    $message='كود التفعيل: http://wawlearning.wawtechno.com/?key='.$arr['key'];
                    $key = hash("gost",$arr['name'],0);
                    mail($email,$subject,$message);
            }
         }
         else 
         {
             $error ='معلومات حسابك خاطئة يرجى التأكد منها';
                 include_once './layouts/errors.php';
         }
    }
    function forget_password($email)
    {
        include_once 'n_mysqli.php';
        $mySqli = new N_MySqli();
        $conn = $mySqli->db_conn();

         $t_name = 'user';
        $cols_name =NULL;
        $condition = ' email="'.$email.'"';
        $str_string = $mySqli->select_str($t_name, $cols_name, $condition);
        $result = $mySqli->query($str_string, $conn);
        $row = mysqli_fetch_array($result);
        
        $password = $row['password'];
        $subject = 'نسيان كلمة السر';
        $message = 'كلمة السر الخاصة بك: '.$password;
        echo $message;
        mail($email,$subject,$message);
    }
}
if(isset($_POST['user_name'])&&isset($_POST['password'])
        &&isset($_POST['email']))
{
    $user_opration = new N_user_operations();
    $user_opration->new_user($_POST['user_name'], $_POST['password'], $_POST['email']);
    
    unset($_POST['user_name']);
    unset($_POST['password']);
    unset($_POST['email']);
}
else if(isset($_GET['key']))
{
     $user_opration = new N_user_operations();
     $user_opration->confirm_user($_GET['key']);
}
else if(isset ($_POST['login_user_name'])&&isset ($_POST['login_password']))
{
    $user_opration = new N_user_operations();
    $user_opration->login($_POST['login_user_name'], $_POST['login_password']);
   
}
if(isset($_POST['logout']))
{ 
    unset($_SESSION['login_flag']);
    unset($_SESSION['login_id']);
    unset($_SESSION['user_name']);
}
if(isset($_POST['get_password']))
{
    $user_opration = new N_user_operations();
    $user_opration->forget_password($_POST['email']);
}