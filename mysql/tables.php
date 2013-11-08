<?php
    
    /* connect to the db */
    $connection = mysqli_connect('engr-cpanel-mysql.engr.illinois.edu','truszko1_main','dsg82bvj');
    
    /* show tables */
    $result = mysqli_query($connection,'SHOW TABLES') or die('cannot show tables');
    while($tableName = mysql_fetch_row($result)) {
        
        $table = $tableName[0];
        
        echo '<pre><h3>',$table,'</h3></pre>';
        $result2 = mysql_query($connection,'SHOW COLUMNS FROM '.$table) or die('cannot show columns from '.$table);
        if(mysql_num_rows($result2)) {
            echo '<pre><table cellpadding="0" cellspacing="0" class="db-table"></pre>';
            echo '<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default<th>Extra</th></tr>';
            while($row2 = mysql_fetch_row($result2)) {
                echo '<tr>';
                foreach($row2 as $key=>$value) {
                    echo '<td><pre>  ',$value,'  </pre></td>';
                }
                echo '</tr>';
            }
            echo '</table><br />';
        }
    }
    
?>