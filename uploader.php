<?php
class N_uploader
{
    function upload_files($files,$file_path=NULL)
    {
        if($file_path == NULL)
        {
            $file_path =dirname($_SERVER["SCRIPT_FILENAME"]).'/';
        }
        else 
        {
            $file_path =__DIR__.'/../'.$file_path;
        }
        
        $files_db_data = array();
        $i=0;
        foreach ($files as $key=> $value)
        {
            $f_name = basename($files[$key]['name']);
            
            $file_name=$key.'_'.uniqid().substr($f_name, strrpos($f_name, ".")); //rename image by adding date ( because if there is two images hase been uploaded with same name )
            $uploadfile = $file_path .$file_name;//add the direct path to the image
            
            if (is_dir($file_path)
                    && is_writable($file_path)
                    &&$this->upload($files[$key]['tmp_name'],$uploadfile)) 
            {
               $files_db_data[$key]= $file_name;
            }
            else 
            {
                $files_db_data[$key]= FALSE;
            }
        }
        return $files_db_data;
    }
    function upload($tmp_name,$uploadfile)
    {
        if (move_uploaded_file($tmp_name, $uploadfile))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
}
