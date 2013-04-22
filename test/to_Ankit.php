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

//display the html code
echo $content."\n********************************************************************************\n";


//extract out out going links
$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($content);
$books = $doc->getElementsByTagName('a');
foreach ($books as $book) {
    echo "\n\t".$book->nodeValue.":".$book->getAttribute('href');
}

/*
how to run this code?
	Let's say the file name is "to_Ankit.php",
	then in terminal in linux with php(>=5.0) type in follwing command
		php <space> <filepath>
			where,
				php: runs th target php file
				<filepath>: path to target file, in this case "to_Ankit.php"
	Now if computer is connected to  internet then it'll fetch hoime page of iit-madras and will give you the anchors
*/


?>
