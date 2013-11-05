<?php
    
    require_once 'login.php';
    /* connect to the db */
    $connection = mysql_connect($db_hostname,$db_username,$db_password);
    mysql_select_db($db_database,$connection);
    
    echo '<br>Who plays what?<br>';
    
    $result = mysql_query('Select name, activityName From Student inner join Does on Student.email = Does.email inner join Activity on Does.idActivity = Activity.idActivity',$connection);
    #echo mysql_errno($connection) . ": " . mysql_error($connection) . "\n";
    echo '<table border="1">';
    echo '<tr><td><b>name</b></td><td><b>activity</b></td></tr>';
    while($tableName = mysql_fetch_row($result)) {
        
        
        echo '<tr><td>'.$tableName[0].'</td>&nbsp;<td>'.$tableName[1].'</td></tr>';
        
    }
    
    echo '</table>';
    echo '<a href="index.php">Home</a><br><br>';
    
    ?>