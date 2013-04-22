<?php

include_once("C:\\xampp/htdocs/My_Files/seminar/lib/DataAccess.php");

/**
 * Show analyzed results.
 */

 
$dbObj=new db_class(); 
//show all web-documents fetched, their incoming links and out-going links
$query="SELECT * FROM  `data` WHERE 1;";
$result=$dbObj->select($query);
if(!$result)
{
	die('<br>Could not fetch records from db...!!<br/>');
}
$records=$dbObj->Records;
echo "<table border=\"1\"><th>Sr No</th><th>Title</th><th>URL</th><th>Time Stamp</th><th>Outgoing links [new] </th></thead>";
foreach($records as $key => $value)
{
	echo "<tr><td>".$value['srNo']."</td><td>".$value['title']."</td><td>".$value['url']."</td><td>".$value['timeStamp']."</td><td><ol>";
	$query="SELECT * FROM  `data` WHERE `srcId`='".$value['srNo']."';";
	$result=$dbObj->select($query);
	if($dbObj->Records)
	{
		foreach($dbObj->Records as $record)
		{
			echo "<li>".$record['url']."</li>";
		}
	}
	else
	{
		echo "None";
	}
	echo "</ol></td></tr>";
}
echo "</table>";












?>