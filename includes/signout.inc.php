<?php

if(isset($_POST['signout']) === true) // check if button is clicked
{
    session_start(); // start session if session deosn't exist so why we would need to destroy it
    session_unset();
    session_destroy();
    header("Location: ../index.php");
    exit();
}
else{
    header("Location: ../index.php");
    exit();
}

?>
