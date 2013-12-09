<?php
    session_start();
    require_once 'mysql/login.php';
    $phone = $pass = $name = $gender = "";
    $email = $_SESSION['email'];    
    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());

    $eventName = $_POST['eventName'];
    $activity = $_POST['activity'];
    $location = $_POST['location'].", ".$_POST['city'];
    $description = $_POST['description'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    $hour = $_POST['hour'];
    $minute = $_POST['minute'];
    $meridiem = $_POST['meridiem'];
    if ($meridiem == "pm")
        $hour+=12;
    $datetime = "2013-".$month."-".$day." ".$hour.":".$minute;
    $ts = date_create($datetime);
    $ts = $ts->format('Y-m-d H:i:s');
    $sql = "INSERT INTO Event (name, activity, location, description, ts, creator) values (\"".$eventName."\",'$activity','$location','$description','$ts','$email')";
    mysqli_query($connection,$sql);
    printf("Errormessage: %s\n", mysqli_error($connection,$sql));
?>
