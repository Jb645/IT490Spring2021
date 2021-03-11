#!/usr/bin/php
<?php
require_once('logger.php');

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    $sql = "SELECT userName FROM Users WHERE username = '$username' and password = '$password'";
    $result = mysqli_query($db, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $active = $row['active'];
    $count = mysqli_num_rows($result);
    if($count == 1){
        return true;
    }

    //return false if not valid
}

function createAccount($username, $password)
{
  //Insert account info into DB
  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
  if ($mydb->query($sql) == TRUE){
    echo "ACCOUNT CREATED" . EOL;
    return true; //Return true on success
    
  }
}

function getWeather($location)
{
  if(!isset($location))
  {
     logData("ERROR: Input is null");
     return NULL;
  }
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

function getWeatherHistory($location, $date)
{
  if(!isset($location))
  {
     logData("ERROR: location is null");
     return NULL;
  }
  if(!isset($date))
  {
     logData("ERROR: date is null");
  }
  $output = shell_exec("python3 RAbbitMQ-TnE/History.py ". $location ." ". $date);
  if(!isset ($output))
  {
    logData("No data recieved from weather API");
  }
  $output = preg_split('/\r\n|\n/', $output);
  
  $weather = array(substr($output[3],18), substr($output[4],13));
  return $weather;
}

function insertResults($request)
{
  //SQL insert results into DB
  $winner = $request['winner'];
  $loser = $request['loser'];
  $winscore = $request['wscore'];
  $loserscore = $request['lscore'];
  $wweather = $request['wweather'];//Weather cond for winnner
  $lweather = $request['lweather'];//Weather cond for loser

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

