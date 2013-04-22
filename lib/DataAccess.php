<?php
//Include DB-Configuration Files Here...
include("config/Db.php");
class db_class
{
	public $LastError;				//Error occured last
    
    public $HostName;				//name of the host
    public $DateBaseUserName;		//username for database server
    public $DateBasePassword;		//password for database server
    public $DataBase;				//name of the database
    public $DataBaseLink;			//link to the database server
	public $FieldNames;				//names of the fields
	public $Records;				//values of records
	public $RowCount;				//number of rows
	public $auto_slashes;			
   
  function __destruct()
  {
  	@mysql_close($this->DataBaseLink);
	
  }  
  function __construct() 
  {
  		global $DataBaseHost,$DataBaseName,$UserName,$Password;			//global variables having similar meanings
		
    	$this->HostName=$DataBaseHost;					//assigning to the object's data-member
        $this->DateBaseUserName=$UserName;
        $this->DateBasePassword=$Password;
        $this->DataBase=$DataBaseName;
        $this->auto_slashes=true;
	
		$this->DataBaseLink=mysql_connect($this->HostName,$this->DateBaseUserName,$this->DateBasePassword,1);
		if(!$this->DataBaseLink)
        {
        	die('Could not connect: ' . mysql_error());		
		
        }
        
      
		if(!mysql_select_db($this->DataBase))
		{
			die('DataBase Not Found: ' . mysql_error());	
			return false;
		}	
              
  }   
  function selectCount($Query)			
   {
   		$this->Query=$Query;
		$Result=mysql_query($Query,$this->DataBaseLink);
		
		if(!$Result)
		{
			return false;
		}
		$Count=mysql_fetch_row($Result);
		$this->RowCount=$Count;
		return TRUE;
   }
   function select($Query)
   {
   		$this->Query=$Query;
		$Result=mysql_query($Query,$this->DataBaseLink);
		
		if(!$Result)
		{
			return false;
		}
		$FieldNo=mysql_num_fields($Result);
		
		$this->FieldNames=NULL;		
		for($i=0;$i<$FieldNo;$i++)
		{
			$Field=mysql_fetch_field($Result,$i);
			$this->FieldNames[]=$Field->name;
		}
		
		$this->Records=NULL;
		while($Record=mysql_fetch_assoc($Result))
		{
		  $this->Records[]=$Record;
		}
		
		$this->RowCount=mysql_num_rows($Result);
		return $Result;
   }
   
   function getRecord($Query)
   {
   	$RecordData=mysql_query($Query,$this->DataBaseLink);
	if(mysql_num_rows($RecordData)!=0)
	{
		$this->RowCount=mysql_num_rows($RecordData);
		$ResultData=mysql_fetch_assoc($RecordData);
	}
   	return $ResultData;
   }
       
   function getRow($Result,$Type='MYSQL_ASSOC')
   {
   		if(!$Result)
		{
			echo "Invalid resource identifier passed to get_row() function.";
			return false;
		}
		
		$Row=mysql_fetch_array($Result,$Type);
		if(!$Row)
			return false;
		
		return $row;	
   }
      
   function insertQuery($Query)
   {
   		$Result=mysql_query($Query);
		if(!$Result)
		{
		mysql_error();
		return false;
		}
		$Id=mysql_insert_id();
		if($Id==0)
			return true;
		else
			return $Id;
   }
   
   function updateQuery($Query)
   {
   		$Result=mysql_query($Query,$this->DataBaseLink);
		if(!$Result)
		{
			mysql_error();
			return false;
		}
		return mysql_affected_rows($this->DataBaseLink);
   }
   
   function deleteQuery($Query)
   {
   		$Result=mysql_query($Query,$this->DataBaseLink);
		if(!$Result)
		{
			mysql_error();
			return false;
		}
		return true;
  }
  function selectFields($Query)
  {
  		$this->Query=$Query;
		$Result=mysql_query($Query,$this->DataBaseLink);
		
		if(!$Result)
		{
			//die('<td>No Record Found: </td>' . mysql_error());
			return false;
		}
		$FieldNo=mysql_num_fields($Result);
		
		$this->FieldNames=NULL;		
		for($i=0;$i<$FieldNo;$i++)
		{
			$Field=mysql_fetch_field($Result,$i);
			$this->FieldNames[]=$Field->name;
		}
  }  
    
}
?>