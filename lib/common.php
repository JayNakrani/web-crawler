<?php

/*Global constants go here*/



/**
 * Generic function for converting local url to global url
 */
function globaliseURL($url,$base,$domain)
{

	//if url starts with # then skip it
	if($url[0]=='#' || strncmp($url,"javascript:",11)==0)
	{
		return("");
	}

	//check for php,asp,jsp,aspx
	$extension3=substr($url,strlen($url)-4);
	$extension4=substr($url,strlen($url)-5);
	if(!($extension3==".asp" || $extension3==".php" || $extension3==".jsp" ||$extension4==".html" || $extension4==".aspx"))
	{
		//document of some other format 
		return("");
	}
	
	
	
	if(strncmp($url,"http",4)==0)
	{
		//already global
		return $url;
	}
	$lastChar=substr($base, -1);
	$firstChar=$url[0];
	if($firstChar!='\\' && $firstChar!='/')
	{
		$base=str_replace(strrchr($base, "/"),"",$base);	//stripping off the document name from base
		
		$path=$base."/".$url;
		$pathComponents=explode("/",$path);
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
	}
	else if($lastChar=='\\' || $lastChar=='/')
	{
		$url=$base.$url;
	}
	else
	{
		$url=$base."\\".$url;
	}
	return($url);
}

function isValidDomain($url, $domain)
{
	if(strncmp($url,$domain,strlen($domain))==0)
	{
		//url in current domain
		return true;
	}
	return false;
}






?>