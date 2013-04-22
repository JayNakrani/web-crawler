<?php
/**
 *This file contains is for testing of stack_class.
 *Author: dj
 *Start Date: 24-03-2013
 */
include_once("../lib/common.php");
include_once("../lib/dataStructs.php");

$stack=new stack_class();

echo "<br/>stack->push(\"haha\"):".$stack->push("haha");
echo "<br/>stack->push(\"hihi\"):".$stack->push("hihi");
echo "<br/>stack->push(\"huhu\"):".$stack->push("huhu");

echo "<br/>stack->pop():".$stack->pop();
echo "<br/>stack->pop():".$stack->pop();
echo "<br/>stack->pop():".$stack->pop();

?>