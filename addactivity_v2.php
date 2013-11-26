<?php
    session_start();
    require_once 'mysql/login.php';

    if (isset($_COOKIE["user"]))
        $email = $_COOKIE["user"];
    else
        header ("Location: index.php");

    $message = "";
    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
    $emailArray = mysqli_query($connection, "SELECT name FROM Student WHERE email='$email'");

    if(isset($_POST['toadd']))
    {
        $message = "";
        $acti = $_POST['toadd'];

        // if the activity is not in the Activity database, add it with a categoryName="other"
        $checkIfActivityExists = mysqli_query($connection, "select idActivity from Activity where activityName='$acti' limit 1");
        $checkIfActivityExists = $checkIfActivityExists -> fetch_array();
        if ($checkIfActivityExists[0] == "")
        {
            $insert = mysqli_query($connection,"INSERT INTO Activity (activityName, categoryName) values ('$acti','other')");
            $message .= $acti. " was successfully added to the list of activities under 'other'.</br>";
        }
        else
        {
            // do nothing
        }
            
        echo "</br>";
            
        // if the activity isn't assigned to $email, insert into Does
        $idActivity = mysqli_query($connection, "SELECT idActivity FROM Activity WHERE activityName='$acti'");
        $idActivity = $idActivity -> fetch_array();
        $checkIfActivityAssigned= mysqli_query($connection, "select idDoes from Does where email='$email' and idActivity='$idActivity[0]' limit 1");
        $checkIfActivityAssigned = $checkIfActivityAssigned -> fetch_array();
        if ($checkIfActivityAssigned[0] == "")
        {
            $insert = mysqli_query($connection,"INSERT INTO Does (email, idActivity) values ('$email','$idActivity[0]')");
            $message .= "<div class=error style=color:#15B318>".$acti." was successfully added to your profile.</div>";
        }
        else
        {
            $message .= "<div class=error style=color:#B50000>".$acti." is already in your activities!</div>";
        }
    }
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <meta charset="utf-8">
        <title>Project X: Add Activity</title>
        <style>
        	#searchButton { margin-top: 3px; padding: 3px 6px 3px 6px; }
            div.categoryList
            {
                background-color:#FFFFFF;
                border-radius:5px;
                font-size: 18px;
            }
            thead
            {
                font-size: 16px;
            }
        </style>
    </head>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/typeahead.js"></script>
    <body>
        <div class="container">
    		<h1>Activities</h1>
    		<div id="navbar"></div>
            <?php
                // get all categories
                $activityList= array();
                $query = "SELECT distinct activityName FROM Activity";
                $result = mysqli_query($connection, $query);
                while($row = mysqli_fetch_array($result))
                {
                    $activityList[] = $row[0];
                }

                // get all activities for each category
                $categoryList= array();
                $cnt = 0;
                $query = "SELECT distinct categoryName FROM Activity";
                $result = mysqli_query($connection, $query);
                while($row = mysqli_fetch_array($result)){
                    $categoryList[] = $row[0];
                }
            ?>
            <table table-layout=fixed width= 100% class="table table-striped">
                <thead>
                    <tr><th>Categories</th><th>Activities</th></tr>
                </thead>
                <tbody>
                    <?php
                        $q = "SELECT COUNT(DISTINCT categoryName) FROM Activity";
                        $r = mysqli_query($connection, $q);
                        $numberOfCategories = mysqli_fetch_array($r);
                        while ($cnt < $numberOfCategories[0])
                        {
                            echo "<tr><td width=50%><b><p class=activityCategory id=".$categoryList[$cnt].">".$categoryList[$cnt]."</p></b></td>";
                            // echo all activities under multiple divs, each corresponding to a category
                            if ($cnt == 0)
                            {
                                echo "<td width=50% rowspan=".$numberOfCategories[0].">";
                                $cnt2=0;
                                while ($cnt2 < $numberOfCategories[0])
                                {
                                    $query = "SELECT count(categoryName) FROM Activity where categoryName='".$categoryList[$cnt2]."'";
                                    $r2 = mysqli_query($connection, $query);
                                    $cnt3 = mysqli_fetch_array($r2);
                                    $cnt3[0]--;
                                    $query = "SELECT activityName FROM Activity where categoryName='".$categoryList[$cnt2]."'";
                                    $r3 = mysqli_query($connection, $query);
                                    echo "<div id=".$categoryList[$cnt2]."list class=categoryList>";
                                    $cnt4 = 0;
                                    while($row = mysqli_fetch_array($r3))
                                    {
                                        echo "<a href=activity_info_page.php?activity=".$row[0].">".$row[0]."</a>";
                                        if ($cnt4 < $cnt3[0])
                                            echo ", ";
                                        $cnt4++;
                                    }
                                    echo "</div>";
                                    $cnt2++;
                                }
                                echo "</td>";
                            }
                            echo "</tr>";
                            $cnt++;
                        }
                    ?>
                </tbody>
            </table>
            <div class="row">
                <div class="col-md-3">
                    <h3>Search</h3>
                    <form method='post' action='addactivity.php'>
                        <div id="formDiv">
                            <input type="text" class="activity" name="toadd" placeholder="Start typing an activity" />
                            <button id="searchButton" type="submit" class="btn btn-default">Add</button>
                            <?php echo $message; ?>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <h3>Your Activities</h3>
                    <ul class="undefined">
                        <?php
                            $acts = mysqli_query($connection, "SELECT activityName FROM Does INNER JOIN Activity ON Does.idActivity=Activity.idActivity INNER JOIN Student ON Does.email=Student.email WHERE Student.email='$email';") or die(mysql_error());
                            while($row = mysqli_fetch_array($acts)){
                                echo "<li><a href=activity_info_page.php?activity=".$row['activityName'].">".$row['activityName'].'</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </div><!-- /container -->
        <script type="text/javascript">
            // hide and show activities
            $('div.categoryList').hide();
            $('p.activityCategory').bind('mouseover', function() {
                $('div.categoryList').hide();
                $('#'+$(this).attr('id')+'list').show();
            }); 

            // fade out arror after 2s
            $(".error").delay(2000).fadeOut(1000); 

            // navbar and search box
            $(document).ready(function() {
            	$('#navbar').load('navbar.php', function(){
            		$('#tabs li').each(function() {
            			$(this).removeClass('active');
            		});
            		$('#activityTab').addClass('active');
            	});
                $('input.activity').typeahead({
                    name: 'accounts',
                    local:<?php echo json_encode($activityList)?>
                });
            })
        </script>
    </body>
</html>
