<html>



<body>



<br><a href="tables.php">Display Schema</a><br><br><br>

<?php
    require_once 'login.php';
    /* connect to the db */
    $connection = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
    
    echo '<b>Current tables (Location and Located are not yet finished!):</b><br><br>';
    
    /* show tables */
    
    $result = mysql_query('SHOW TABLES',$connection) or die('cannot show tables');
    while($tableName = mysql_fetch_row($result)) {
        
        
        $table = $tableName[0];
        
        error_reporting(E_ALL);
        
        $pagename = 'my_page1';
        
        $newFileName = './'.$table.".php";
        $newFileContent = '<?php ?>';
        echo '<a href='.$table.'.php'.'>'.$table.'</a><br>';


        
#echo '<pre>',$table,'</pre>';
    }
    echo '<br>';




?>

<a href="insert_Student.php">Insert into a Student Table</a><br><br>
<a href="insert_Activity.php">Insert into an Activity Table</a><br><br>
<a href="insert_students_activity.php">Insert a student's activity</a><br><br>
<a href="who_does_what.php">Who does what?</a><br><br><br><br><br><br>

//below are not finished!!<br>
<a href="insert_Located.php">Insert into a Located</a><br><br>
<a href="insert_Location.php">Insert into a Activity</a><br><br>
<a href="drop_table.php">Drop a table</a><br><br>
</body>

</html>