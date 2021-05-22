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
    <script>

        // za dolocanje nakljucnih vrednosti ob zagonu strani
        window.onload = () => {
            const movingDivs = document.getElementById("gifs").children;
            
            for (let container of movingDivs) {
                const duration = Math.random() * 5 + 7;
                console.log(duration);

                container.style.animationDuration = duration + "s";
            }
        }
    </script>
</head>
<body>
    <header>
        Gif Messenger
        <?php 
        echo "<a id='logInOutButton' href='".$client->createAuthUrl()."'>Sign in</a>";
        ?>
    </header>
    <main>
        <div id="writing">
            <h1>Now what is this?</h1>
            <p>
                Have you ever wanted to communicate in only GIFs? Well now you can!
            </p>
            <h2>But why?</h2>
            <p>
                Because GIFs are AWESOME 🤩
            </p>
        </div>
        <div id="gifs">
            <div>
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/count.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
            </div>
            <div>
                <img src="tmp/count.gif">
                <img src="tmp/count.gif">
                <img src="tmp/jajca.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/count.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
            </div>
            <div>
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/count.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
            </div>
            <div>
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/count.gif">
                <img src="tmp/earth.gif">
                <img src="tmp/earth.gif">
            </div>
        </div>
    </main>
</body>
</html>