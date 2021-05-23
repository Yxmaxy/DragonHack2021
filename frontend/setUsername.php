<?php

if(isset($_POST['username']))
{
    session_start();
    
    $rez = UpdateUsername($_SESSION['email'], $_POST['username']);
    
    if($rez==0)
    {		
		$_SESSION['username'] = $_POST['username'];
        header("Location: chat.php");
    }
    else if($rez==1){
        echo "Že nastavljeno";
    }
    else{
        echo "Error";
    }
    
}


function UpdateUsername($email, $username)
{
    include "database/db.php";
    
    $sql="SELECT UpdateUsername('". mysqli_real_escape_string($conn, $_POST['username']) ."', '". mysqli_real_escape_string($conn, $email) ."')";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        return 0;
    } else {
          if(strpos($conn->error, "Username is")===false)
          {
              return 2;
          }
          else{
              return 1;
          }
    }
        
    $conn->close();
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gif Messenger</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/setUsername.css">

    <script>
        function checkUsername()
            {
                var xmlHttp = new XMLHttpRequest();
                xmlHttp.onreadystatechange = function() { 
                    if (xmlHttp.status == 200)
                    {
                        if(xmlHttp.responseText.includes("FALSE"))
                        {
                            document.getElementById("exists").innerHTML = "<div style='color: green;'>The username is available.</div>";
                        }
                        else{
                            document.getElementById("exists").innerHTML = "<div style='color: red;'>The username is not available.</div>";
                        }
                    }
                }
                xmlHttp.open("GET",  "./database/exists.php?usernameExists=" + document.getElementById("username").value, true ); // true for asynchronous 
                xmlHttp.send(null);
            }
    </script>
</head>
<body>
    <header>
        Gif Messenger
    </header>
    <main>
        <form action="" method="POST">
            <label for="username">Desired username:</label><br>
            <input ID='username' name='username' type='text' maxlength='20' onchange='checkUsername();' onkeyup='checkUsername();' oninput='checkUsername();'>
            <input id="logInOutButton" type="submit" id="submit" value="Set username!">
            <div id='exists'></div>
        </form>
    </main>
</body>
</html>