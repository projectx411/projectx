<?php
    require_once 'login.php';
    /* connect to the db */
    $connection = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
    
    echo '<br><br>Current Activity Table:<br>';
    
    $result = mysql_query('Select * From Activity',$connection) or die('cannot show tables');
    echo '<table border="1">';
    echo '<tr><td>idActivity</b></td><td><b>activityName</b></td><td><b>categoryName</b></td></tr>';
    while($tableName = mysql_fetch_row($result)) {
        
        
        echo '<tr><td>'.$tableName[0].'</td>&nbsp;<td>'.$tableName[1].'</td>&nbsp;<td>'.$tableName[2].'</td><br></tr>';
        
    }
    
    echo '</table>';
    echo '<a href="index.php">Home</a><br><br>';
    
?>