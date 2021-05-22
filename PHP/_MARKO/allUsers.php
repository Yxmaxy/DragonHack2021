<?php

session_start();
if(isset($_SESSION['email']))
{
    include "../db.php";
    
    $sql="SELECT `UserName`, `picture` FROM `Users` WHERE 1 ";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
      while($row = $result->fetch_assoc()) {
        echo  "<p>" .$row["UserName"]. " <img src='" . $row["picture"] . "'></p>";
      }
    } else {
      echo "0 results";
    }
}

?>