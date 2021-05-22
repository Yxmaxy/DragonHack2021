<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gif Messenger</title>
    <link rel="stylesheet" href="style/main.css">
    <link rel="stylesheet" href="style/chat.css">

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
    </script>
</head>
<body>
    <header>
        Gif Messenger
        <a id='logInOutButton'>Log out</a>
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
                <input placeholder="Enter a keyword">
                <button>🔎</button>
            </div>
        
        </div>
        <div id="gifSearch">
            <h3>GIFS with keyword</h3>
            <div id="gifList">
                <div id="gifList1"></div>
                <div id="gifList2"></div>
            </div>
        </div>
    </main>
</body>
</html>