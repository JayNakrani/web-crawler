<?php

include_once("C:\\xampp/htdocs/My_Files/seminar/lib/DataAccess.php");
$MAXTRY=3;
$TABLE="data";
class document_class
{
	public $url;	//url of document
	public $content;//all content
	public $urlList;
	public $id;		//self url id
	public $srcId;	//source url id
	public $title;	//title of the document

	
	function __construct()
	{
		$this->$url="";
		$this->$content="";
		$this->$urlList="";
		$this->$id=0;
		$this->$srcId=0;
		$this->$title="";
	}
	
	function __destruct()
	{
  	
	}
	
	function fetch()
	{
		$tryCount=0;
	listFetch:
		$fp=fopen($this->url,"r");
		//if fail then try again after 1 sec
		if(!$fp)
		{
			$tryCount++;
			if($tryCount>$MAXTRY)
			{
				return(false);
			}
			echo "\nFailed in try number:".$tryCount;
			sleep(1);
			goto listFetch;
		}
		$str="";
		$this->content="";
		while(!feof($fp))
		{
			$str=fread($fp,1024);
			$this->content.=$str;
		}
		fclose($fp);
		return(true);
	}
	
	//flowing function extracts all the urls from document
	function analyze()
	{
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);	//to hide all internal errors
		$doc->loadHTML($this->content);
		
		//finding the title
		$titles =$doc->getElementsByTagName('title');
		foreach ($titles as $title) {
			$this->title=$title->nodeValue;
			break;
		}
		
		$anchors = $doc->getElementsByTagName('a');
		$cnt=0;
		foreach ($anchors as $anchor) {
			$this->urlList[$cnt++]=$anchor->getAttribute('href');
		}
		return $cnt;
	}
	
	function insertIntoDB()
	{
		global $TABLE;
	
		$dbObj= new db_class();
		$this->url=mysql_real_escape_string($this->url);
		date_default_timezone_set('Asia/Calcutta');
		$timeStamp=date('Y-m-d H:i:s');
		$query="INSERT INTO `".$TABLE."` (`srNo`, `url`,`title`,`timeStamp`,`srcId`) VALUES ('', '".$this->url."','".$this->title."','".$timeStamp."',".$this->srcId.");";
		$result=$dbObj->insertQuery($query);
		
		//setup current document's id
		$query="SELECT * FROM `".$TABLE."` WHERE url='".$this->url."';";
		$result=$dbObj->getRecord($query);
		$this->id=$result["srNo"];
	}
	
	function insertAsBadLink()
	{
		global $TABLE;
	
		$dbObj= new db_class();
		$url=mysql_real_escape_string($this->url);
		date_default_timezone_set('Asia/Calcutta');
		$timeStamp=date('Y-m-d H:i:s');
		$query="INSERT INTO `bad_links` (`srNo`, `url`, `timeStamp`,`srcId`) VALUES ('', '".$url."','".$timeStamp."','".$this->srcId."');";
		$result=$dbObj->insertQuery($query);

	}
	
	function isVisited()
	{
		$dbObj= new db_class();
		$url=mysql_real_escape_string($this->url);
		$query="SELECT * FROM `data` WHERE `url`='".$url."';";		
		$dbObj->selectCount($query);
		if($dbObj->RowCount>0)
		{
			//if yes then skip
			return(true);
		}
		return(false);
	}
	
	function isBad()
	{
		$dbObj= new db_class();
		$url=mysql_real_escape_string($this->url);
		$query="SELECT * FROM `bad_links` WHERE `url`='".$url."';";		
		$dbObj->selectCount($query);
		if($dbObj->RowCount>0)
		{
			//if yes then skip
			return(true);
		}
		return(false);
	}
}
?>