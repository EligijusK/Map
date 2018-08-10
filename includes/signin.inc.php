<?php
session_start();

if(isset($_POST['login']) === true)
{
    include_once dirname(__FILE__) . '/../includes/dbh.inc.php';
    $email = mysqli_real_escape_string($sql, $_POST['email']); // string control
    $pass = mysqli_real_escape_string($sql, $_POST['pass']);
    if(empty($email) || empty($pass))
    {
            header("Location: ../index.php?signin=empty_fields");
            exit();
    }
    else
    {
        $exsist = "SELECT * FROM `users` WHERE email = '$email'"; // check if row with email exist then check if password is the same
        $quer = mysqli_query($sql, $exsist);
        $result = mysqli_num_rows($quer);
        if($result > 1)
        {
           if($row = mysqli_fetch_assoc($quer))
           {
               $hashedpasscheck = password_verify($pass, $row['password']);
                   if($hashedpasscheck === false)
                   {
                        header("Location: ../index.php?signin=invaild_password");
                        exit();
                   }else if($hashedpasscheck === true)
                   {
                       // log in to the website
                       $_SESSION['u_email'] = $row['email'];
                       $_SESSION['u_name'] = $row['name'];
                       header("Location: ../index.php?signin=sucess");
                       exit();
                   }
           }
        }
        else
        {
            header("Location: ../index.php?signin=database_empty");
            exit();
        }
    }
}

?>
