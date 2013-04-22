<?php
/*
$img = imagegrabscreen();
imagepng($img, 'screenshot.png');
echo "<img src=\"".$img.".png\"/>";
*/
/*
$xml = <<< XML
<?xml version="1.0" encoding="utf-8"?>
<books>
 <book>Patterns of Enterprise Application Architecture</book>
 <book>Design Patterns: Elements of Reusable Software Design</book>
 <book>Clean Code</book>
</books>
XML;
*/

$url="http://www.iitm.ac.in";
$fd=fopen($url,"r");
$str="";
$content="";
while($str=fread($fd,1024))
{
	$content.=$str;
}
fclose($fd);

//echo $content;

$doc = new DOMDocument();
libxml_use_internal_errors(true);
$doc->loadHTML($content);
//$doc->loadXML("<html><head></head><body><h1>haha</h1><h1>haha</h1><h1>haha</h1></body></html>");
//$doc->loadXML($xml);
echo "<hr/>";
$books = $doc->getElementsByTagName('a');
echo "<ol>";
foreach ($books as $book) {
    echo "<li>".$book->nodeValue.":".$book->getAttribute('href')."</li>";
}
echo "</ol>";
?>

