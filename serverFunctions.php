#!/usr/bin/php
<?php
require_once('logger.php');

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    return true;
    //return false if not valid
}

function createAccount($usernmane, $password)
{
  //Insert account info into DB
  return true; //Return true on success
}

function getWeather($location)
{
  $output = shell_exec("python3 RAbbitMQ-TnE/WeatherPONG.py ". $location);
  if(!isset ($output))
  {
    logData("No data recieved from weather API");	  
  }
  $output = preg_split('/\r\n|\n/', $output);
  $weather = array((float) filter_var( $output[0], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION), substr($output[3],15));
  LogData("Temp: " . $weather[0] ." | Desc: ". $weather[1] ."\n");
  return $weather;
}

function insertResults($request)
{
  //SQL insert results into DB
  return true; //Return true on success
}

function getFriends($username)
{
  //SQL retrieve list of friends from DB
  return true; //Return true on success
}

function addFriend($username, $target)
{
  //SQL insert target into username's friends list
  return true; //Return true on success
}

function rmFriend($username, $target)
{
  //SQL remove target from username's friend's list
  return true; //Return true on success
}
?>

