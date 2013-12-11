<?php
    session_start();
    require_once 'mysql/login.php';

    if (isset($_COOKIE["user"]))
        $email = $_COOKIE["user"];
    else
        header ("Location: index.php");

    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
    $activity = $_GET['activity'];
    $actArray = mysqli_query($connection, "SELECT idActivity FROM Activity WHERE activityName='$activity'");
    $activityId = -1;
    while ($row = mysqli_fetch_array($actArray)) {
    	$activityId = $row['idActivity'];
    }
	$_SESSION['activityId'] = $activityId;

    $email = $_SESSION['email'];
    $emailArray = mysqli_query($connection, "SELECT * FROM Student WHERE email='$email'");
    $eventName = "";
    $location = "";
    $description = "";

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

    $array = mysqli_query($connection, "SELECT * FROM Does WHERE idActivity=$activityId AND email='$email'");
    $count = 0;
    while ($row = mysqli_fetch_array($array)) {
    	$count++;
    }
    if ($count > 0) {
    	$subscribed = 1;
    } else {
    	$subscribed = 0;
    }
?>

<!DOCTYPE html>
<html lang="en-US">
    <head>
		<link rel="shortcut icon" href="images/favicon.ico">
        <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
        <meta charset="utf-8">
        <style>
        	.form-control { margin-bottom: 8px; }
			.tableRow { cursor: pointer; cursor: hand; }
			img { margin-right: 6px; }
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
                <div class="col-md-6" style="width: 30%;">
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
                                    echo '<td>';
									$allowedExtensions = ["JPG", "png", "gif","tif"];
									foreach($allowedExtensions as $extension)
									{
									  $flag = 0;
												$path = "uploads/".$row['email'].".".$extension;

									  if(file_exists($path))
									  {
										  echo '<img src="'.$path.'" alt="Profile Picture" height="25" width="25" class="img-rounded">';
										  $flag = 1;
										  break;
									  }
									}
									if($flag == 0)
										echo '<img src="uploads/default.png" alt="Profile Picture" height="25" width="25" class="image-thumbnail">';
									echo $row['name'].'</td>';
									echo '<td class="targetEmail" hidden>'.$row['email'].'</td></tr>';
                                }

                            ?>
                        <tbody>
                    </table>
					<button id="un" class="btn btn-danger">Unsubscribe</button>
					<button id="sub" class="btn btn-success">Subscribe</button>
                </div>
                <div class="col-md-6" style="width: 70%;">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th style="width: 15%;"><?php echo $activity; ?> events</th>
                                <th style="width: 13%;">Creator</th>
                                <th style="width: 10%;">Date</th>
                                <th style="width: 8%;">Time</th>
                                <th style="width: 20%;">Location</th>
                                <th style="width: 20%;">Description</th>
                            </tr>
                        </thead>
                        <tbody>
                        	<?php
                        		$events = mysqli_query($connection, "SELECT * FROM Event WHERE activity='$activity'");
                        		while ($row = mysqli_fetch_array($events)) {
                        			echo '<tr class="tableRow">';
                        			echo '<td>'.$row['name'].'</td>';
                        			$creatorEmail = $row['creator'];
                        			$creatorName = mysqli_query($connection, "SELECT name FROM Student WHERE email='$creatorEmail'");
                        			$creatorName = $creatorName -> fetch_array();
                        			echo '<td>'.$creatorName[0].'</td>';
                        			$ts = date_create($row['ts']);
				                    $date = $ts->format('F j, Y');
				                    echo "<td>".$date."</td>";
				                    $time = $ts->format('g:i a');
                    				echo '<td>'.$time.'</td>';
                    				echo '<td>'.$row['location'].'</td>';
                    				echo '<td>'.$row['description'].'</td>';
                    				echo '<td class="idEvent" hidden>'.$row['idEvent'].'</td>';
                    				echo '</tr>';
                        		}
                        	?>
                        <tbody>
                    </table>
                	<button id="createEventButton" class="btn" data-toggle="modal" data-target="#createAct" id="showModal">Create an event</button>
                </div>
            </div>
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
                                                    echo '<td><select id="yearSel" class=form-control name=year>';
                                                    $year = 0;
                                                    for ($i= 2013; $i < 2018; $i++)
                                                    {
                                                        echo "<option value=".$i.">".$i."</option>";
                                                    }
                                                    echo "</select></td>";
                                                    echo '<td><select id="monthSel" class=form-control name=month>';
                                                    $month = 0;
                                                    for ($i= 1; $i <= 12; $i++)
                                                    {
                                                        echo "<option value=".$i.">".$months[$i]."</option>";
                                                    }
                                                    echo "</select></td>";
                                                    echo '<td><select id="daySel" class=form-control name=day>';
                                                    for ($i= 1; $i <= 31; $i++)
                                                    {
                                                        echo "<option value=".$i.">".$i."</option>";
                                                    }
                                                    echo "</select></td></tr></table>";
                                                    echo "</td></tr><tr><td>";
                                                    echo "<table><tr>";
                                                    echo "<td><select class=form-control name=hour>";
                                                    $hour = 0;
                                                    for ($i= 1; $i <= 12; $i++)
                                                    {
                                                        echo "<option value=".$i.">".$i;
                                                    }
                                                    echo "</select></td><td>:</td>";
                                                    echo "<td><select class=form-control name=minute>";
													$minute = 0;
                                                    for ($i= 0; $i < 60; $i++)
                                                    {
                                                        if ($i == 0)
                                                        {
                                                            echo "<option value=00>00";
                                                        }
                                                        else if ($i == 5)
                                                        {
                                                            echo "<option value=05>05";
                                                        }
                                                        else if ($i%5==0)
                                                        {
                                                            echo "<option value=".$i.">".$i;
                                                        }
                                                    }

                                                    echo "</select></td><td></td>";
                                                    echo "<td><select class=form-control name=meridiem>";
                                                    $meridiem = "";
                                                    echo "<option value=am>am</option>";
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
                                                                echo ' <input type=radio name=city value=Champaign style="margin-left: 10px;"> Champaign ';
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
					window.location = 'events.php#event' + $(this).children('.idEvent').text();
				});
			});
			$('#un').hide();
			$('#sub').hide();
			$('#createEventButton').hide();
			<?php
				if ($subscribed) {
					echo "$('#un').show();";
					echo "$('#createEventButton').show();";
				} else {
					echo "$('#sub').show();";
				}
			?>

			// hide and show activities
            $('div.errorName').hide();
            $('div.errorLocation').hide();
            $('div.errorCity').hide();


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

			$('#sub').click(function() {
				$.get('subscribe.php');
				location.reload();
			});

			$('#un').click(function() {
				$.get('unsubscribe.php');
				location.reload();
			});

			$("button#modalClose").click(function() {
				closeModal();
			});
			$(".close").click(function() {
				closeModal();
			});

			$('#monthSel').on('change', function() {
				var i;
				$('#daySel').empty();
				if ($(this).val() == 1 || $(this).val() == 3 || $(this).val() == 5 ||
					$(this).val() == 7 || $(this).val() == 8 || $(this).val() == 10 || $(this).val() == 12) {
					for (i = 1; i <= 31; i++) {
						$('#daySel').append('<option value=' + i + '>' + i +'</option>');
					}
				} else if ($(this).val() == 4 || $(this).val() == 6 ||
							 $(this).val() == 9 || $(this).val() == 11) {
					for (i = 1; i <= 30; i++) {
						$('#daySel').append('<option value=' + i + '>' + i +'</option>');
					}
				} else if ($(this).val() == 2) {
					if ($('#yearSel').val() % 4 == 0) {
						for (i = 1; i <= 29; i++) {
							$('#daySel').append('<option value=' + i + '>' + i +'</option>');
						}
					}else{
						for (i = 1; i <= 28; i++) {
							$('#daySel').append('<option value=' + i + '>' + i +'</option>');
						}
					}
				}
			});
        });
    </script>

</html>
