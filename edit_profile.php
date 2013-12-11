<?php
    session_start();
    require_once 'mysql/login.php';
    $connection = mysqli_connect($db_hostname, $db_username, $db_password, $db_database) or die(mysql_error());
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
<title>Edit Profile</title>
</head>

<body>
<div class="container">
<h1>Edit Profile</h1>
<div id="navbar"></div>
<table class="table table-hover" id="attributes">
<thead>
<tr>
<th>Attribute</th><th>Current</th>
</tr>
<tr>
<td>Email</td>
<?php echo '<td>'.$email.'</td>'; ?>
<td><button style="width:175px" class="btn btn-primary" data-toggle="modal" data-target="#emailModal">Update Email</button></td>
</tr>
<tr>
<td>Name</td>
<?php echo '<td>'.$name.'</td>'; ?>
<td><button style="width:175px" class="btn btn-primary" data-toggle="modal" data-target="#nameModal">Update Name</button></td>
</tr>
<tr>
<td>Gender</td>
<?php echo '<td>'.$gender.'</td>'; ?>
<td><button style="width:175px" class="btn btn-primary" data-toggle="modal" data-target="#genderModal">Update Gender</button></td>
</tr>
<tr>
<td>Phone Number</td>
<?php echo '<td>'.$phoneNumber.'</td>'; ?>
<td><button style="width:175px" class="btn btn-primary" data-toggle="modal" data-target="#phoneModal">Update Phone Number</button></td>
</tr>
<tr>
<td>Password</td>
<?php echo '<td>'.$password.'</td>'; ?>
<td><button style="width:175px" class="btn btn-primary" data-toggle="modal" data-target="#passModal">Update Password</button></td>
</tr>
<tr>
	<td></td>
	<td></td>
	<td>
		<button style="width:175px" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete Profile</button>
	</td>
</tr>
</table>
<a href="profile.php">Return to Homepage</a>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Update Email</h4>
			</div>
			<div class="modal-body">
				Are you sure you want to delete your account?
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" id="deleteButton">Yes, delete</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Email Modal -->
<div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myModalLabel">Update Email</h4>
			</div>
			<div class="modal-body">
				You cannot change your email. If you have another @illinois.edu account, please create another account.
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Name Modal -->
<div class="modal fade" id="nameModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">Update Name</h4>
</div>
<form class="nameForm">
<div class="modal-body">
New Name: <input name="name" class="input-xlarge" placeholder="Name" type="text">
</div>
</form>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="button" class="btn btn-success" id="nameSubmit">Save changes</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="genderModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">Update Gender</h4>
</div>
<form class="genderForm">
<div class="modal-body">
<span style="font-size: 16px; margin-right: 10px;">Gender</span>
<input type="radio" name="gender" value="male">Male
<input type="radio" name="gender" value="female">Female
<input type="radio" name="gender" value="other">Other
</div>
</form>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="button" class="btn btn-success" id="genderSubmit">Save Changes</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="phoneModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">Update Phone Number</h4>
</div>
<form class="phoneForm">
<div class="modal-body">
New Phone Number: <input id="phoneNumber" name="number" class="input-xlarge" placeholder="Digits only" type="text">
</div>
</form>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button class="btn btn-success" id="phoneSubmit">Save Changes</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h4 class="modal-title" id="myModalLabel">Update Password</h4>
</div>
<form class="passForm">
<div class="modal-body">
New Password: <input name="pass" class="input-xlarge" placeholder="Password" type="text">
</div>
</form>
<div class="modal-footer">
<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
<button type="button" class="btn btn-success" id="passSubmit">Save Changes</button>
</div>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

</div><!-- /container -->
<script>
$(function() {

	var numDigits = 0;

	// Only allow numeric input into phone field.
	$('#phoneNumber').keydown(function(event) {

		var len = $(this).val().length;

		// Allow exceptions.
		if	(event.keyCode == 46 || event.keyCode == 9 ||
			(event.keyCode == 65 && event.ctrlKey) ||
			(event.keyCode == 67 && event.ctrlKey) ||
			(event.keyCode == 88 && event.ctrlKey) ||
			(event.keyCode == 86 && event.ctrlKey) ||
			(event.keyCode >= 35 && event.keyCode <= 45) ||
			(event.keyCode == 13) || (event.keyCode == 8)) {
				return;
		}else if (event.keyCode >= 48 && event.keyCode <= 57 && !(event.shiftKey)) {
			if (len == 10) event.preventDefault();
		}else{
			// For everything else, if it is not a number, block its functionality.
			if (event.shiftKey || (event.keyCode < 48 || event.keyCode > 57) && (event.keyCode < 96 || event.keyCode > 105 )) {
				event.preventDefault();
			}
		}
	});

	$('#navbar').load('navbar.php', function(){
		$('#tabs li').each(function() {
			$(this).removeClass('active');
		});
		$('#profileTab').addClass('active');
	});

  //twitter bootstrap script
  $("button#phoneSubmit").click(function() {
                                $.ajax({
                                       type: "POST",
                                       url: "updatePost.php",
                                       data: $('form.phoneForm').serialize(),
                                       success: function(msg) {
  										window.location = 'edit_profile.php';
                                       },
                                       error: function() {
                                       //alert("error");
                                       }
                                       });
                                });
  $("button#passSubmit").click(function() {
                               $.ajax({
                                      type: "POST",
                                      url: "updatePost.php",
                                      data: $('form.passForm').serialize(),
                                      success: function(msg) {
  										window.location = 'edit_profile.php';
                                      },
                                      error: function() {
                                      //alert("error");
                                      }
                                      });
                               });
  $("button#nameSubmit").click(function() {
                               $.ajax({
                                      type: "POST",
                                      url: "updatePost.php",
                                      data: $('form.nameForm').serialize(),
                                      success: function(msg) {
  										window.location = 'edit_profile.php';
                                      },
                                      error: function() {
                                      //alert("error");
                                      }
                                      });
                               });
  $("button#genderSubmit").click(function() {
                                 $.ajax({
                                        type: "POST",
                                        url: "updatePost.php",
                                        data: $('form.genderForm').serialize(),
                                        success: function(msg) {
  										window.location = 'edit_profile.php';
                                        },
                                        error: function() {
                                        //alert("error");
                                        }
                                        });
                                 });
   
   $("button#deleteButton").click(function() {
   		var request = $.ajax({
   			url: "deleteProfile.php",
   			type: "GET",
   			dataType: "html",
   			success: function() {
   				alert("success");
   			}
   		});
   		
   		
   		/*request.done(function(msg) {
			alert(msg);         
		});*/
		
		
		
		/*request.fail(function(jqXHR, textStatus) {
            alert( "Request failed: " + textStatus );
        });*/
   });
                                 
   $(".modal-content").find("button").click(function() {
                                 	$('#myModal').modal('hide');
									$('body').removeClass('modal-open');
									$('.modal-backdrop').remove();
					             });
					             
	$(".close").click(function() {
				closeModal();
			});				             
                                

  /*
  $("button#profileReturn").click(function() {
                                  var currentURL = window.location.href;
                                  var idx = currentURL.search("edit_profile");
                                  var newURL = currentURL.substr(0, idx) + "profile.php";
                                  window.location.href = newURL;
                                  });
 */
  });
</script>
</body>
</html>
