<?php
//$fp=fopen("http://en.wikipedia.org/wiki/Computer_science","r");
//$fp=fopen("http://www.google.com","r");

$fp=fopen("C:\\xampp\htdocs\My_Files\seminar\demo\aboutUs.php","r");

$str="";
$content="";
while(!feof($fp))
{
	$str=fread($fp,1024);
	$content.=$str;
}
fclose($fp);

//display the html code
echo $content."\n********************************************************************************\n";


//extract out out going links
$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($content);
$paras = $doc->getElementsByTagName('a');
foreach ($paras as $para) {
    echo "\n\t".$para->nodeValue.":".$para->getAttribute('href');
}
?>
