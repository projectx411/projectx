<?php
    
    require_once 'login.php';
    /* connect to the db */
    $connection = mysql_connect($db_hostname,$db_username,$db_password);
    mysql_select_db($db_database,$connection);
    
    echo '<br><br>Current Student Table:<br>';
    
    $result = mysql_query('Select * From Student',$connection) or die('cannot show tables');
    echo '<table border="1">';
    echo '<tr><td><b>email</b></td><td><b>password</b></td><td><b>name</b></td><td><b>gender</b></td><td><b>phoneNumber</b></td></tr>';
    while($tableName = mysql_fetch_row($result)) {
        
        
        echo '<tr><td>'.$tableName[0].'</td>&nbsp;<td>'.$tableName[1].'</td>&nbsp;<td>'.$tableName[2].'</td>&nbsp;<td>'.$tableName[3].'</td>&nbsp;<td>'.$tableName[4].'</td>&nbsp;<br></tr>';
        
    }
    
    echo '</table>';
    echo '<a href="index.php">Home</a><br><br>';
    
    ?>