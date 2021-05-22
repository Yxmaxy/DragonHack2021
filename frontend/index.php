<?php 
include("./database/redirect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gif Messenger</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/landing.css">
</head>
<body>
    <header>
        Gif Messenger
        <?php 
        echo "<a id='login' href='".$client->createAuthUrl()."'>Sign in</a>";
        ?>
    </header>
    <main>
        <div id="writing">
            <h1>Opis</h1>
            <p>
                Have you ever wanted to communicate in only GIFs? Well now you can!
            </p>
            <h2>Why?</h2>
            <p>
                Because GIFs are AWESOME 🤩
            </p>
        </div>
        <div id="gifs">
            Tu se bojo pol gifi slideal
        </div>
    </main>
</body>
</html>