<?php
 session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-3.3.1.min.js"></script>


</head>
<body>

<header>
    <nav>
    <div class="main-wrapper">
        <ul>
            <li><a href="index.php">Home</a></li>
        </ul>
        <div class="nav-login">
        <?php
        if(isset($_SESSION['u_email']))
        {
            echo('<form method="post" action="includes/signout.inc.php">
                <button type="submit" name="signout">SignOut</button>
                </form>');
        }
        else
        {
          echo('
                <form method="post" action="includes/signin.inc.php">
                <input type="email" name="email" placeholder="Email">
                <input type="password" name="pass" placeholder="Password">
                <button type="submit" name="login">Login</button>
                </form>
                <a href="signup.php">SignUp</a>
        ');
        }
        ?>
        </div>
    </div>
    </nav>
</header>
