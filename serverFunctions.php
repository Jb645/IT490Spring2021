#!/usr/bin/php
<?php
require_once('logger.php');

function dbConnect()
{
  $mydb = new mysqli('25.13.229.207','tim2','potato','weatherPong');

  if ($mydb->errno != 0)
  {
        echo "failed to connect to database: ". $mydb->error . PHP_EOL;
        exit(0);
  }

  echo "successfully connected to database".PHP_EOL;
  return $mydb;
}

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    $mydb = dbConnect();
    $sql = "SELECT userName FROM Users WHERE username = '$username' and password = '$password'";
    $result = mysqli_query($mydb, $sql);
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $active = $row['active'];
    $count = mysqli_num_rows($result);
    if($count == 1){
        return true;
    }

    $mydb->close();
    //return false if not valid
}

function createAccount($username, $password)
{
  $mydb = dbConnect();
  //Insert account info into DB
  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
  if ($mydb->query($sql) == TRUE){
    echo "ACCOUNT CREATED";
    $mydb->close();
    return true; //Return true on success 
  }
  logData("Failed to insert");
  $mydb->close();
  return false;
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

function scoreTableChecker($id){
      $mydb = dbconnect();
      $sql = "SELECT * FROM scores WHERE id=$id";
      if($mydb->query($sql) != TRUE){
         $newRowSql = "INSERT INTO scores (id) VALUES ($id)";
         $mydb->query($newRowSql);
      }
      $mydb->close();
}

function insertResults($request, $id)
{
  //SQL insert results into DB
  
  switch($request){
        case "winner":
           $winner = $request['winner'];  //insert winner into users
           $mydb = dbConnect();
           $sqlWinnerSearch = "SELECT wins FROM users WHERE id=$id";
           $winsSearch = mysql_query($mydb, $sqlWinnerSearch);
	   if ($mydb->query($winsSearch) == TRUE){
               $newWins = $winsSearch + 1;
	       $sqlWins = "UPDATE users SET wins=$newWins WHERE id=$id";
	       $mydb->query($sqlWins);
	   }
           else{
                $sqlEmptyWins ="UPDATE users SET wins=1 WHERE id=$id";
                $mydb->query($sqlEmptyWins);
	   }
           $mydb->close();
           break;
        case "loser":
           $loser = $request["loser"];
           $mydb = dbConnect();
           $sqLoserSearch = "SELECT losses FROM users WHERE id=$id";
           $lossesSearch = mysql_query($mydb, $sqlLoserSearch);
           if ($mydb->query($lossesSearch) == TRUE){
               $newLosses = $lossesSearch + 1;
               $sqlLosses = "UPDATE users SET losses=$newLosses WHERE id=$id";
               $mydb->query($sqlLosses);
           }
           else{
                $sqlEmptyLosses = "UPDATE users SET losses=1 WHERE id=$id";
                $mydb->query($sqlEmptyLosses);
           }
           $mydb->close();
           break;
         case 'wscore':
           $winscore = $request['wscore'];  //insert winner into users
	   $mydb = dbConnect();
	   scoreTableChecker($id);
           $sqlWinScoreSearch = "SELECT wins FROM scores WHERE id=$id";
           $winsSearch = mysql_query($mydb, $sqlWinnerSearch);
           if ($mydb->query($winsSearch) == TRUE){
               $newWins = $winsSearch + 1;
               $sqlWins = "INSERT INTO users (wins) VALUES ($newWins) WHERE id=$id";
               $mydb->query($sqlWins);
           }
           else{
                $sqlEmptyWins = "INSERT INTO users (wins) VALUES (1) WHERE id=$id";
                $mydb->query($sqlEmptyWins);
           }
           $mydb->close();
           break;

  $winscore = $request['wscore'];
  $loserscore = $request['lscore'];
  $wweather = $request['wweather'];//Weather cond for winnner
  $lweather = $request['lweather'];//Weather cond for loser

  $mydb = dbConnect();
  //Insert account info into DB
  $sql = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
  return true; //Return true on success
  }
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

