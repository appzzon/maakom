<?php


class Login
{
        private $name_db=""; // user name in database
        private $pass_db=""; //password in database
        private $pass_inc="";//incoded password using md5 algorithem
        private $result="";  //result data from database
        private $count="";	//count of rows 
        private $row="";	  //first row in result
        private $department="";//department if it exest in database structure

function check_login($user_n,$user_p,$t_name=NULL,$user_col=NULL,$password_col=NULL,$user_dept=NULL)
        {
            include_once __DIR__.'/n_mysqli.php';
            $name_db=$user_n; 
            $pass_db= $user_p;
            $pass_inc=$pass_db;
            if($t_name == NULL)
                $t_name = 'users';
            if($user_col == NULL)
                $user_col = 'email';
            if($password_col == NULL)
                $password_col = 'password';
            // cheack are username and password are true 
            if( $user_dept != NULL)
            {
                $sql = new N_MySqli();
                $conn = $sql->db_conn();
                $cols_name=NULL;
                $condition=$user_col."='$name_db' and ".$password_col."='$pass_inc'" ;
                $str=$sql->select_str($t_name,$condition, $cols_name);

                $result=$sql->query($str,$conn);								 
                $count=  mysqli_num_rows($result);					 
                if($count==1)
                {
                        $row = mysqli_fetch_array($result);
                        $department=$row['type'];
                        $id=$row['id'];

                        $_SESSION['user_dept'] = $department;
                        $_SESSION['valid_u'] = True;
                        $_SESSION['id'] = $id;
                        $_SESSION['user_name'] = $row['name'];
                        $sql->closeConn($conn);
                        return true;
                }
                else
                {
                    $sql->closeConn($conn);
                    return false;
                }
            }
            else
            {
                $sql = new N_MySqli();
                $conn = $sql->db_conn();
                $cols_name=NULL;
                $condition=$user_col."='$name_db' and ".$password_col."='$pass_inc'" ;
                $str=$sql->select_str($t_name,$condition, $cols_name);
                $result=$sql->query($str,$conn);		 
                $count=mysqli_num_rows($result);					 
                if($count==1)
                {
                    $row = mysqli_fetch_array($result);
                    
                    if($t_name == 'users')
                    {
                        $_SESSION['user_id'] = $row['id'];
                        $_SESSION['valid_u'] = TRUE; 
                    }
                    else
                    {
                        $_SESSION['valid_admin'] = TRUE;
                        $_SESSION['admin_id'] = $row['id'];
                    }
                    $_SESSION['selected_form'] = '0';
                    $_SESSION['user_name'] = $row['person_name'];
                    $sql->closeConn($conn);
                    return 1;
                }
                else
                {
                    $sql->closeConn($conn);
                    return 0;
                }
            }	
        }
}
if(isset($_POST['login_user']))
{
    $login = new Login();
    echo $login->check_login($_POST['login_user'], $_POST['login_password']);
}