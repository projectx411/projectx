<?php
    session_start();
    require_once 'mysql/login.php';

    if (isset($_COOKIE["user"]))
        $email = $_COOKIE["user"];
    else
        header ("Location: index.php");

	$activity = "";
	$eventName = "";
	$location = "";
	$description = "";
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

        <meta charset="utf-8">
        <title>Events</title>
        <style>
        	.form-control { margin-bottom: 8px; }
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
    <body>
        <div class="container">
            <h1>Events</h1>
            <div id="navbar"></div>

            <!-- Button trigger modal -->
            <button class="btn" data-toggle="modal" data-target="#myModal">
                Create an event
            </button>
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
            </br>
            </br>
            <table table-layout=fixed width= 100% class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th style="width: 15%;">Event name</th>
                        <th style="width: 10%;">Activity</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 10%;">Time</th>
                        <th style="width: 20%;">Location</th>
                        <th style="width: 30%;">Description/Notes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $sql = "select * from Event";
                        $r = mysqli_query($connection, $sql);
                        while ($row = mysqli_fetch_array($r))
                        {
                            date_default_timezone_set('America/Chicago');
                            echo '<tr>';
                            echo "<td>".$row[1]."</td>";
                            echo "<td>".$row[3]."</td>";
                            $ts = date_create($row[5]);
                            $date = $ts->format('F j, Y');
                            echo "<td>".$date."</td>";
                            $time = $ts->format('g:i a');
                            echo "<td>".$time."</td>";
                            echo '<td><a class="eventLocation" href="#">'.$row[4].', IL</a></td>';
                            echo "<td>".$row[2]."</td>";
                        }
                    ?>
                </tbody>
            </table>

			<div id="map_canvas" style="align: center; width: 500px; height: 400px; margin-bottom: 50px;"></div>
        </div><!-- /container -->
    </body>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/bootstrap.js"></script>
		<script type="text/javascript" src="js/typeahead.js"></script>
	    <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
        <script type="text/javascript">

            $(document).ready(function() {
                $('#navbar').load('navbar.php', function(){
                    $('#tabs li').each(function() {
                        $(this).removeClass('active');
                    });
                    $('#eventTab').addClass('active');
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

				// hide and show activities
				$('div.errorName').hide();
				$('div.errorLocation').hide();
				$('div.errorCity').hide();

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

				$('.eventLocation').each(function() {
					$(this).on('click', function() {
						$('html, body').animate({ scrollTop: $(document).height() }, 470);
						//google.maps.visualRefresh = true;
						google.maps.event.addDomListener(window, 'load', initializeGoogleMaps($(this).text()));
					});
				});

            });

			var map;
			var geo;
			var mapOptions;
			var markerList = new Array();
			// Does not work yet in 3.12 Visual Refresh
			var mapTypes = new Array(	google.maps.MapTypeId.HYBRID,
										google.maps.MapTypeId.ROADMAP,
										google.maps.MapTypeId.SATELLITE,
										google.maps.MapTypeId.TERRAIN);

            function initializeGoogleMaps(address) {

				geo = new google.maps.Geocoder();
				geo.geocode({'address': address}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						mapOptions = {
							zoom: 17,
							center: results[0].geometry.location,
							disableDoubleClickZoom: true,
							mapTypeControl: true,

							// Does not work yet in 3.12 Visual Refresh
							mapTypeControlOptions: {
								mapTypeIds: mapTypes,
								style: google.maps.MapTypeControlStyle.DEFAULT
							},

							scaleControl: true,

							// Does not work yet in 3.12 Visual Refresh
							scaleControlOptions: {
								position: google.maps.ControlPosition.BOTTOM_LEFT
							}
						};

						map = new google.maps.Map($('#map_canvas').get(0), mapOptions);

						for (var i = markerList.length - 1; i >= 0; i--)
						{
							markerList[i].setMap(null);
							markerList.pop();
						}
						var marker = new google.maps.Marker({
							map: map,
							position: results[0].geometry.location,
							animation: google.maps.Animation.DROP,
							draggable: false,
							icon: 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png'
						});
						markerList.push(marker);
					} else {
						alert('Geocoder unsuccessful:  ' + status);
					}
				});
			}

        	function closeModal() {
        		$('#myModal').modal('hide');
				$('body').removeClass('modal-open');
				$('.modal-backdrop').remove();
        	}

        </script>
</html>
