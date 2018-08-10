<?php
    session_start();
    if(isset($_SESSION["u_email"]))
    {
    include_once dirname(__FILE__) . '/../includes/dbh.inc.php';
    $fromDatabase = "SELECT id, Device_Id, Coordinates, Choise, created_at FROM `maps`"; // selet all data from database
    $result = mysqli_query($sql, $fromDatabase); // all results
    if(mysqli_num_rows($result) > 0)
    { $count = 0;
        $array = array(mysqli_num_rows($result));
        while($row = mysqli_fetch_assoc($result))
        {
            $array[$count] = array(4);
            $coordinates =  preg_replace('/\s/', '', $row['Coordinates']);
            $CoordinatesArray = explode(',', $coordinates); // make two varables for cordinates
            $array[$count][0] = $row['Device_Id'];
            $array[$count][1] = $row['Choise'];
            $array[$count][2] = $CoordinatesArray[0];
            $array[$count][3] = $CoordinatesArray[1];
            $array[$count][4] = $count;
            $count++;

        }
                 echo json_encode($array); // return all data from database

    }
    }
    else
    {
    header("Location: ../index.php?");
    exit();
    }
?>
