<?php
session_start();

if (!$_SESSION['username']) {
    header("Location: setUsername.php");
}

if (!isset($_SESSION['email'])) {
    session_unset();
    header("Location: index.php");
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
        socket = null;
        chat_username = null;
        currentLink = "";

        // used for drawing gifs that are on the right side
        function drawGifs(obj) {
            div = [document.getElementById("gifList1"), document.getElementById("gifList2")];
            div[0].innerHTML = "";
            div[1].innerHTML = "";
            liha = false;

            // loop alternates between sides of the gif list
            obj.forEach(element => {
                liha = !liha;

                var url = element[0].url;
                var bigUrl = element[1].url;
                var x = element[0].dims[0];
                var y = element[0].dims[1];
                
                if (liha) {
                    div[0].innerHTML += "<img onclick='Send(\""+bigUrl+"\");' src=\"" + url + "\">";
                } else {
                    div[1].innerHTML += "<img onclick='Send(\""+bigUrl+"\");' src=\"" + url + "\">";
                }
            });
        }

        // is used for drawing GIFs in the chat view
        function drawGIF(connect) {
            const userChat = document.getElementById("userChat");
            const container = document.createElement("div");
            const ime = document.createElement("div");
            const img = document.createElement("img");

            ime.innerHTML = connect.sender;
            img.src = connect.message;
            img.onload = () => {
                userChat.scrollTop = userChat.scrollHeight;
            }

            container.appendChild(ime);
            container.appendChild(img);
            
            return container;
        }

        // the same as above, except used for gifs with custom text
        function drawWrappedGIF(connect) {
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
            upperTextDiv.textContent = connect.upperText;
            const lowerTextDiv = document.createElement("div");
            lowerTextDiv.classList.add("lowerText");
            lowerTextDiv.textContent = connect.lowerText;

            container.appendChild(ime);
            container.appendChild(img);
            container.appendChild(upperTextDiv);
            container.appendChild(lowerTextDiv);

            return container;
        }

        // used for sending data over sockets
        function Send(params, upperText, lowerText) {
            currentLink = params;
            var connect = {type: "send", username: chat_username, message: params, sender: currentUser};

            if (upperText != undefined || lowerText != undefined) {
                // data is modified to include top and bottom text of gif with custom text
                connect = {type: "send", username: chat_username, message: params, sender: currentUser, upperText: upperText, lowerText, lowerText};
                
                // data is sent via socket
                var text = JSON.stringify(connect)
                socket.send(text);
                
                userChat.appendChild(drawWrappedGIF(connect));
                chatArchive[connect.username].appendChild(drawWrappedGIF(connect));
                
            } else {
                // data is sent via socket
                var text = JSON.stringify(connect)
                socket.send(text);

                userChat.appendChild(drawGIF(connect));
                chatArchive[connect.username].appendChild(drawGIF(connect));
            }
        }


        window.onload = () => {
            // so that it's possible to press Enter to search
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
                var username = "<?= $_SESSION["username"]?>";
                var connect = {type: "client hello!", username: username}
                
                var text = JSON.stringify(connect)
                socket.send(text);
            });

            // Listen for messages
            socket.addEventListener('message', function (event) {
                var obj = JSON.parse(event.data);
                // draws gifs
                if (obj["type"] == "request return") {
                    drawGifs(obj["data"]);
                }
                // gets online users
                if (obj["type"] == "who online") {
                    markupOnlineUsers(obj["data"]);
                }
                // when gif data was recieved
                if (obj["type"] == "send") {
                    createMessage(obj["message"], obj["sender"], obj["username"], obj["upperText"], obj["lowerText"]);
                    if (obj["username"] == currentUser) {
                        // audio when gif is recieved
                        var audio = new Audio('alert.mp3');
                        audio.play();
                    }
                }
            });
        }

        function createMessage(message, sender, reciever, upperText, lowerText) {
            if (sender != undefined) {
                // check if we are dealing with a GIF with custom text
                if (upperText != undefined || lowerText != undefined) {
                    // modify data with upper and lower text
                    const connect = {username: reciever, message: message, sender: sender, upperText: upperText, lowerText: lowerText};
                    const userChat = document.getElementById("userChat");

                    // so we know if we can show the message directly (we are chatting with the user who sent the gif)
                    if (sender == chat_username) {
                        userChat.appendChild(drawWrappedGIF(connect));
                    }
                    chatArchive[connect.sender].appendChild(drawWrappedGIF(connect));
                }
                else {
                    const connect = {username: reciever, message: message, sender: sender};
                    const userChat = document.getElementById("userChat");

                    // so we know if we can show the message directly (we are chatting with the user who sent the gif)
                    if (sender == chat_username) {
                        userChat.appendChild(drawGIF(connect));
                    }
                    chatArchive[connect.sender].appendChild(drawGIF(connect));
                }
            }
        }

        // used for selecting user from left menu
        function selectChat(i) {
            var user = i["currentTarget"].innerText;
            chat_username = user;

            const userChat = document.getElementById("userChat");
            userChat.innerHTML = chatArchive[user].innerHTML;
            userChat.scrollTop = userChat.scrollHeight;

            document.getElementById("chattingWith").innerHTML = "Chatting with: " + chat_username;
            document.getElementById("searchQuery").disabled = false;
            document.getElementById("searchButton").disabled = false;
        }

        // for gifs in preview
        function requestGIFS() {
            var st_slik = 50;
            var GIFkeywords = document.getElementById("searchQuery").value.split(",");
            var obj = {type: "request", numOfGifs: st_slik, keywords: GIFkeywords};
            var text = JSON.stringify(obj)
            socket.send(text);
        }

        // for user list
        function whoOnline() {
            var obj = {type: "who online"};
            var text = JSON.stringify(obj)
            socket.send(text);
        }

        // modifies the look of users in userList
        function markupOnlineUsers(onlineUsers) {
            arrayOfUsers = document.getElementById("usersList").children;
            for (var i = 0; i < arrayOfUsers.length; i++) {
                var user = arrayOfUsers[i];
                const regex = /<img.*>/i;
                var username = user.innerHTML.replace(regex, "").replaceAll("\n", "").replace(/^ */i, "").replace(/ *$/i, "");
                
                chatArchive[username] = document.createElement("div");

                // if user is online
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
        <h3 id="chattingWith">Please select a user from the list...</h3>
        <div id="userChat"></div>
        <div id="searchBar">
            <input id="searchQuery" placeholder="Enter a keyword" disabled>
            <button id="searchButton" onclick="requestGIFS()" disabled>🔎</button>
        </div>

    </div>
    <div id="gifSearch">
        <h3 style="display:flex; justify-content: space-between; align-items: center">GIFS with keyword <button style="padding: 5px;" class="styledButton" onclick="document.getElementById('makeOwnGifWrapper').style.display='flex'">Make caption 🖊</button></h3>
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
            <div id="upperTextID" class="upperText"></div>
            <div id="lowerTextID" class="lowerText"></div>
        </div>
        <div>
            <button class="styledButton" onclick="doggieSend()">OKE</button>
            <button class="styledButton" onclick="resetDoggie()">Cancel</button>
        </div>
    </div>
</div>
</body>
</html>