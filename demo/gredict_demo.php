<html>
	<body>
		<table border="1" >
<?php
$characters="abcdefghijklmnopqrstuvwxyz";
$wordCount=0;
for($i=0;$i<2;$i++)
{
	//fetch word list for current character
	$url="http://gredic.com/index_".$characters[$i].".html";
listFetch:
	$fp=fopen($url,"r");
	//if fail then try again after 1 sec
	if(!$fp)
	{
		sleep(1);
		goto listFetch;
	}
	$str="";
	$content="";
	while(!feof($fp))
	{
		$str=fread($fp,1024);
		$content.=$str;
	}
	fclose($fp);
	$pattern = "/<div id=\"main\">.*\/div>/sU";
	$matches=NULL;
	preg_match_all($pattern, $content, $matches);
	
	$pattern = "/<ul>.*\/ul>/sU";
	$matches=NULL;
	preg_match_all($pattern, $content, $matches);
	$content=$matches[0][0];
	
	$pattern = "/<li>.*\/li>/sU";
	$listMatches=NULL;
	preg_match_all($pattern, $content, $listMatches);
	foreach ($listMatches[0] as $listKey => $listValue)
	{
		$pattern = "/\">.*<\//sU";
		$wordMatches=NULL;
		$content=$listValue;
		preg_match_all($pattern, $content, $wordMatches);
		foreach ($wordMatches[0] as $wordKey => $wordValue)
		{
			$arr=explode(">",$wordValue);
			$currentWord=explode("<",$arr[1]);
			$currentWord=$currentWord[0];
			echo "<tr><td>".($wordKey+1)."</td><td>".$currentWord."</td></tr>";
		}
	}
}
?>
		</table>	
	</body>
</html>	