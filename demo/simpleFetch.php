<?php
	$url="http://www.nirmauni.ac.in/it/";
	$fp=fopen($url,"r");
	if(!$fp)
	{
		echo "\nERROR:Could Not fetch the specified web document.";
	}
	else
	{
		
		$str="";
		$content="";
		while(!feof($fp))
		{
			$str=fread($fp,1024);
			$content.=$str;
		}
		echo "\n***************************Dumping The Content*****************************\n";
		echo $content;
		echo "\n***************************************************************************\n";
	}
	fclose($fp);
?>