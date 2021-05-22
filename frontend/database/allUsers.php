<?php

//session_start();
            
//if(isset($_SESSION['email']))
{
    include "./database/db.php";
    
    $sql="SELECT `UserName`, `picture` FROM `Users` WHERE 1 ";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<script>console.log('tu sem')</script>";
        echo "<div class='user'>
                <img src='" . $row["picture"] . "'>
                " . $row["UserName"]  . "
            </div>";
    }
    } else {
        echo "test";
    }
}

?>