<?php

include_once("C:\\xampp/htdocs/My_Files/seminar/lib/DataAccess.php");
$MAXTRY=3;
$TABLE="data";

class meta_class
{
	public $name;
	public $content;
	function __construct($name=NULL,$content=NULL)
	{
		$this->name=$name;
		$this->content=$content;
	}
	
	function __destruct()
	{
  	
	}
}
class script_class
{
	public $type;			// script type
	public $src;			// src element
	public $innerText;		// anything in between 'script' tags
	function __construct($type=NULL,$src=NULL,$innerText=NULL)
	{
		$this->type=$type;
		$this->src=$src;
		$this->innerText=$innerText;		
	}
	function __destruct()
	{
		
	}
}
class style_class			//style tag
{
	public $type;			// stylesheet type
	public $href;			// href element
	public $innerText;		// anything in between 'style' tags
	function __construct($type=NULL,$href=NULL,$innerText=NULL)
	{
		$this->type=$type;
		$this->href=$href;
		$this->innerText=$innerText;		
	}
	function __destruct()
	{
		
	}
}
class anchor_class
{
	public $href;			// href string
	public $target;			// target
	public $innerText;		// anything in between 'anchor' tags
	function __construct($href=NULL,$target=NULL,$innerText=NULL)
	{
		$this->target=$target;
		$this->href=$href;
		$this->innerText=$innerText;		
	}
	function __destruct()
	{
		
	}
}
class form_class
{
	public $name;			// name of the form
	public $method;			// get || post
	public $type;			// !html element. jQuery-AJAX based(async.) handling || normal sync.
	public $actionURL;		// full url of action
	public $innerText;		// anything in between form tags
	function __construct($name=NULL,$method=NULL,$type=NULL,$actionURL=NULL,$innerText=NULL)
	{
		$this->name=$name;
		$this->method=$method;
		$this->type=$type;
		$this->actionURL=$actionURL;
		$this->innerText=$innerText;		
	}
	function __destruct()
	{
		
	}
}
class image_class
{
	public $src;			// image url full
	public $style;			// style ATTRIBUTE not stylesheet
	public $title;			// title of the image
	public $alt;			// alternate text for Image
	function __construct($src=NULL,$style=NULL,$title=NULL,$alt=NULL)
	{
		$this->src=$src;
		$this->style=$style;
		$this->title=$title;
		$this->alt=$alt;
	}
	function __destruct()
	{
		
	}
}
class iframe_class
{
	public $src;
	public $document;
	
	function __construct()
	{
		$this->document=new document_class();
		$this->src=new src_class();
	}
	function __destruct()
	{
  	
	}
}
class head_class
{
	public $title;		//string for title
	public $meta;		//Array of objects of the class meta
	public $script;		//Array of objects of the class script
	public $style;		//Array of objects of the class script
	function __construct()
	{
		$this->title=NULL;
		$this->meta=new meta_class();
		$this->script=new script_class();
		$this->style=new style_class();
	}
	
	function __destruct()
	{
  	
	}
	
}
class body_class
{
	public $script;	//Array of objects of the class script
	public $anchor;	//Array of objects of the class anchor
	public $form;	//Array of objects of the class form
	public $image;	//Array of objects of the class image
	public $iframe;	//Array of iframe class objects
	
	function __construct()
	{
		$this->script=new script_class();
		$this->anchor=new anchor_class();
		$this->form=new form_class();
		$this->image=new image_class();
		$this->iframe=new iframe_class();
	}
	
	function __destruct()
	{
  	
	}
}
class document_class
{
	public $url;	//url of document
	public $content;//all content
	public $urlList;
	public $id;		//self url id
	public $srcId;	//source url id
	public $title;	//title of the document
	/*
	public $head;	//object of the class head
	public $body;	//object of the class body
	*/
	
	function __construct()
	{
		//$this->head=new head_class();
		//$this->body=new body_class();
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