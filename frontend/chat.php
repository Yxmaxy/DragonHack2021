<?php
session_start();

if(!$_SESSION['username'])
{
    header("Location: setUsername.php");
}

if (!isset($_SESSION['email'])) {
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
        const currentUser = <?php echo "\"" . $_SESSION["username"] . "\";"; ?>
        const chatArchive = {};

        function drawGifs(obj) {
            div = [document.getElementById("gifList1"), document.getElementById("gifList2")];
            div[0].innerHTML = "";
            div[1].innerHTML = "";
            //alert("Dela");
            //console.log("DrawGifs: " + obj);
            liha = false;
            obj.forEach(element => {
                liha = !liha;
                var url = element[0].url;
                var bigUrl = element[1].url;
                var x = element[0].dims[0];
                var y = element[0].dims[1];
                if (liha) {
                    //div[0].innerHTML += "<img src=\"" + url + "\" width=\"" + x + "px\" height=\"" + y + "px\">"
                    div[0].innerHTML += "<img onclick='Send(\""+bigUrl+"\");' src=\"" + url + "\">"
                } else {
                    //div[1].innerHTML += "<img src=\"" + url + "\" width=\"" + x + "px\" height=\"" + y + "px\">"
                    div[1].innerHTML += "<img onclick='Send(\""+bigUrl+"\");' src=\"" + url + "\">"
                }
            });
        }

        socket = null;
        chat_username = null;
        currentLink = "";

        function drawGIF(connect)
        {
            const userChat = document.getElementById("userChat");
            const sporocilo = document.createElement("div");
            const ime = document.createElement("div");
            const img = document.createElement("img");
            ime.innerHTML = connect.sender;
            img.src = connect.message;
            img.onload = () => {
                userChat.scrollTop = userChat.scrollHeight;
            }
            sporocilo.appendChild(ime);
            sporocilo.appendChild(img);
            return sporocilo;
        }

        function drawWrappedGIF(connect, upperText, lowerText)
        {
            const userChat = document.getElementById("userChat");
            
            const container = document.createElement("div");
            container.classList.add("imageContainer"); 
            const ime = document.createElement("div");
            const img = document.createElement("img");
            ime.innerHTML = connect.sender;
            img.src = connect.message;
            img.onload = () => {
                userChat.scrollTop = userChat.scrollHeight;
            }
            const upperTextDiv = document.createElement("div");
            upperTextDiv.classList.add("upperText"); 
            const lowerTextDiv = document.createElement("div");
            upperTextDiv.classList.add("lowerText");



            container.appendChild(ime);
            container.appendChild(img);
            container.appendChild(upperTextDiv);
            container.appendChild(lowerTextDiv);

            return container;
        }


        function Send(params, upperText, lowerText) {
            currentLink = params;
            var connect = {type: "send", username: chat_username, message: params, sender: currentUser};
            var text = JSON.stringify(connect)
            socket.send(text);

            if (upperText != undefined || lowerText != undefined) {
                userChat.appendChild(drawWrappedGIF(connect, upperText, lowerText));
                chatArchive[connect.username].appendChild(drawWrappedGIF(connect, upperText, lowerText));
                
            } else {
                userChat.appendChild(drawGIF(connect));
                chatArchive[connect.username].appendChild(drawGIF(connect));
            }

            

            console.log(chatArchive[connect.username],connect.username);
        }


        window.onload = () => {
            // da lahko kliknes enter za iskanje
            const searchQuery = document.getElementById("searchQuery");
            searchQuery.addEventListener("keyup", function (event) {
                // Number 13 is the "Enter" key on the keyboard
                if (event.keyCode === 13) {
                    requestGIFS();
                    document.getElementById("gifList").scrollTop = 0;
                }
            });

            // Create WebSocket connection.
            socket = new WebSocket('ws://server.gifmessenger.online');

            // Connection opened
            socket.addEventListener('open', function (event) {
                var username = "<?= $_SESSION["username"]?>";//document.getElementById("session_username").innerHTML;
                var connect = {type: "client hello!", username: username}
                var text = JSON.stringify(connect)
                socket.send(text);
            });

            // Listen for messages
            socket.addEventListener('message', function (event) {
                //console.log('Message from server ', event.data);
                var obj = JSON.parse(event.data);
                if (obj["type"] == "request return") {
                    drawGifs(obj["data"]);
                }
                if (obj["type"] == "who online") {
                    markupOnlineUsers(obj["data"]);
                }
                if (obj["type"] == "send") {
                    createMessage(obj["message"], obj["sender"], obj["username"]);
                }
            });
        }

        function createMessage(message, sender, reciever) {
            if (sender != undefined) {
                const userChat = document.getElementById("userChat");

                const sporocilo = document.createElement("div");
                const ime = document.createElement("div");
                const img = document.createElement("img");
                ime.innerHTML = sender;
                img.src = message;
                img.onload = () => {
                    userChat.scrollTop = userChat.scrollHeight;
                }
                
                sporocilo.appendChild(ime);
                sporocilo.appendChild(img);

                if (sender == chat_username) {
                    userChat.appendChild(sporocilo);
                }
                
                archiveChat(sender, sender, message);
            }
        }

        function archiveChat(sender, reciever, message) {
            console.log(sender, reciever)
            const sporocilo = document.createElement("div");
            const ime = document.createElement("div");
            const img = document.createElement("img");
            ime.innerHTML = sender;
            img.src = message;
            img.onload = () => {
                userChat.scrollTop = userChat.scrollHeight;
            }
            
            sporocilo.appendChild(ime);
            sporocilo.appendChild(img);

            chatArchive[sender].appendChild(sporocilo);

            console.log(chatArchive[sender],sender);
        }

        function selectChat(i) {
            var user = i["currentTarget"].innerText;
            chat_username = user;

            const userChat = document.getElementById("userChat");
            userChat.innerHTML = chatArchive[user].innerHTML;
            userChat.scrollTop = userChat.scrollHeight;

            console.log(chatArchive[user], user);

            document.getElementById("chattingWith").innerHTML = "Chatting with: " + chat_username;
        }

        function requestGIFS() {
            var st_slik = 50;
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

        function markupOnlineUsers(onlineUsers) {
            arrayOfUsers = document.getElementById("usersList").children;
            for (var i = 0; i < arrayOfUsers.length; i++) {
                var user = arrayOfUsers[i];
                const regex = /<img.*>/i;
                var username = user.innerHTML.replace(regex, "").replaceAll("\n", "").replace(/^ */i, "").replace(/ *$/i, "");
                
                // ta div je pol da ga uvozimo namest onih retardov
                chatArchive[username] = document.createElement("div");

                if (onlineUsers.includes(username)) {
                    user.style.backgroundColor = "green";
                    user.style.enabled = true;
                    user.addEventListener("click", function(x) {selectChat(x);});
                } else {
                    user.style.backgroundColor = "rgb(47, 54, 54)";
                    user.style.enabled = false;
                }
            }
        }

        function doggieSend() {
            Send("https://media.tenor.com/images/d7afbeb5c3b3efc48a86eb2c3450ceb8/tenor.gif", document.getElementById("upperTextInput").value, document.getElementById("lowerTextInput").value)
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
        <h3 id="chattingWith">Chatting with: </h3>
        <div id="userChat"></div>
        <div id="searchBar">
            <input id="searchQuery" placeholder="Enter a keyword">
            <button id="searchButton" onclick="requestGIFS()">🔎</button>
        </div>

    </div>
    <div id="gifSearch">
        <h3 style="display:flex; justify-content: space-between; align-items: center">GIFS with keyword <button class="styledButton" onclick="document.getElementById('makeOwnGifWrapper').style.display='flex'">Make caption 🖊</button></h3>
        <div id="gifList">
            <div id="gifList1"></div>
            <div id="gifList2"></div>
        </div>
    </div>
</main>

<?php
//echo '<p style="visibility: hidden" id="session_username">'.$_SESSION["username"].'</p>';

?>
<div id="makeOwnGifWrapper">
    <div id="makeOwnGif">
        <div id="settings">
            <div>
                <h2>Upper text</h2>
                <table>
                    <tr>
                        <td>Text:</td>
                        <td>
                            <input type="text" value="" id="upperTextInput"onkeyup="changeText(this.value, 'upper')">
                        </td>
                    </tr>
                </table>
            </div>
            <div>
                <h2>Lower text</h2>
                <table>
                    <tr>
                        <td>Text:</td>
                        <td>
                            <input type="text" value="" id="lowerTextInput" onkeyup="changeText(this.value, 'lower')">
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
            <button class="styledButton" onclick="doggieSend()">OKE</button>
            <button class="styledButton" onclick="cancelOwnGif(document.getElementById('makeOwnGifWrapper'))">Cancel</button>
        </div>
    </div>
</div>
</body>
</html>