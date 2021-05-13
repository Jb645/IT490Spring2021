#!/usr/bin/php
<?php
require_once('logger.php');

function dbConnect()
{
  $mydb = new mysqli('25.13.229.207','tim2','potato','weatherPong');

  if (!($mydb->errno != 0))
  {
        echo "successfully connected to database" . PHP_EOL;
        return $mydb;
  } 
  else
  {
         echo "Failed to connect to database: " .$mydb->error .  PHP_EOL;
         exit(0);
  }

}

function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    $mydb = dbConnect();
    $sql = "SELECT username FROM users WHERE username = '$username' and password = '$password'";
    $output = $mydb->query($sql);
    $mydb->close();
    logRequest($output);
    if($output == TRUE){
        return true;
    }
    else
	    return false;
    //return false if not valid
}

function createAccount($username, $password)
{
  $mydb = dbConnect();
  //Insert account info into DB
  $password2 = "6@9a" . $password;
  $sql = "INSERT INTO users (username, password, wins, losses) VALUES ('$username', '$password2', 0, 0)";
  if ($mydb->query($sql) == TRUE)
	  echo "ACCOUNT CREATED";

    $sql2 = "SELECT id FROM users WHERE username = '$username'";
    $newScores = $mydb->query($sql2);
   while($row = mysqli_fetch_row($newScores))
  {
          $newID = $row[0];
          logData("\n user id: ".$row[0]);
  }
    $sql3 = "INSERT INTO scores (id, lastWinningScore, lastLosingScore, lastWinningCondition, lastLosingCondition) VALUES ('$newID', 0, 0, 'clear sky', 'clear sky')";
   if($mydb->query($sql3) == TRUE)
     logData("Added $newID to scores");
  
  $sql4 = "INSERT INTO hats (id) VALUES ('$newID')";
  if($mydb->query($sql4) == TRUE){
    $mydb->close();
    logData("Added $newID to hats");
    return true;
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
      $mydb = dbConnect();
      $sql = "SELECT * FROM scores WHERE id=$id";
      if($mydb->query($sql) != TRUE){
         $newRowSql = "INSERT INTO scores (id) VALUES ($id)";
         $mydb->query($newRowSql);
      }
      $mydb->close();
}

//Insert results into DB
function insertResults($request)
{
  $winner = $request['winner'];
  $loser = $request['loser'];
  $wscore = $request['wscore'];
  $lscore = $request['lscore'];
  
  $mydb = dbConnect();
  $sql = "UPDATE users SET wins = wins + 1 WHERE username = '$winner'";
  $myQuery = $mydb->query($sql);
  if($myQuery != true)
  {logData("Failed to update winner"); $mydb->close(); return false;}

  $sql = "UPDATE users SET losses = losses + 1 WHERE username = '$loser'";
  $myQuery = $mydb->query($sql);
  if($myQuery != true)
  {logData("Failed to update loser"); $mydb->close(); return false;}

  $mydb->close();  
}
  //Insert account info into Database
function getFriends($username)
{
  //SQL retrieve list of friends from DB
  $mydb = dbConnect();	
  $friendsList = array();
  $sql="SELECT friendList FROM users WHERE username='$username'";
  $friendsRaw = $mydb->query($sql);
  while($row = mysqli_fetch_row($friendsRaw))
      {
	 logData($row[0]);
          $friendsArray = explode(" ", $row[0]);
	 logRequest($friendsArray);
	 //logData("friends array:" . $friendsArray[0] . $friendsArray[1]);
       }
   foreach ($friendsArray as &$id)
   {
	   $sql = "SELECT username FROM users WHERE id ='$id'";
	   $friendsRaw = $mydb->query($sql);
	   while($row = mysqli_fetch_row($friendsRaw))
	   {
	      array_push($friendsList, $row[0]);
	      logData($row[0]."\n");
	   }
   }
  
   $mydb->close();
  logRequest($friendsList);
  return $friendsList; 
}

function getWins($username)
{
	//returns the amounts of wins a user has
	$mydb = dbConnect();
	$targetSQL = "SELECT wins FROM users WHERE username='$username'";
	logData($targetSQL);
	$wins = $mydb->query($targetSQL);
	return $wins;
}


function addFriend($username, $target)
{
  //SQL insert target into username's friend list
  $mydb = dbConnect();
  $targetSQL = "SELECT id FROM users WHERE username='$target'";
  logData($targetSQL);
  //$newFriendId = $mydb->query($targetSQL);
  $newFriendId = $mydb->query($targetSQL);
  while($row = mysqli_fetch_row($newFriendId))
  {
	  $newFriendId2 = $row[0];
	  logData("\n user id: ".$row[0]);
  }
  $friendCheckSQL = "SELECT friendList FROM users WHERE username='$username'";
  $friendCheckResult = $mydb->query($friendCheckSQL);
  if($friendCheckResult != NULL){
        while($row = mysqli_fetch_row($friendCheckResult))
       {
          $newList = $row[0] . " " . $newFriendId2 ;
          logData("\n user id: ".$row[0]);
       }	  
      $SQLfinisher1 = "UPDATE users SET friendList='$newList' WHERE username='$username'";
      $mydb->query($SQLfinisher1);
  }
  else{
      $SQLFinisher2 = "UPDATE users SET friendList='$newFriendId2' WHERE username=$username";
      $mydb->query($SQLFinisher2);
  }
  $mydb->close();
  return true; //Return true on success
}

function rmFriend($username, $target)
{
  //SQL remove target from username's friend's list
  $mydb = dbConnect();   
  $sql = "SELECT id FROM users WHERE username='$target'";
  $myQuery = $mydb->query($sql);
  while($row = mysqli_fetch_row($myQuery))
  {
        $targetId = $row[0];
        logData("\n id to be removed: $targetId");
  }
  $targetSQL = "SELECT friendList FROM users WHERE username='$username'";
  $upsetUserId = $mydb->query($targetSQL);
  while($row = mysqli_fetch_row($upsetUserId))
  {
        $upsetList = $row[0];
        logData("\n friend list raw ids: " .$upsetList);
  }
  $friendListArray = explode(" ", $upsetList);
  $count = 0;
  while($count < count($friendListArray))
  {
     if($friendListArray[$count] == $targetId)
     {    
	$friendListArray[$count] = "";
	$friendListReturn = implode(" ", $friendListArray);    
	$sqlUpdate = "UPDATE users SET friendList='$friendListReturn' WHERE username='$username'";
        $mydb->query($sqlUpdate);
	$mydb->close();
	return true;
     }
     $count ++;
  }
  echo "No friend exists";
  $mydb->close();
  return false;
}

function getLeaderboard()
{
  $mydb = dbConnect();
  if(!isset($mydb))
  {logData("Failed to connect"); return;}
  $sql = "SELECT username, wins, losses FROM users Order by wins DESC";
  $getTopTen = $mydb->query($sql);
  $count = 0;
  $boardArray = array();
  while($row = mysqli_fetch_row($getTopTen)){ 
    array_push($boardArray, ($row[0] . " | " . $row[1] . " | " . $row[2]) );
    if($count == 9)
	    break;
    $count ++;
  }
  $mydb->close();
  return $boardArray;
}

function getSuggestion($username)
{
  $mydb = dbConnect();
  if(!isset($mydb))
  {logData("$username failed to connect to db"); return;}
  $sql = "SELECT username FROM users WHERE NOT username = '$username'";
  $myQuery = $mydb->query($sql);
  $userArray = array();
  while($row = mysqli_fetch_row($myQuery)){
  	array_push($userArray, $row[0]);
  }
  $random = rand(0, (count($userArray)-1));
  $mydb->close();
  return $userArray[$random];
}

function getHat1($id)
{
    $mydb = dbConnect();
    if(!isset($mydb))
    {logData("$username failed to connect to db"); return;}
    
}

function getProfile($username)
{
  /*$mydb = dbConnect();
  if(!isset($mydb))
  {logData("Failed to connect to db"); exit();}
  $sql = "SELECT * FROM users WHERE username = '$username'";
  $myQuery = $mydb->query($sql);
  $userInfo = array();
  while($row = mysqli_fetch_row($myQuery)){
        array_push($userInfo, $row[0]);
  }
  if(!isset($userInfo) || $userInfo = "")
  {
    return null;
  } 

  $mydb->close();
  return $userArray;
  */
  return null; 
}

function changeName($username, $password, $change)
{
  /*$mydb = dbConnect();
  if(!isset($mydb))
  {logData("Failed to connect to db"); exit();}
  $sql = "SELECT username FROM users WHERE username = '$username' AND password = $password";
  $myQuery = $mydb->query($sql);
  while($row = mysqli_fetch_row($myQuery))
  {
     if ($row[0] == $username)	 
     {
	$mydb->close();
        return false;	  
     }
  }
  $sql = "UPDATE users SET username = $username WHERE username = $username"
  $myQuery = $mydb->query($sql);
  if ($myQuery == false)
  {
    $mydb->close();
    return false;
  }

  $mydb->close();
   */
  return true;

}

function changePass($username, $password, $change)
{
  /*$mydb = dbConnect();
  if(!isset($mydb))
  {logData("Failed to connect to db"); exit();}
  $sql = "SELECT username FROM users WHERE username = '$username' AND password = $password";
  $myQuery = $mydb->query($sql);
  while($row = mysqli_fetch_row($myQuery))
  {
     if ($row[0] == $username)   
     {
        $mydb->close();
        return false;     
     }
  }
  $sql = "UPDATE users SET password = $password WHERE username = $username"
  $myQuery = $mydb->query($sql);
  if ($myQuery == false)
  {
    $mydb->close();
    return false;
  }

  $mydb->close();
   */
  return true;

}

function getBalance($username)
{
  return 100;
}

?>
