<?php
//Če je post nastavljen, potem je bil form submitan
if (isset($_POST['username']))
{
    session_start();
	
	//Posodobi uporabniško ime
    $rez = UpdateUsername($_SESSION['email'], $_POST['username']);

	//Če je 0, potem pomeni, da je uspelo!
    if ($rez == 0)
    {
		//Nastavi username in pojdi na chat
        $_SESSION['username'] = $_POST['username'];
        header("Location: chat.php");
    }
	//Če je 1, to pomeni, da je username že nastavljen
    else if ($rez == 1)
    {
        echo "Že nastavljeno";
    }
	//Če je karkoli drugega, potem izpiši error
    else
    {
        echo "Error";
    }

}

//Funkcija kliče funkcijo v MySQL za posodbaljanje uporabniškega imena
function UpdateUsername($email, $username)
{
    include "database/db.php";

    $sql = "SELECT UpdateUsername('" . mysqli_real_escape_string($conn, $_POST['username']) . "', '" . mysqli_real_escape_string($conn, $email) . "')";

    $result = $conn->query($sql);

	//Če je več kot 1 vrstica, potem pomeni, da je uspelo in je vse ok :D
    if ($result->num_rows > 0)
    {
        return 0;
    }
    else
    {
		//Če error vsebuje "Username is" (custom nastavljen error), to pomeni, da je uporabniško ime že nastavljeno
        if (strpos($conn->error, "Username is") === false)
        {
            return 2;
        }
		//V nasprotnem primeru, vrne 1
        else
        {
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
            <input class="styledButton" type="submit" id="submit" value="Set username!">
            <div id='exists'></div>
        </form>
    </main>
</body>
</html>
