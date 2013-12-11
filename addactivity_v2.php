<?php
    session_start();
    require_once 'mysql/login.php';

    if (isset($_COOKIE["user"]))
        $email = $_COOKIE["user"];
    else
        header ("Location: index.php");

    $message = '';
    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
    $emailArray = mysqli_query($connection, "SELECT name FROM Student WHERE email='$email'");

    if(isset($_POST['toadd']))
    {
        $message = '';
        $acti = $_POST['toadd'];

        // if the activity is not in the Activity database, add it with a categoryName="other"
        $checkIfActivityExists = mysqli_query($connection, "select idActivity from Activity where activityName='$acti' limit 1");
        $checkIfActivityExists = $checkIfActivityExists -> fetch_array();
        if ($checkIfActivityExists[0] == "")
        {
            $insert = mysqli_query($connection,"INSERT INTO Activity (activityName, categoryName) values ('$acti','other')");
            //$message .= $acti. " was successfully added to the list of activities under 'other'.</br>";
        }
        else
        {
            // do nothing
        }

        echo '</br>';

        // if the activity isn't assigned to $email, insert into Does
        $idActivity = mysqli_query($connection, "SELECT idActivity FROM Activity WHERE activityName='$acti'");
        $idActivity = $idActivity -> fetch_array();
        $checkIfActivityAssigned= mysqli_query($connection, "select idDoes from Does where email='$email' and idActivity='$idActivity[0]' limit 1");
        $checkIfActivityAssigned = $checkIfActivityAssigned -> fetch_array();
        if ($checkIfActivityAssigned[0] == "")
        {
            $insert = mysqli_query($connection,"INSERT INTO Does (email, idActivity) values ('$email','$idActivity[0]')");
            $cat = mysqli_query($connection, "SELECT categoryName FROM Activity WHERE idActivity='$idActivity[0]' limit 1");
            $cat = $cat -> fetch_array();
            $message .= '<div class="error" style="color:#15B318">'.$acti.' was successfully added to your profile under '.$cat[0].'.</div>';
        }
        else
        {
            $message .= '<div class="error" style="color:#B50000">'.$acti.' is already in your activities!</div>';
        }
    }
?>
<!DOCTYPE html>
<html lang="en-US">
    <head>
		<link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <link rel="stylesheet" type="text/css"href="css/typeahead.js-bootstrap.css" >

        <meta charset="utf-8">
        <title>Activities</title>
        <style>
        	#searchDiv { margin-left: 24px; }
        	#searchBox { width: 450px; }
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
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript" src="js/jquery.tablesorter.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/typeahead.js"></script>
    <body>
        <div class="container">
    		<h1>Activities</h1>
    		<div id="navbar"></div>
			<?php echo $message;?>
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
            <h3>Find an Activity</h3>
            <table table-layout=fixed width= 100% class="table table-striped table-hover">
                <thead>
                    <tr><th style="width: 150px;">Categories</th><th>Activities</th></tr>
                </thead>
                    <?php
                        $q = "SELECT COUNT(DISTINCT categoryName) FROM Activity";
                        $r = mysqli_query($connection, $q);
                        $numberOfCategories = mysqli_fetch_array($r);
                        while ($cnt < $numberOfCategories[0])
                        {
                            echo '<tr><td><b><p class="activityCategory" id="'.$categoryList[$cnt].'">'.$categoryList[$cnt].'</p></b></td>';
                            // echo all activities under multiple divs, each corresponding to a category
                            if ($cnt == 0)
                            {
                                echo '<td rowspan="'.$numberOfCategories[0].'">';
                                $cnt2=0;
                                while ($cnt2 < $numberOfCategories[0])
                                {
                                    $query = "SELECT count(categoryName) FROM Activity where categoryName='".$categoryList[$cnt2]."'";
                                    $r2 = mysqli_query($connection, $query);
                                    $cnt3 = mysqli_fetch_array($r2);
                                    $cnt3[0]--;
                                    $query = "SELECT activityName FROM Activity where categoryName='".$categoryList[$cnt2]."'";
                                    $r3 = mysqli_query($connection, $query);
                                    echo '<div id="'.$categoryList[$cnt2].'list" class="categoryList">';
                                    $cnt4 = 0;
                                    while($row = mysqli_fetch_array($r3))
                                    {
                                        echo '<a href="activity_info_page.php?activity='.$row[0].'">'.$row[0].'</a>';
                                        if ($cnt4 < $cnt3[0])
                                            echo ", ";
                                        $cnt4++;
                                    }
                                    echo '</div>';
                                    $cnt2++;
                                }
                                echo '</td>';
                            }
                            echo '</tr>';
                            $cnt++;
                        }
                    ?>
            </table>
				<h3>Add an Activity</h3>
				<form id="addActivityForm" method='post' action='addactivity_v2.php'>
					<div id="formDiv">
						<div id="searchDiv">
							<input id="searchBox" type="text" class="activity" name="toadd" placeholder="Start typing to search for an activity, or type in a non-existing one." />
							<button id="searchButton" type="submit" class="btn btn-default">Add to your activities</button>
						</div>
					</div>
				</form>
				<h3>Your Activities</h3>
				<ul class="undefined">
					<?php
						$acts = mysqli_query($connection, "SELECT activityName FROM Does INNER JOIN Activity ON Does.idActivity=Activity.idActivity INNER JOIN Student ON Does.email=Student.email WHERE Student.email='$email';") or die(mysql_error());
						while($row = mysqli_fetch_array($acts)){
							echo '<li><a href="activity_info_page.php?activity='.$row['activityName'].'">'.$row['activityName'].'</a></li>';
						}
					?>
				</ul>

				<h3>Suggested Activities</h3>
				<table table-layout="fixed" width= "100%" class="table table-striped" id="rec" class="tablesorter">
					<thead>
						<tr><th style="width: 150px;">Activity</th><th style="width: 150px;">Recommendation Strength</th></tr>
					</thead>
				<?php

					$suggestArray = mysqli_query($connection, "SELECT COUNT(idActivity) AS ct, idActivity AS id2 FROM Does AS D3 INNER JOIN (SELECT DISTINCT email AS em FROM Does AS D2 INNER JOIN (SELECT idActivity AS id1 FROM Does AS D1 INNER JOIN Student AS S1 ON D1.email=S1.email WHERE D1.email='$email') AS T1 ON D2.idActivity=id1 WHERE email<>'$email') AS T2 ON D3.email=em GROUP BY idActivity") or die(mysqli_error);

					while ($row = mysqli_fetch_array($suggestArray)) {
						$acts = mysqli_query($connection, "SELECT Does.idActivity FROM Does INNER JOIN Activity ON Does.idActivity=Activity.idActivity INNER JOIN Student ON Does.email=Student.email WHERE Student.email='$email';") or die(mysql_error());
						$inActs = FALSE;
						$finalArray = array();
						while ($row1 = mysqli_fetch_array($acts)) {
							if ($row1['idActivity'] == $row['id2'])  {
								$inActs = TRUE;
							}
						}

						if ($inActs == FALSE) {
							$qry = "SELECT activityName FROM Activity WHERE idActivity=".$row['id2'];
							$qry = mysqli_query($connection, $qry);
							while ($row2 = mysqli_fetch_array($qry)) {
								$str = '<tr><td>'.$row2[0].'</td><td>'.$row['ct'].'</td></tr>';
								$finalArray[$str] = $row['id2'];
							}
						}

						//print_r($finalArray);
						foreach ($finalArray as $key=>$value) {
							echo $key;
						}
					}
				?>
				</table>
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
