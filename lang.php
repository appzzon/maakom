<?php 
// for chech languge using session

// set language using post
if(isset($_POST['lang']))
{
        if(!isset($_SESSION)) 
        { 
                session_start(); 
        }
        $_SESSION['lang']= $_POST['lang'];
}

class Lang
{
	public function setLang($language)
	{
            $_SESSION['lang']=$language;
	}
	public function getlang($language)
	{
		if(!isset($_SESSION['lang']))
		{
			$lang = new Lang();
			$lang->setLang($language);
		}
		return ($_SESSION['lang']);
	}
}