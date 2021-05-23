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
                container.style.animationDuration = duration + "s";
            }
    
            // Create WebSocket connection.
            socket = new WebSocket('ws://server.gifmessenger.online');

            // Connection opened
            socket.addEventListener('open', function (event) {
                requestGIFS();
            });

            // Listen for messages
            socket.addEventListener('message', function (event) {
                var obj = JSON.parse(event.data);
                if (obj["type"] == "request return") {
                    drawGifs(obj["data"]);
                }
            });
        }

        function requestGIFS() {
            var st_slik = 13;
            var all = ["cat", "dog", "mouse", "computer" , "hacking", "programming", "bby", "love you", "i hate you", "idk", "GIF", "mbby", "I don't", "RK"];
            var GIFkeywords = [all[giveRandom(all.length)], all[giveRandom(all.length)], all[giveRandom(all.length)], all[giveRandom(all.length)] ];
            var obj = {type: "request", numOfGifs: st_slik, keywords: GIFkeywords};
            var text = JSON.stringify(obj)
            socket.send(text);
        }

        function giveRandom(max)
        {
            return Math.floor((Math.random() * max) + 1);
        }

        function drawGifs(obj) {
            div = [document.getElementById("first"),
            document.getElementById("second"),
            document.getElementById("third"),
            document.getElementById("forth")];
            var i=0;

            obj.forEach(element => {
                var url = element[0].url
                var x = element[0].dims[0]
                var y = element[0].dims[1]
                
                div[i].innerHTML +="<img src='"+url+"'>";

                if(i==3)
                    i=-1;
                i++;
            });
        }
    </script>
</head>
<body>
    <header>
        Gif Messenger
        <?php 
        echo "<a class='styledButton' href='".$client->createAuthUrl()."'>Sign in</a>";
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
            <div id="first">

            </div>
            <div id="second">
  
            </div>
            <div id="third">

            </div>
            <div id="forth">

            </div>
        </div>
    </main>
</body>
</html>