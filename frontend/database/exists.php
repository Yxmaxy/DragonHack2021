<?php
if(isset($_GET['usernameExists']))
{
	//Preveri, če uporabnik s tem uporabniškim imenom že obstaja.
    include "db.php";
    
    $sql="SELECT `ID` FROM `Users` WHERE `UserName`='". mysqli_real_escape_string($conn, $_GET['usernameExists']) ."'";
    echo $sql;
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        echo "FALSE";
    } else {
      echo "TRUE";
    }
    $conn->close();
}
?>