<?php
    require_once 'login.php';
    /* connect to the db */
    $connection = mysqli_connect($db_hostname,$db_username,$db_password,$db_database);
    
    echo '<br><br>Current Does Table:<br>';
    
    $result = mysql_query('Select * From Does',$connection) or die('cannot show tables');
    echo '<table border="1">';
    echo '<tr><td><b>idDoes</b></td><td><b>email</b></td><td><b>idActivity</b></td>';
    while($tableName = mysql_fetch_row($result)) {
        
        
        echo '<tr><td>'.$tableName[0].'</td>&nbsp;<td>'.$tableName[1].'</td>&nbsp;<td>'.$tableName[2].'</td>';
        
    }
    
    echo '</table>';
    echo '<a href="index.php">Home</a><br><br>';
    
    ?>