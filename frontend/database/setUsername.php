<?php

if(isset($_POST['username']))
{
    session_start();
    
    $rez = UpdateUsername($_SESSION['email'], $_POST['username']);
    
    if($rez==0)
    {		
		$_SESSION['username'] = $_POST['username'];
        header("Location: ../chat.php");
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
    include "db.php";
    
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
            xmlHttp.open("GET",  "https://gifmessenger.nikigre.si/api.php?usernameExists=" + document.getElementById("username").value, true ); // true for asynchronous 
            xmlHttp.send(null);
        }
</script>

 <form action="" method="POST">
  <label for="username">Desired username:</label><br>
  <input ID='username' name='username' type='text' maxlength='20' onchange='checkUsername();' onkeyup='checkUsername();' oninput='checkUsername();'>
  <input type="submit" id="submit" value="Set username!">
</form> 
<div id='exists'></div>
