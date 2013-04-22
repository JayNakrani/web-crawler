<?php

/**
 *This file contains is for testing of queue_class.
 *Author: dj
 *Start Date: 24-03-2013
 */

include_once("../lib/common.php");
include_once("../lib/dataStructs.php");

$queue=new queue_class();

echo "<br/>queue->insert(\"haha\"):".$queue->insert("haha");
echo "<br/>queue->insert(\"haha\"):".$queue->insert("haha");
echo "<br/>queue->insert(\"huhu\"):".$queue->insert("huhu");
echo "<br/>queue->insert(\"huhu\"):".$queue->insert("huhu");
echo "<br/>queue->insert(\"hihi\"):".$queue->insert("hihi");
echo "<br/>queue->insert(\"hihi\"):".$queue->insert("hihi");


echo "<br/>queue->delete():".$queue->delete();
/*
echo "<br/>queue->delete():".$queue->delete();
echo "<br/>queue->delete():".$queue->delete();
*/


?>