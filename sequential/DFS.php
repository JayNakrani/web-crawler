<?php

/*	Initialization Phase	*/

include_once("C:\\xampp/htdocs/My_Files/seminar/lib/common.php");
include_once("C:\\xampp/htdocs/My_Files/seminar/lib/dataStructs.php");
include_once("C:\\xampp/htdocs/My_Files/seminar/lib/document.php");

//$domain="http://www.iitm.ac.in";
$domain="http://www.nirmauni.ac.in";


//read all seeds and save them in stack
$dbObj=new db_class();
$stack=new stack_class();
$document=new document_class();


$query="SELECT * FROM `seeds` WHERE 1;";
$dbObj->select($query);
$records=$dbObj->Records;
if(!$records)
{
	echo "\nERROR:No Seeds..!!\n";
}
foreach($records as $key=>$value)
{
	//save them in stack
	$stack->push($value['url'],-1);
}


/*	Crawling Phase	*/

$url=$stack->pop();		//take a seed from stack and start analyzing it
$count=0;
while($url!=false)
{	
	if(isValidDomain($url, $domain))
	{
		$document->url=$url;
		$document->srcId=$stack->srcId;
		//check if a bad link or already visited 
		if($document->isBad() || $document->isVisited())
		{
			goto skip;
		}
		$result=$document->fetch();
		
		if($result==false)
		{
			//enter the link into bad links table
			$document->insertAsBadLink();	
			goto skip;
		}
		echo "\n***************[".$document->url."]***************\n";
		$result=$document->analyze();
		$document->insertIntoDB();
		if($result>0)		//some links were found
		{
			foreach($document->urlList as $key=>$value)
			{
				//enter newly found links onto the stack
				echo "\nval:".$value."\tbase:".$document->url;
				$value=globaliseURL($value,$document->url,$domain);
				if($value!="")
				{
					if($stack->push($value,$document->id))
					{
						//the url has been pushed
						echo "\n\t\t".$key.":".$value;
					}
				}
			}
		}
	}
skip:
	//take next seed
	$url=$stack->pop();
	usleep(5000);
	/*
	$count++;
	if($count>=5)
	{
		break;
	}
	*/
}
?>