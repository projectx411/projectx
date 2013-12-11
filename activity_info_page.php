<?php
    session_start();
    require_once 'mysql/login.php';

    if (isset($_COOKIE["user"]))
        $email = $_COOKIE["user"];
    else
        header ("Location: index.php");

    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
    $activity = $_GET['activity'];
    
    $email = $_SESSION['email'];
    $emailArray = mysqli_query($connection, "SELECT * FROM Student WHERE email='$email'");

    $name = '';
    $gender = '';
    $phoneNumber = '';
    $password = '';
    while ($row = mysqli_fetch_array($emailArray)) {
        $name = $row['name'];
        $gender = $row['gender'];
        $phoneNumber = $row['phoneNumber'];
        $password = $row['password'];
    }

    $activity = $_GET['activity'];

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
        <meta charset="utf-8">
        <style>
			.tableRow { cursor: pointer; cursor: hand; }
        </style>
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<script src="https://code.jquery.com/jquery.js"></script>
		<script src="js/bootstrap.js"></script>
        <?php echo '<title>'.$activity.'</title>'; ?>
    </head>
    <body>
        <div class="container">
            <?php echo '<h1>About '.$activity.'</h1>' ?>
            <div id="navbar"></div>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>People interested in <?php echo $activity; ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $names = mysqli_query($connection, "SELECT Student.* FROM Student inner join Does on Student.email = Does.email inner join Activity on Does.idActivity = Activity.idActivity where activityName = '$activity'") or die(mysql_error());
                                // store the record of the "tblstudent" table into $row
                                while($row = mysqli_fetch_array($names)){
                                    // Print out the contents of the entry
                                    echo '<tr class="tableRow">';
                                    echo '<td>'.$row['name'].'</td>';
									echo '<td class="targetEmail" hidden>'.$row['email'].'</td></tr>';
                                }

                            ?>
                        <tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><?php echo $activity; ?> event</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php
                        		$events = mysqli_query($connection, "SELECT * FROM Event WHERE activity='$activity'");
                        		while ($row = mysqli_fetch_array($events)) {
                        			echo '<tr class="tableRow">';
                        			echo '<td>'.$row['name'].'</td>';
                        			$ts = date_create($row['ts']);
				                    $date = $ts->format('F j, Y');
				                    echo "<td>".$date."</td>";
				                    $time = $ts->format('g:i a');
                    				echo '<td>'.$time.'</td>';
                    				echo '<td>'.$row['location'].'</td>';
                    				echo '<td>'.$row['description'].'</td>';
                        		}
                        	?>
                        <tbody>
                    </table>
                </div>
            </div>
             <form id="addActivityForm" method='post' action="activity_info_page.php?activity=<?php echo $_GET['activity']?>">
                    <div id="formDiv">
                        <div id="searchDiv">
                            <input id="searchBox" type="hidden" class="activity" name="toadd" value ="<?php echo $_GET['activity']?>"  />
                            <button id="searchButton" type="submit" class="btn btn-default">Add to your activities</button>
                        </div>
                    </div>
                </form>
    		<button class="btn" data-toggle="modal" data-target="#createAct" id="showModal">Create an event</button>
    		<!-- Modal -->
			<!-- Modal -->
            <div class="modal fade" id="createAct" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Create an event</h4>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                            
                                <form method="post" class="create" action="create_event.php">
                                    <table>
                                        <tr>
                                            <td>
                                                <input class="form-control" value="<?php echo htmlspecialchars($eventName);?>" type="text" id="eventName" name="eventName" placeholder="Event Name" autofocus>
                                                <div class="errorName">
                                                    <span style="color:red">Provide a name!</span>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?php
                                                    $acts = mysqli_query($connection, "SELECT activityName FROM Does INNER JOIN Activity ON Does.idActivity=Activity.idActivity INNER JOIN Student ON Does.email=Student.email WHERE Student.email='$email';") or die(mysql_error());
                                                    echo "<select class=form-control name=activity>";
                                                    while($row = mysqli_fetch_array($acts)){
                                                        if ($row['activityName'] == $activity)
                                                            echo "<option selected=selected value=\"".$row['activityName']."\">".$row['activityName']."</option>";
                                                        else
                                                            echo "<option value=\"".$row['activityName']."\">".$row['activityName']."</option>";
                                                    }
                                                    echo "</select>";
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>

                                                <?php
                                                    $months = array(
                                                            1=>"January",2=>"February",3=>"March",4=>"April",5=>"May",6=>"June",7=>"July",8=>"August",9=>"September",10=>"October",11=>"November",12=>"December"
                                                        );
                                                    echo "<table><tr>";
                                                    echo "<td><select class=form-control name=year>";
                                                    for ($i= 2013; $i < 2015; $i++)
                                                    {
                                                        if ($i == $year)
                                                            echo "<option selected=selected value=".$i.">".$i."</option>";
                                                        else
                                                            echo "<option value=".$i.">".$i."</option>";
                                                    }
                                                    echo "</select></td>";
                                                    echo "<td><select class=form-control name=month>";
                                                    for ($i= 1; $i < 12; $i++)
                                                    {
                                                        if ($i == $month)
                                                            echo "<option selected=selected value=".$i.">".$months[$i]."</option>";
                                                        else
                                                            echo "<option value=".$i.">".$months[$i]."</option>";
                                                    }
                                                    echo "</select></td>";
                                                    echo "<td><select class=form-control name=day>";
                                                    for ($i= 1; $i < 30; $i++)
                                                    {
                                                        if ($i == $day)
                                                            echo "<option selected=selected value=".$i.">".$i."</option>";
                                                        else
                                                            echo "<option value=".$i.">".$i."</option>";
                                                    }
                                                    echo "</select></td></tr></table>";
                                                    echo "</td></tr><tr><td>";
                                                    echo "<table><tr>";
                                                    echo "<td><select class=form-control name=hour>";
                                                    for ($i= 1; $i <= 12; $i++)
                                                    {
                                                        if ($i == $hour)
                                                            echo "<option selected=selected value=".$i.">".$i;
                                                        else
                                                            echo "<option value=".$i.">".$i;
                                                    }
                                                    echo "</select></td><td>:</td>";
                                                    echo "<td><select class=form-control name=minute>";

                                                    for ($i= 0; $i < 60; $i++)
                                                    {
                                                        if ($i == 0)
                                                        {
                                                            if ($i == $minute)
                                                                echo "<option selected=selected value=00>00";
                                                            else
                                                                echo "<option value=00>00";
                                                        }
                                                        else if ($i == 5)
                                                        {
                                                            if ($i == $minute)
                                                                echo "<option selected=selected value=05>05";
                                                            else
                                                                echo "<option value=05>05";
                                                        }
                                                        else if ($i%5==0)
                                                        {
                                                            if ($i == $minute)
                                                                echo "<option selected=selected value=".$i.">".$i;
                                                            else
                                                                echo "<option value=".$i.">".$i;
                                                        }
                                                    }
                                                    
                                                    echo "</select></td><td></td>";
                                                    echo "<td><select class=form-control name=meridiem>";
                                                    if ($meridiem == "am")
                                                        echo "<option selected=selected value=am>am</option>";
                                                    else
                                                        echo "<option value=am>am</option>";
                                                    if ($meridiem == "pm")
                                                        echo "<option selected=selected value=pm>pm</option>";
                                                    else
                                                        echo "<option value=pm>pm</option>";
                                                    echo "</select></td></tr></table>";
                                                ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <table>
                                                    <tr>
                                                        <td>
                                                            <input class="form-control" value="<?php echo htmlspecialchars($location);?>" type="text" name="location" placeholder="Street Address" autofocus>
                                                            <div class="errorLocation">
                                                                <span style="color:red">Provide a location!</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                echo " <input type=radio name=city value=Champaign> Champaign ";
                                                                echo " <input type=radio name=city value=Urbana> Urbana ";
                                                            ?>
                                                            <div class="errorCity">
                                                                <span style="color:red">Select a city!</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input class="form-control" value="<?php echo htmlspecialchars($description);?>" type="textarea" name="description" placeholder="Description" autofocus>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div> <!-- /container -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal" id="modalClose">Close</button>
                            <button type="button" class="btn btn-primary" id="create">Create an event</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
        </div>
    </body>

    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery.js"></script>
    <script>
    	function closeModal() {
        		$('#myModal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
        	}
    	
        $(function() {
            $('#navbar').load('navbar.php', function(){
                $('#tabs li').each(function() {
                    $(this).removeClass('active');
                });
                $('#activityTab').addClass('active');
            });

			$('.tableRow').each(function() {
				$(this).on('click', function() {
					var e = $(this).children('.targetEmail').text();
					window.location = 'student.php?email='+e;
				});
			});
			
			// hide and show activities
            $('div.errorName').hide();
            $('div.errorLocation').hide();
            $('div.errorCity').hide();
        });
        $("button#create").click(function() {
                var error = "";
                var eventName = document.getElementsByName("eventName")[0].value;
                var street = document.getElementsByName("location")[0].value;
                var city;
                if (document.getElementsByName("city")[0].checked)
                    city = "Champaign"
                else if (document.getElementsByName("city")[1].checked)
                    city = "Urbana";
                else
                    city = "";
                if (!eventName)
                    $(".errorName").show().delay(2000).fadeOut(1000);
                if (!street)
                    $(".errorLocation").show().delay(2000).fadeOut(1000);
                if (!city)
                    $(".errorCity").show().delay(2000).fadeOut(1000);
                if (eventName && city && street)
                {
                    $.ajax({
                        type: "POST",
                        url: "create_event.php",
                        data: $('form.create').serialize(),
                        success: function(msg) {
                            window.location = 'events.php';
                        },
                        error: function() {
                            //alert("error");
                        }
                    });
                }
                
                if (eventName && street && city) {
                	closeModal();
                }
			});
			
			$("button#modalClose").click(function() {
				closeModal();
			});
			$(".close").click(function() {
				closeModal();
			});
    </script>

</html>
