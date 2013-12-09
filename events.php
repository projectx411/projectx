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


        <link href="kendoui/styles/kendo.common.min.css" rel="stylesheet" />
        <link href="kendoui/styles/kendo.default.min.css" rel="stylesheet" />
        <script src="kendoui/js/jquery.min.js"></script>
        <script src="kendoui/js/kendo.web.min.js"></script>


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
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
    <script type="text/javascript" src="js/typeahead.js"></script>
    <body>
        <div class="container">
            <h1>Activities</h1>
            <div id="navbar"></div>
            <h3>Events</h3>



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
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary" id="create">Create an event</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            </br>
            </br>
            <table table-layout=fixed width= 100% class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 15%;">Event name</th>
                        <th style="width: 15%;">Activity</th>
                        <th style="width: 15%;">Date</th>
                        <th style="width: 10%;">Time</th>
                        <th style="width: 15%;">Location</th>
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
                            echo "<tr>";
                            echo "<td>".$row[1]."</td>";
                            echo "<td>".$row[3]."</td>";
                            $ts = date_create($row[5]);
                            $date = $ts->format('F j, Y');
                            echo "<td>".$date."</td>";
                            $time = $ts->format('g:i a');
                            echo "<td>".$time."</td>";
                            echo "<td>".$row[4]."</td>";
                            echo "<td>".$row[2]."</td>";
                        }
                    ?>
                </tbody>
            </table>
        </div><!-- /container -->
        <script type="text/javascript">
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
            });

            // hide and show activities
            $('div.errorName').hide();
            $('div.errorLocation').hide();
            $('div.errorCity').hide();
                
            // navbar
            $(document).ready(function() {
                $('#navbar').load('navbar.php', function(){
                    $('#tabs li').each(function() {
                        $(this).removeClass('active');
                    });
                    $('#eventTab').addClass('active');
                });
            })
        </script>
    </body>
</html>
