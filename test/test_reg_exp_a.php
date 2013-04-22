<?php
//$fp=fopen("http://en.wikipedia.org/wiki/Computer_science","r");
//$fp=fopen("http://www.google.com","r");

$fp=fopen("http://www.iitm.ac.in/","r");
$str="";
$content="";
while(!feof($fp))
{
	$str=fread($fp,1024);
	$content.=$str;
}
fclose($fp);
//echo htmlentities($content)."<hr/>";


//regular expression

$pattern = "/<a.*>/sU";
$matches=NULL;
preg_match_all($pattern, $content, $matches);
//var_dump($matches[0]);
foreach($matches[0] as $key=>$value)
{
	echo htmlentities($value)."<br/>";
}

?>