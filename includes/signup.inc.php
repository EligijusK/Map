<?php

if(isset($_POST['submit1']) === true)
{   include_once('header.php');
    include_once dirname(__FILE__) . '/../includes/dbh.inc.php';
    $name = mysqli_real_escape_string($sql, $_POST['name']); // input control that doesn't allow to send code to database
    $email = mysqli_real_escape_string($sql, $_POST['email']);
    $pass = mysqli_real_escape_string($sql, $_POST['pass']);
    if(empty($name) || empty($email) || empty($pass)) //all ifs check if data is correct
    {
        header("Location: ../signup.php");
        exit();
    }
    else
    {
        if(!preg_match("/^[a-zA-Z]*$/", $name))
        {
            header("Location: ../signup.php?signup=invalid");
            exit();
        }
        else
        {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                header('Location: ../signup.php?signup=invalid_email');
                exit();
            }
            else
            {
                $sqlemail = "SELECT * FROM `users` WHERE email='$email'"; // select row with email and then checks if email exist
                $result = $sql->query($sqlemail);
                $resultCheck = mysqli_num_rows($result);
                if($resultCheck > 0)
                {
                    header('Location: ../signup.php?signup=already_registered');
                    exit();
                }
                else
                {
                    $hashedPass = password_hash($pass, PASSWORD_DEFAULT); // if email doesn't exist then encripts password and insert to database
                    $insert = "INSERT INTO `users` (name, email, password, created_at, updated_at) VALUE('$name', '$email', '$hashedPass', NOW(), NOW() );";
                    if($sql->query($insert))
                    {
                        header('Location: ../signup.php?signup=sucess');
                        exit();
                    }
                    else
                    {
                        header('Location: ../signup.php?signup=database_error');
                        exit();
                    }
                }
            }
        }
    }
}
else
{
  header('Location: ../signup.php');
  exit();
}

?>
