<?php
include('redirect.php');
session_start();
$accesstoken=$_SESSION['access_token'];

//Reset OAuth access token
$client->revokeToken($accesstoken);

//Destroy entire session data.
session_destroy();

//redirect page to index.php
header('location: ../index.php');