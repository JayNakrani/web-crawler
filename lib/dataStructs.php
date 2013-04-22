<?php
/**
 *This file contains various data-sturctures for bot. All the data structures uses database for permenant data storage. These classes provide just accessing mechanism.
 *Author: dj
 *Start Date: 24-03-2013
 *Convetntions:
 *		1. class names are preceded by "_class" postfix
 */
include_once("C:\\xampp/htdocs/My_Files/seminar/lib/DataAccess.php");
 
 
 
//stack
class stack_class
{
	public $top;
	public $url;
	public $srcId;
	public $dbObj;
	
	function __construct()
	{
		$this->url="";
		$this->dbObj= new db_class();
		$query="SELECT * from `stack_top` WHERE 1;";
		$this->dbObj->select($query);
		if($this->dbObj->Records[0]["tos"])
		{
			$this->top=$this->dbObj->Records[0]["tos"];		
		}
		else
		{
			//no records in database
			$query="TRUNCATE TABLE `stack_top`;";
			$this->dbObj->deleteQuery($query);
			$this->top=0;
			$query="INSERT INTO `stack_top` (`tos`) VALUES (".$this->top.");";
			$result=$this->dbObj->insertQuery($query);
		}
	}
	
	function __destruct()
	{
		//update stack_top table
		$query="TRUNCATE TABLE `stack_top`;";
		$this->dbObj->deleteQuery($query);
		$query="INSERT INTO `stack_top` (`tos`) VALUES (".$this->top.");";
		$result=$this->dbObj->insertQuery($query);
	}

	function push($url,$srcId)
	{
		if($url)
		{
			$url=mysql_real_escape_string($url);
			$dbObj=new db_class();
			
			//check if redundant url
			$query="SELECT * FROM `stack` WHERE `url`='".$url."';";		
			$dbObj->selectCount($query);
			if($dbObj->RowCount>0)	// if yes then return without pushing
			{
				return(false);
			}
			
			$query="INSERT INTO `stack` (`srNo`, `url`,`srcId`) VALUES ('".$this->top."', '".$url."','".$srcId."');";
			$result=$dbObj->insertQuery($query);
			//success
			$this->top++;
			return ($this->top-1);
		}
		return false;
	}
	
	/*
	function pushList($urlList,$srcId)
	{
	
		foreach(urlList as $key=>$value)
		{
			//enter newly found links onto the stack
			$value=globaliseURL($value,$document->url);
			echo "\n".$value;
			if($value!="")
			{
				$this->push($value,$document->id);
			}
		}
	}
	*/
	
	function pop()
	{
		if($this->top<=0)
		{
				return false;
		}
		$this->top--;
		$query="SELECT * FROM `stack` WHERE `srNo`=".$this->top.";";
		$result=$this->dbObj->select($query);
		$this->url=$this->dbObj->Records[0]['url'];
		$this->srcId=$this->dbObj->Records[0]['srcId'];
		//deleting the record from db
		$query="DELETE FROM `stack` WHERE `stack`.`srNo` =".$this->top.";";
		$result=$this->dbObj->deleteQuery($query);
		return($this->url);
	}
}


//queue
class queue_class
{
	public $url;
	public $srcId;
	public $dbObj;
	function __construct()
	{
		$this->dbObj= new db_class();
		$this->url="";
		$this->srcId=-1;
	}
	
	function __destruct()
	{
	
	}

	function insert($url)
	{
		if($url)
		{
			$url=mysql_real_escape_string($url);
			$query="INSERT INTO `queue` (`url`) VALUES ('".$url."');";
			$result=$this->dbObj->insertQuery($query);
			return (true);
		}
		return(false);
	}

	function delete()
	{
		$query="SELECT * FROM `queue` LIMIT 1;";
		$result=$this->dbObj->select($query);
		$this->url=$this->dbObj->Records[0]['url'];
		
		//deleting the record from db
		$query="DELETE FROM `queue` LIMIT 1;";
		$result=$this->dbObj->deleteQuery($query);
		return($this->url);
	}
}
?>