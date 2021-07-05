<?php
if(isset($_POST['username']))
{
    $id=UserExists($_POST['username']);
    
    if($id == $_SESSION['id'])
    {
        echo "<script>alert('You can not add yourself 😂!');</script>";
    }
    else if($id==-1)
    {
        echo "<script>alert('User does not exists!');</script>";
    }
    else
    {
       
        $friends = AreWeFriends($id);
        if($friends==-3)
        {
            SendFriendRequest($id);
        }
        else
        {
            $sporocilo="";
            switch ($friends)
            {
                case 0: $sporocilo="You are allready friend with " . $_POST['username'] . " 😉"; break;
                case -1: AddBack($id); break;
                case 1: $sporocilo="Your friend did not added you back. 😔"; break;
                default: $sporocilo="That was not suposed to happen."; break;
            }
            echo "<script>alert('" . $sporocilo . "');</script>";
        }
    }
}
?>
<form action="" method="POST">
    <input type="text" name="username" onfocus="this.value=''" value="username">
    <input type="submit" value="Add!">
</form>

<?php

function UserExists($user)
{
    include "db.php";
    
    $sql="SELECT `ID` FROM `Users` WHERE `UserName`='" . mysqli_real_escape_string($conn, $user) . "'";

    $rez=-1;
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
        while($row = $result->fetch_assoc()) {
            $rez=$row["ID"];
        }
    }
    
    $conn->close();
    
    return $rez;
}

function AreWeFriends($id)
{
    include "db.php";
    
    $sql="SELECT `Accepted1`, `Accepted2`, IDuser1, IDuser2 FROM `Friends` WHERE (`IDuser1`='" . mysqli_real_escape_string($conn, $id) . "' AND IDuser2='" . mysqli_real_escape_string($conn, $_SESSION['id']) . "') OR 
    (`IDuser1`='" . mysqli_real_escape_string($conn, $_SESSION['id']) . "' AND IDuser2='" . mysqli_real_escape_string($conn, $id) . "')";
 
    $rez=-3;
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
      // output data of each row
        while($row = $result->fetch_assoc()) {
            
            //Če je oboje enako, potem to pomeni, da sta prijatelja
            if($row['Accepted1']== 1 && $row['Accepted2']== 1)
            {
                $rez= 0;
            }
            
            if($row['IDuser1']== $_SESSION['id'])
            {
                if($row['Accepted1']== 0)
                {
                    $rez= -1;
                    goto a;
                }
                
                if($row['Accepted2']== 0)
                {
                    $rez= 1;
                }
            }
            else{
                if($row['Accepted1']== 0)
                {
                    $rez= 1;
                    goto a;
                }
                
                if($row['Accepted2']== 0)
                {
                    $rez= -1;
                }
            }
            
        }
    }
  a:  
    $conn->close();
    
    return $rez;
}

function SendFriendRequest($id)
{
    include "db.php";
    
    $sql = "INSERT INTO `Friends`(`IDuser1`, `IDuser2`, `Accepted1`) VALUES ('" . mysqli_real_escape_string($conn, $_SESSION['id']) . "','" . mysqli_real_escape_string($conn, $id) . "',1)";
    
    if ($conn->query($sql) === TRUE) {
       echo "<script>alert('Friend request was sent! 😃');</script>";
    } else {
       echo "<script>alert('Error!');</script>";
    }
    
    $conn->close();
}

function AddBack($id)
{
    include "db.php";
    
    $sql = "UPDATE `Friends` SET `Accepted1`=1,`Accepted2`=1 WHERE (IDuser1='" . mysqli_real_escape_string($conn, $_SESSION['id']) . "' AND IDuser2='" . mysqli_real_escape_string($conn, $id) . "')OR (IDuser2='" . mysqli_real_escape_string($conn, $_SESSION['id']) . "' AND IDuser1='" . mysqli_real_escape_string($conn, $id) . "')";
    
    if ($conn->query($sql) === TRUE) {
       echo "<script>alert('Friend was added! 😉');</script>";
    } else {
       echo "<script>alert('Error!');</script>";
    }
    
    $conn->close();
}


?>