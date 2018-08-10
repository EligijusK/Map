<?php
    include_once('header.php');
    if(isset($_SESSION['u_email']))
    {
        echo("<div class='registreda'>you already registered</div>");
    }
else{
    echo('
    <div class="signup">
        <h2>SignUp</h2>
        <form method="POST" action="includes/signup.inc.php">
        <div>
            <lable>Name:</lable>
            <input type="text" name="name">
        </div>
        <div>
            <lable>Email:</lable>
            <input type="email" name="email">
        </div>
        <div>
            <lable>Password:</lable>
            <input type="password" name="pass">
        </div>
            <button type="submit" name="submit1">Register</button>
        </form>
    </div>
        ');
}
    include_once('footer.php');
?>
