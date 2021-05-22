<?php
session_start();

if(!isset($_SESSION['email']))
{
    session_unset();
    //header("Location: index.php");
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
    <link rel="stylesheet" href="style/chat.css">
    <link rel="stylesheet" href="style/ownGif.css">

    <script src="ownGifHandler.js"></script>

    <script>
        /*window.onload = () => {
            const seznam = document.getElementById("usersList");
            const rit = document.getElementById("userChat");
            const ass = document.getElementById("gifList");

            for (let i = 0; i <= 50; i++) {
                const div = document.createElement("div");
                div.classList.add("user");
                div.textContent = "Nek uporabnik" + i
                
                seznam.appendChild(div);
            }
            
            // za scrollanje do dol
            // ass.scrollTop = ass.scrollHeight;
        }*/

        function drawGifs(obj) {
            div = [document.getElementById("gifList1"), document.getElementById("gifList2")];
            div[0].innerHTML = "";
            div[1].innerHTML = "";
            //alert("Dela");
            console.log("DrawGifs: " + obj);
            liha = false;
            obj.forEach(element => {
                liha = !liha;
                var url = element[0].url
                var x = element[0].dims[0]
                var y = element[0].dims[1]
                if (liha)
                {
                    //div[0].innerHTML += "<img src=\"" + url + "\" width=\"" + x + "px\" height=\"" + y + "px\">"
                    div[0].innerHTML += "<img src=\"" + url + "\">"
                }
                else
                {
                    //div[1].innerHTML += "<img src=\"" + url + "\" width=\"" + x + "px\" height=\"" + y + "px\">"
                    div[1].innerHTML += "<img src=\"" + url + "\">"
                }

            });
        }

        socket = null;

        window.onload = () => {
            
            // da lahko kliknes enter za iskanje
            const searchQuery = document.getElementById("searchQuery");
            searchQuery.addEventListener("keyup", function(event) {
            // Number 13 is the "Enter" key on the keyboard
            if (event.keyCode === 13) {
                requestGIFS();
            }
            });
            
            // Create WebSocket connection.
            socket = new WebSocket('ws://192.168.0.41:81');

            // Connection opened
            socket.addEventListener('open', function (event) {
                var username = "<?= $_SESSION["username"]?>"//document.getElementById("session_username").innerHTML;
                var connect = {type: "client hello!", username: username}
                var text = JSON.stringify(connect)
                socket.send(text);
            });

            // Listen for messages
            socket.addEventListener('message', function (event) {
                console.log('Message from server ', event.data);
                var obj = JSON.parse(event.data);
                if (obj["type"] == "request return") {
                    drawGifs(obj["data"]);
                }
                if (obj["type"] == "who online") {
                    markupOnlineUsers(obj["data"]);
                }
            });
        }

        function requestGIFS() {
            var st_slik = 30;
            var GIFkeywords = document.getElementById("searchQuery").value.split(",");
            var obj = {type: "request", numOfGifs: st_slik, keywords: GIFkeywords};
            var text = JSON.stringify(obj)
            socket.send(text);
        }

        function whoOnline() {
            var obj = {type: "who online"};
            var text = JSON.stringify(obj)
            socket.send(text);
        }

        function markupOnlineUsers(onlineUsers)
        {
            arrayOfUsers = document.getElementById("usersList").children;
            for (var i = 0; i < arrayOfUsers.length; i++)
            {
                var user = arrayOfUsers[i];
                const regex = /<img.*>/i;
                var username = user.innerHTML.replace(regex, "").replaceAll("\n", "").replace(/^ */i, "").replace(/ *$/i, "");
                console.log("MarkupOnlineUsers: " + username);
                if (onlineUsers.includes(username))
                {
                    user.style.backgroundColor = "green";
                    user.enabled = true;
                }
                else
                {
                    user.style.backgroundColor = "rgb(47, 54, 54)";
                    user.enabled = false;
                }
            }
        }
        
    </script>
</head>
<body>
    <header>
        Gif Messenger
        <a href="./database/logout.php" class='styledButton'>Log out</a>
    </header>
    <main>
        <div id="users">
            <h3>Users</h3>
            <div id="usersList">
            <?php
            include "./database/allUsers.php";
            ?>
            </div>  
        </div>
        <div id="chat">
            <h3>Chatting with: </h3>
            <!--div id="userChat">
                <div>
                    16:17 - Marko
                </div>
            </div-->
            <div id="searchBar">
                <input id="searchQuery" placeholder="Enter a keyword">
                <button id="searchButton" onclick="requestGIFS()">🔎</button>
            </div>
        
        </div>
        <div id="gifSearch">
            <h3>GIFS with keyword <button class="styledButton" onclick="document.getElementById('makeOwnGifWrapper').style.display='flex'">Make caption 🖊</button></h3>
            <div id="gifList">
                <div id="gifList1"></div>
                <div id="gifList2"></div>
            </div>
        </div>
    </main>

    <div id="makeOwnGifWrapper">
        <div id="makeOwnGif">
            <div id="settings">
                <div>
                    <h2>Upper text</h3>
                    <table>
                        <tr>
                            <td>Text:</td>
                            <td>
                                <input type="text" value="" onkeyup="changeText(this.value, 'upper')">
                            </td>
                        </tr>
                        <tr>
                            <td>Size:</td>
                            <td>
                                <input type="number" value="25" onchange="changeFont(this.value, 'upper')">
                            </td>
                        </tr>
                        <tr>
                            <td>Is bold?</td>
                            <td>
                                <input id="upperTextBoldCheck" type="checkbox" onchange="changeIsBold(this.checked, 'upper')">
                            </td>
                        </tr>
                        <tr>
                            <td>Font color:</td>
                            <td>
                                <input type="color" onchange="changeColor(this.value, 'upper')">
                            </td>
                        </tr>
                    </table>
                </div>
                <div>
                    <h2>Lower text</h3>
                    <table>
                        <tr>
                            <td>Text:</td>
                            <td>
                                <input type="text" value="" onkeyup="changeText(this.value, 'lower')">
                            </td>
                        </tr>
                        <tr>
                            <td>Size:</td>
                            <td>
                                <input type="number" value="25" onchange="changeFont(this.value, 'lower')">
                            </td>
                        </tr>
                        <tr>
                            <td>Is bold?</td>
                            <td>
                                <input id="upperTextBoldCheck" type="checkbox" onchange="changeIsBold(this.checked, 'lower')">
                            </td>
                        </tr>
                        <tr>
                            <td>Font color:</td>
                            <td>
                                <input type="color" onchange="changeColor(this.value, 'lower')">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="imageContainer">
                <img src="https://media.tenor.com/images/d7afbeb5c3b3efc48a86eb2c3450ceb8/tenor.gif">
                <div id="upperText"></div>
                <div id="lowerText"></div>
            </div>
            <div>
                <button class="styledButton">OKE</button>
                <button class="styledButton" onclick="document.getElementById('makeOwnGifWrapper').style.display='none'">Cancel</button>
            </div>
        </div>
    </div>
    
</body>
</html>