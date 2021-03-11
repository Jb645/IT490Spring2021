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
      $mydb = dbConnect();
      $sql = "SELECT * FROM scores WHERE id=$id";
      if($mydb->query($sql) != TRUE){
         $newRowSql = "INSERT INTO scores (id) VALUES ($id)";
         $mydb->query($newRowSql);
      }
      $mydb->close();
}

function insertResults($request, $id=NULL, $Score=0, $Condition=NONE)
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
           $sqlWinScoreSearch = "SELECT lastWinningScore FROM scores WHERE id=$id";
           $winsScoreSearch = mysql_query($mydb, $sqlWinScoreSearch);
           if ($mydb->query($winsScoreSearch) == TRUE){
               $sqlWinScore = "UPDATE scores SET lastWinningScore=$Score WHERE id=$id";
               $mydb->query($sqlWinScore);
           }
           else{
                $sqlEmptyWinScore ="INSERT INTO scores (lastWinningScore) VALUES ($Score) WHERE id=$id";
                $mydb->query($sqlEmptyWinsScore);
           }
           $mydb->close();
	   break;
         case 'lscore':
           $loserscore = $request['lscore'];  //insert winner into users
           $mydb = dbConnect();
           scoreTableChecker($id);
           $sqlLossScoreSearch = "SELECT lastLosingScore FROM scores WHERE id=$id";
           $lossScoreSearch = mysql_query($mydb, $sqlLossScoreSearch);
           if ($mydb->query($lossScoreSearch) == TRUE){
               $sqlLossScore = "UPDATE scores SET lastLosingScore=$Score WHERE id=$id";	  
               $mydb->query($sqlLossScore);

           }
           else{
                $sqlEmptyLossesScore ="INSERT INTO scores (lastLosingScore) VALUES ($Score) WHERE id=$id";
                $mydb->query($sqlEmptyLossesScore);
           }
           $mydb->close();
           break;

         case 'wweather':
           $wweather = $request['wweather'];  //insert winner into users
           $mydb = dbConnect();
           scoreTableChecker($id);
           $sqlWinConditionSearch = "SELECT lastWinningCondition FROM scores WHERE id=$id";
           $winConditionSearch = mysql_query($mydb, $sqlWinConditionSearch);
           if ($mydb->query($winConditionSearch) == TRUE){
               $sqlWinCondition = "UPDATE scores SET lastWinningCondition=$Condition WHERE id=$id";
               $mydb->query($sqlWinCondition);
           }
           else{
                $sqlEmptyWinCondition ="INSERT INTO scores (lastWinningCondition) VALUES ($Condition) WHERE id=$id";
                $mydb->query($sqlEmptyWinCondition);
           }
           $mydb->close();
	   break;

         case 'lweather':
           $lweather = $request['lweather'];  //insert winner into users
           $mydb = dbConnect();
           scoreTableChecker($id);
   $sqlLossConditionSearch = "SELECT lastLosingCondition FROM scores WHERE id=$id";
           $lossConditionSearch = mysql_query($mydb, $sqlLossConditionSearch);
           if ($mydb->query($lossConditionSearch) == TRUE){
               $sqlLossCondition = "UPDATE scores SET lastLosingCondition=$Condition WHERE id=$id";
               $mydb->query($sqlLossConditon);

           }
           else{
                $sqlEmptyLossCondition ="INSERT INTO scores (lastLosingCondition) VALUES ($Condition) WHERE id=$id";
                $mydb->query($sqlEmptyLossCondition);
           }
           $mydb->close();
           break;
  }
}
  //Insert account info into Database
function getFriends($username)
{
  //SQL retrieve list of friends from DB
  $mydb = dbConnect();	
  $sql="SELECT friendList FROM users WHERE username=$username";
  $friendsRaw = $mydb->query($sql);
  $friendsArray = explode(" ", $friendsRaw);
  foreach($friendsArray as &$friends){
    $sqlFriendFinder = "SELECT username FROM users WHERE id=$friends";
    $friendOut = $mydb->query($sqlFriendFinder);
    echo $friendOut;  
  }
  unset($friends);
  $mydb->close();
  return true; //Return true on success
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
      //$newList = $mydb->query($friendCheckSQL) . "$newFriendId2";
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
  $targetSQL = "SELECT id FROM users WHERE username=$target";
  $badFriendId = $mydb->query($targetSQL);
  $friendListPullQ = "SELECT friendList FROM users WHERE username=$username";
  $friendListRaw = $mydb->query($friendListPullQ);
  $friendListArray = explode(" ", $friendListRaw);
  
  if(($key = array_search($badFriendId, $friendListArray)) != FALSE){
      unset($friendListArray[$key]);
  }

  $friendListReturn = implode(" ", $friendListArray);
  $sqlUpdate = "UPDATE users SET friendList=$friendListReturn WHERE username=$username";
  $mydb->query($sqlUpdate);
  $mydb->close();
  return true; //Return true on success
}
?>
