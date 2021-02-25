#!/usr/bin/php
<?php

function customError($errno, $errstr)
{
  logData("<b>ERROR:</b> [$errno] $errstr<br>");
  echo "<b>ERROR:</b> [$errno] $errstr<br>";
}

function logRequest($request) //Takes request and writes it to file
{
  $logs = fopen("logs.txt", "a") or die("Unable to open file."); //open logs

  ob_start(); //Output buffering
  var_dump($request); //2nd var dump, captured by output buffering for logging
  $info = ob_get_clean(); //store captured data

  fwrite($logs, date("\nh:i:sa")); //write time of request
  fwrite($logs, "\n___________\n"); //formatting

  fwrite($logs, $info); //take caputured var dump and write to file 

  fclose($logs);//close
}

function logData($string)
{
  $logs = fopen("logs.txt", "a") or die("Unable to open file."); //open logs
  fwrite($logs, $string);
  fclose($logs);
}

set_error_handler("customError",E_USER_WARNING);

?>
