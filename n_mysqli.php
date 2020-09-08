<?php
class N_MySqli
{
    function db_conn($host_var=NULL, $user_var=NULL, $password_var=NULL, $database_var=NULL)
    {
        include __DIR__.'/../config/sql.php';
        
        if($host_var==null)
            $host_var = $host;
        if($user_var==null)
            $user_var = $user;
        if($password_var==null)
            $password_var = $password;
        if($database_var==null)
            $database_var = $database;
        
        $conn = new  mysqli($host_var , $user_var , $password_var , $database_var ) 
                or dia ('Could not connect: ' . mysqli_error());  
        $conn->set_charset('utf8');
        
        return $conn;
    }
    function non_query($sql_str,$conn)
    {
        try
        {
            mysqli_set_charset($conn, "utf8");
            mysqli_query($conn,$sql_str);
            return $conn->insert_id;
        }
        catch(Exception $e)
        {
            return FALSE;
        }
    }
    function query($sql_str,$conn)
    {
        try
        {
            $result = mysqli_query($conn,$sql_str);
            return($result);
        }
        catch(Exception $e)
        {
            return NULL;
        }
    }
    function closeConn($conn)
    {
        if (isset($conn))
        mysqli_close($conn);
    }
    function select_str($t_name, $condition=NULL,$cols_name=NULL,$end_statment=NULL)
    {
        $sql_statment = 'select ';
        if (!is_null($cols_name))
        {
            $cols_str = '';
            for($i=0;$i<count($cols_name)-1;$i++)
            {
                $cols_str .=" `".$cols_name[$i]."`, ";
            }
            $sql_statment .=$cols_str." `".$cols_name[$i]."`";
        }
        else
        {
            $sql_statment = $sql_statment." *";
        }
        $sql_statment .= ' from `' . $t_name."`";
        if ( !is_null($condition))
        {
            $sql_statment .= ' where '.$condition;
        }
        if(!is_null($end_statment))
        {
            $sql_statment .=' '.$end_statment;
        }
        return $sql_statment;
    }
    
    function insert_str($t_name,$cols_name,$cols_var)
    {
        $sql_statment = "insert into `".$t_name."` (";
        for($i=0;$i<count($cols_name)-1;$i++)
        {
            $sql_statment.="`".$cols_name[$i]."` , ";
        }
        $sql_statment.="`".$cols_name[$i]."`) values (";
        for($i=0;$i<count($cols_var)-1;$i++)
        {
            if(is_array($cols_var[$i]))
            {
                if($i>0)
                 $sql_statment.="(";
                for($j=0;$j<count($cols_var[$i])-1;$j++)
                {
                    $sql_statment.="'".$cols_var[$i][$j]."' , ";
                }
                $sql_statment.="'".$cols_var[$i][$j]."') ,";
            }
            else {
                $sql_statment.="'".$cols_var[$i]."' , ";
            }
        }
        if(is_array($cols_var[$i]))
            {
            $sql_statment.="(";
                for($j=0;$j<count($cols_var[$i])-1;$j++)
                {
                    $sql_statment.="'".$cols_var[$i][$j]."' , ";
                }
                if(count($cols_var[$i])==1)
                {
                    $j=0;
                }
                $sql_statment.="'".$cols_var[$i][$j]."')";
            }
            else {
                $sql_statment.="'".$cols_var[$i]."') ";
            }
        return $sql_statment;
    }
    function update_str($t_name,$cols_name,$cols_var,$condition=NULL)
    {
        $sql_statment = 'update '.$t_name.' set ';
        for($i=0;$i<count($cols_name)-1;$i++)
        {
            $sql_statment.='`'.$cols_name[$i]."` ='".$cols_var[$i]."',";
        }
        $sql_statment.='`'.$cols_name[$i]."` ='".$cols_var[$i]."'";
        
        if($condition != NULL)
        {
            $sql_statment.=' where '.$condition;
        }
        return $sql_statment;
    }
    function delete_str($t_name,$condition=NULL)
    {
        $str_statment = "delete from `".$t_name."` ";
        
        if($condition!=NULL)
        {
            $str_statment .= " where ". $condition;
        }
        return $str_statment;
    }
    function procedure_str($proceduer_name,$values=NULL)
    {
        $str_statment = 'CALL `'.$proceduer_name.'` (';
        if(!is_array($values) && $values!=NULL)
        {
            $values=str_replace('"', '\"', $values);
            $str_statment.='"'.$values.'"';
        }
        
        else if(isset ($values))
        {
            for($i=0;$i+1<count($values);$i++)
            {
                $values[$i]=str_replace('"', '\"', $values[$i]);
                $str_statment.='"'.$values[$i].'",';
            }
            $values[$i]=str_replace('"', '\"', $values[$i]);
            $str_statment.='"'.$values[$i].'"';
        }
        $str_statment.=')';
        return $str_statment;
    }
    
}