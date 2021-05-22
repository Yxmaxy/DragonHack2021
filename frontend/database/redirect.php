<?php
require_once 'vendor/autoload.php';
  
// init configuration
$clientID = '235250556017-pnuqpjd5j9oucktd0h9usnq9ca75a90h.apps.googleusercontent.com';
$clientSecret = 'C-mn-vcRdrdXLclp8gNNQ9nM';
$redirectUri = 'http://gifmessenger.nikigre.si/frontend/database/redirect.php';
   
// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");
  
// authenticate code from Google OAuth Flow
if (isset($_GET['code'])) {
  $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
  $client->setAccessToken($token['access_token']);

  // get profile info
  $google_oauth = new Google_Service_Oauth2($client);
  $google_account_info = $google_oauth->userinfo->get();
  $email =  $google_account_info->email;
  $name =  $google_account_info->name;
  
  $id_token=$token['id_token'];
  $access_token=$token['access_token'];
  $picture=$google_account_info->picture;
 
  $user=UserExists($email);

  if($user !=false)
  {
      session_start();
      $_SESSION['email'] = $email;
      $_SESSION['username'] = $user;

      header("Location: ../chat.php");
  }
  else{
      InsertUser($email, $id_token, $access_token, $picture);
      
      session_start();
      $_SESSION['email'] = $email;
      
      header("Location: ../setUsername.php");
  }
}

function UserExists($email)
{
    include "db.php";
    $sql = "SELECT UserName FROM `Users` WHERE Email='" . mysqli_real_escape_string($conn, $email) . "'";
    $result = $conn->query($sql);
    
    if ($result->num_rows == 1) {
        while($row = $result->fetch_assoc()) {
            $username=$row['UserName'];
        }       
    }
    else
    {
        return false;
    }
    $conn->close();

    return $username;
}

function InsertUser($email, $id_token, $access_token, $picture)
{
    include "db.php";
    $sql="INSERT INTO `Users`(`id_token`, `access_token`, `UserName`, `Email`, `picture`) VALUES (
    '" . mysqli_real_escape_string($conn, $id_token) . "',
    '" . mysqli_real_escape_string($conn, $access_token) . "',
    NULL,
    '" . mysqli_real_escape_string($conn, $email) . "',
    '" . mysqli_real_escape_string($conn, $picture) . "'
    )";
    
    if ($conn->query($sql) === TRUE) {
      echo "OK";
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
    
    $conn->close();
    
}
?>