<?php

     session_start();
    if(isset($_SESSION['u_email']))
    {
    include_once dirname(__FILE__) . '/../includes/dbh.inc.php';
    $fromDatabase = "SELECT id, Device_Id, Coordinates, Choise, created_at FROM `maps`"; // selet all data from database
    $result = mysqli_query($sql, $fromDatabase);// all results
    $result2 = mysqli_query($sql, $fromDatabase);
    if(mysqli_num_rows($result) > 0)
    {
        $count = 0;
        $places = array(1);
        while($row = mysqli_fetch_assoc($result))
        {
            $id = "".$row['Device_Id']."";
            echo('
            <div class="device">
            <div class="id" >Device id: '.$row["Device_Id"].'</div>
            <input type="hidden" id="device'.$count.'" value="'.$row["Device_Id"].'">
            <div class="choise">'.$row["Choise"].'</div>
            <div class="time">'.$row["created_at"].'</div>
            </div>');
            $count++;

        }

    }
    else
    {
        echo('<div>Add data to database if you want to see results</div>');

    }
    }
else
{
    header("Location: ../index.php");
    exit();
}

?>
