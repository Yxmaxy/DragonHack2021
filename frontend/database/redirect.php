<?php
require_once 'vendor/autoload.php';

// init configuration
$clientID = '235250556017-pnuqpjd5j9oucktd0h9usnq9ca75a90h.apps.googleusercontent.com';
$clientSecret = 'C-mn-vcRdrdXLclp8gNNQ9nM';
$redirectUri = 'http://localhost/DragonHack2021/frontend/database/redirect.php';

// create Client Request to access Google API
$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

session_start();

// authenticate code from Google OAuth Flow
if (isset($_GET['code']))
{
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token['access_token']);

    // get profile info
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth
        ->userinfo
        ->get();
    $email = $google_account_info->email;
    $name = $google_account_info->name;

    $id_token = $token['id_token'];
    $access_token = $token['access_token'];
    $picture = $google_account_info->picture;
	
	//Preveri, kakšno je stanje uporabnika
    $user = UserExists($email);

	//Če je user že registriran z uporabniškim imenom
    if ($user != false)
    {
        $_SESSION['email'] = $email;
        $_SESSION['username'] = $user;
        $_SESSION['access_token'] = $access_token;
		
		//Preusmeri na chat
        header("Location: ../chat.php");
    }
    else
    {
		//Ustavi novega userja v tabelo
        InsertUser($email, $id_token, $access_token, $picture);

        $_SESSION['email'] = $email;
		
		//Preusmeri ga na stran, da nastavi username
        header("Location: ../setUsername.php");
    }
}

//Preveri, če user obstaja
function UserExists($email)
{
    include "db.php";
    $sql = "SELECT UserName FROM `Users` WHERE Email='" . mysqli_real_escape_string($conn, $email) . "'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1)
    {
        while ($row = $result->fetch_assoc())
        {
			//Dobi username
            $username = $row['UserName'];
        }
    }
    else
    {
		//Če ga ni, vrne false
        return false;
    }
    $conn->close();
	
	//Vrne username
    return $username;
}

//Vstavi novega uporabnika
function InsertUser($email, $id_token, $access_token, $picture)
{
    include "db.php";
	
    $sql = "INSERT INTO `Users`(`id_token`, `access_token`, `UserName`, `Email`, `picture`) VALUES (
    '" . mysqli_real_escape_string($conn, $id_token) . "',
    '" . mysqli_real_escape_string($conn, $access_token) . "',
    NULL,
    '" . mysqli_real_escape_string($conn, $email) . "',
    '" . mysqli_real_escape_string($conn, $picture) . "'
    )";

    if ($conn->query($sql) === true)
    {
		//Če je uspelo, potem OK
        echo "OK";
    }
    else
    {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();

}
?>
