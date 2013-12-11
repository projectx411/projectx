<?php
    session_start();
    require_once 'mysql/login.php';
    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
    $email = $_SESSION['email'];
    $emailArray = mysqli_query($connection, "SELECT * FROM Student WHERE email='$email'");

	if (isset($_COOKIE["user"]))
				$userMail = $_COOKIE["user"];
	else
				header ("Location: index.php");

	$location = "";
	$description = "";
	$eventName = "";
	$activity = "";
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="shortcut icon" href="images/favicon.ico">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://code.jquery.com/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<meta charset="utf-8">
<title>Edit Event</title>
</head>
<body>
<div class="container">
<h1>Edit Event</h1>
<div id="navbar"></div>
<table class="table table-striped" style="width: 500px;">
<thead>
<tr>
<th>Details</th>
</tr>
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
<button type="button" class="btn btn-primary" id="create">Create an event</button>
</div><!-- /container -->
<script>
$(function() {

	$('#navbar').load('navbar.php', function(){
		$('#tabs li').each(function() {
			$(this).removeClass('active');
		});
		$('#eventTab').addClass('active');
	});

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

	// hide and show activities
	$('div.errorName').hide();
	$('div.errorLocation').hide();
	$('div.errorCity').hide();

	$('#yearSel').on('change', function() {
		int i;
		$('#daySel').empty();
		if ($('#monthSel').val() == 2) {
			if ($(this).val() % 4 == 0) {
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
</body>
</html>
