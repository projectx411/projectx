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

	if ($_FILES) {
	  $name = $_FILES['filename']['name'];
	  $split = explode(".",$name);
	  $newName = $userMail.".".end($split);
	   move_uploaded_file($_FILES['filename']['tmp_name'], "uploads/".$newName);
	   //echo "Uploaded image '$newName'<br />";
	  }

  $allowedExtensions = ["JPG", "png", "gif","tif"];

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
<table class="table table-hover" id="attributes">
<thead>
<tr>
<th>Details</th>
</tr>

</table>
<a href="profile.php">Return to Homepage</a>
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
