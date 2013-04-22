<?php
include_once("C:\\xampp/htdocs/My_Files/seminar/lib/common.php");
/*
//$path="a/c/d/k/../../f";
$path="http://www.nirmauni.ac.in/it/dept/mathematics/../abt_dept.asp";
$pathComponents=explode("/",$path);

//var_dump($pathComponents);
$url="";
foreach($pathComponents as $key=>$value)
{
	if($value=="..")
	{
		$url=str_replace(strrchr($url, "/"),"",$url);
	}
	else
	{
		$url=$url."/".$value;
	}
}
$url=substr($url,1);	//removing first /
echo "\n".$url;

*/

$value=globaliseURL("../../a/b/c/../../dj.php","http://www.google.com/haha/hihi/hk.asp","http://www.google.com");
//var_dump($value);
echo "\n".$value;
?>