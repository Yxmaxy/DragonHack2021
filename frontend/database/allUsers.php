<?php

// Izpiše vse uporabnike v sistemu v formatu, ki je prikazan na levi strani

if(isset($_SESSION['email']))
{
    include "./database/db.php";
    
    $sql="
    SELECT UserName, Picture FROM `Friends` JOIN Users ON Users.ID=Friends.IDuser2 WHERE IDuser1=" . mysqli_real_escape_string($conn, $_SESSION['id']) . " AND (`Accepted1`=1 AND `Accepted2`=1)
    UNION
    SELECT UserName, Picture FROM `Friends` JOIN Users ON Users.ID=Friends.IDuser1 WHERE IDuser2=" . mysqli_real_escape_string($conn, $_SESSION['id']) . " AND (`Accepted1`=1 AND `Accepted2`=1)
    ";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<div class='user'>
                    <img src='" . $row["Picture"] . "'>
                    " . $row["UserName"]  . "
                </div>";
    }
    } else {
        //echo "<b>You don't have any firends on GifMessenger 😔 Add them bellow :D</b>";
    }
}

?>
