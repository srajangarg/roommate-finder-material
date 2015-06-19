<!DOCTYPE html>
<html lang="en">
  <head>
  
	 <title>Roommate!</title>
	 <!--Import materialize.css-->
	 <link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/css/materialize.min.css"  media="screen,projection"/>
	 <!--Let browser know website is optimized for mobile-->
	 <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

  </head>

  <body>

  <nav class="teal lighten-1" role="navigation">
		<div class="nav-wrapper container"><a id="logo-container" href="index.html" class="brand-logo">Room-mate Finder</a>
		</div>
  </nav>


  <?php

	if(!isset($_POST['roomno']) || !isset($_POST['hostel']))
	 {
		  $error = true;
	 }
	 else
	 {
			$error = false;
	 }

	 if(!$error)
    {
		 $hostelchoice = $_POST['hostel'];
		 $roomnum = $_POST['roomno'];
		 $wingnum = 3*(floor($roomnum/100)-1) + floor((($roomnum - floor($roomnum/100)*100)-1)/6) +1;
		 
		 if($hostelchoice == 1)
		 {
			$hostelchoice = "15A";
		 }
		 elseif($hostelchoice == 2)
		 {
			$hostelchoice = "15B";
		 }
		 elseif($hostelchoice == 3)
		 {
			$hostelchoice = "15C";
		 }
		 elseif($hostelchoice == 4)
		 {
			$hostelchoice = "16A";
		 }


		 $queryroom = sprintf("SELECT * FROM allstudents WHERE roomno ='%s' AND hostelno = '%s';",$roomnum,$hostelchoice);
		 $querywing = sprintf("SELECT * FROM allstudents WHERE wingno ='%s' AND hostelno = '%s' AND roomno != '%s';",$wingnum ,$hostelchoice, $roomnum);


		$mysql_host = "localhost";
        $mysql_user = "root";
        $mysql_password ="";
        $mysql_database = "roommate";
		 
		//$mysql_host = "mysql15.000webhost.com";
		//$mysql_database = "a2861568_srajan";
		//$mysql_user = "a2861568_srajan";
		//$mysql_password = "srajan123";

		 // Create connection
		 $conn = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_database);

		 // Check connection
		 if (!$conn) 
		 {
			  die("Connection failed: " . mysqli_connect_error());
		 }

		 $resultroom = $conn->query($queryroom);
		 $resultwing = $conn->query($querywing);

		 $roommatenum = $resultroom->num_rows;
		 $wingmatenum = $resultwing->num_rows;

	}

	 function cardTemplate($args, $name, $roomno, $email, $fbid, $fbimg)
	 {	
	 	$altsource = "alt.png";
		return sprintf('<div class="col %s">
					 <div class="card white">
						<div class="card-content teal-text">
						  <span class="card-title" style="color: black">%s</span>
						  <img src = "%s" class = "circle" onError="this.src=\'%s\';" align="right" height="25%%" width="25%%"/>
						  <p>Room %s<br><i class="mdi-communication-email tiny"></i>  %s</p>
						  <i class="mdi-social-people tiny"></i><a href="%s" target="_blank" style="color: teal">   Facebook Profile</a>
						</div>
					 </div>
				  </div>',$args,$name , $fbimg,$altsource, $roomno, $email, $fbid);
	 }

  ?>

  	<?php if (!$error) : ?>
	 <div class="section no-pad-bot" id="index-banner">


		<div class="container">
		  <div class="row">

				<h4 class="col s12 center">Your Room</h4>

				<div class="row">

				  <!--2 elements here-->

				<?php
					if($roommatenum == 0)
					{
						echo "<p class = 'center' style='font-size :140%' > <span style='color:teal;font-size :120%'>Aww shucks! </span>Looks like no one from your room has filled up yet!<span>";
					}
					elseif($roommatenum == 1)
					{
						$row = $resultroom->fetch_assoc();
						echo cardTemplate("s12 l4 offset-l4",$row["name"],$row["roomno"],$row["email"],$row["fbid"],$row["fbimg"]);
					}
					else
					{ 
						$row = $resultroom->fetch_assoc();
						echo cardTemplate("s12 l4 offset-l2",$row["name"],$row["roomno"],$row["email"],$row["fbid"],$row["fbimg"]);
						$row = $resultroom->fetch_assoc();
						echo cardTemplate("s12 l4",$row["name"],$row["roomno"],$row["email"],$row["fbid"],$row["fbimg"]);
					}
				?>

				</div>

			 <h4 class="col s12 center">Your Wing</h4>

				<div class="row">

				  <!-- can be infi here-->

				  <?php

					if($wingmatenum == 0)
					{
						echo "<p class = 'center' style='font-size :140%' > <span style='color:teal;font-size :120%'>Aww shucks! </span>Looks like no one from your wing has filled up yet!<span>";
					}

					else
					{
						while(floor($wingmatenum/3) >= 1)
						{
							for ($i=0; $i < 3; $i++) 
							{ 
								$row = $resultwing->fetch_assoc();
								echo cardTemplate("s12 l4",$row["name"],$row["roomno"],$row["email"],$row["fbid"],$row["fbimg"]);
							}
							$wingmatenum = $wingmatenum - 3;
						}

						if($wingmatenum == 1)
						{
							$row = $resultwing->fetch_assoc();
							echo cardTemplate("s12 l4 offset-l4",$row["name"],$row["roomno"],$row["email"],$row["fbid"],$row["fbimg"]);
						}
						elseif($wingmatenum == 2)
						{
							$row = $resultwing->fetch_assoc();
							echo cardTemplate("s12 l4 offset-l2",$row["name"],$row["roomno"],$row["email"],$row["fbid"],$row["fbimg"]);
							$row = $resultwing->fetch_assoc();
							echo cardTemplate("s12 l4",$row["name"],$row["roomno"],$row["email"],$row["fbid"],$row["fbimg"]);
						}
					}

				?>

				</div>

				<h4 class="col s12 center">Your info isn't here? Add it!</h4>
				<form id ="myform" action = "thanks.php" class="col s12" method="post">

				  <div class="row">
					 <div class="input-field col s12 l6">
						<i class="mdi-action-account-box prefix"></i>
						<input autocomplete="off" onchange="validateInput();" onkeyup="validateInput();" onpaste="validateInput();" oninput="validateInput();"  id="name" name="name" type="text" class="validate" >
						<label for="name">Name</label>
					 </div>

					 <div class="input-field col s12 l6">
						<i class="mdi-communication-email prefix"></i>
						<input autocomplete="off" autocapitalize="off" onchange="validateInput();" onkeyup="validateInput();" onpaste="validateInput();" oninput="validateInput();" id="email" name="email" type="email" class="validate">
						<label for="email">Email</label>
					 </div>

				  </div>

				  <div class="row">

					 <div class="input-field col s12 l6">
						<i class="mdi-social-people prefix"></i>
						<input autocomplete="off" autocapitalize="off" onchange="validateInput();" onkeyup="validateInput();" onpaste="validateInput();" oninput="validateInput();" name ="fbid" id="fbid" type="url" class="validate">
						<label for="fbid">Facebook Profile URL</label>
					 </div>

					 <div class="input-field col s12 l3 ">
						  <select name ="hostel">
							 <option value="1">15 A</option>
							 <option value="2">15 B</option>
							 <option value="3">15 C</option>
							 <option value="3">16 A</option>
						  </select>
						  <label>Hostel</label>
					 </div>

					 <div class="input-field col s12 l3">
						<input autocomplete="off" onchange="validateInput();" onkeyup="validateInput();" onpaste="validateInput();" oninput="validateInput();" id="roomno" name="roomno" type="text" class="validate">
						<label for="roomno">Alloted Room No</label>
					 </div>

				  </div>

				  <br><br>
				  <div class="row center">
					 <button type="submit" id="submit-button" class="btn-large waves-effect waves-light teal lighten-1 ">Fill <i class="mdi-content-send right"></i></button>

				  </div>

				</form>
			 </div>
		</div>
		

	 </div>

	 <?php else : ?>

	 	<div class="section no-pad-bot" id="index-banner">
      <div class="container">

        <br><br>
        <h2 class="header center teal-text">

			Shucks!
        </h2>
            
        
        <div class="row center">
          <h5 class="header col s12 light">
				I think you meant to go <a style='color:teal' href='index.html' >here</a>!
          </h5>

          
        </div>

      </div>

    </div>
    
    <?php endif ?>



	 <!--Import jQuery before materialize.js-->
	 <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
	 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/js/materialize.min.js"></script>



	 <script>

	 function validateInput() //(name,email,fbid)
	 { 

		name = $("#name").val();
		email = $("#email").val();
		fbid = $("#fbid").val();
		roomno = $("#roomno").val();

		var x=false;
		var y=false;
		var z=false;
		var w=false;

		if(name == "")
		  x = false;
		else
		  x = true;

		var emailPattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
		y = emailPattern.test(email);

		var fbUrl = /^(https:\/\/)(www\.)?facebook.com\/[a-zA-Z0-9(\.\?)?]/
		z = fbUrl.test(fbid)

		if( $.isNumeric(roomno) && roomno.length > 2 && roomno.length < 5)
		{ 
		  var num = parseInt(roomno)
		  var x = Math.floor(num/100);
		  var y = num%100;

		  if(x >=1 && x<=10 && y>=1 && y<=18)
		  {
			 w = true;
		  }
		  
		}

		var ok = ( x && y && z && w);

		if(ok)
		{
		  $("#submit-button").fadeIn(0);
		}
		else
		{
		  $("#submit-button").fadeOut();
		}
	 }

	 $(document).ready(function() {

		  $("#submit-button").hide();
		  $('select').material_select();
		  document.getElementById("myform").reset();
		  $('#myform input').attr('style', '-webkit-box-shadow: inset 0 0 0 1000px #ffffff !important');

	 });
	 $('html').bind('keypress', function(e)
	 {
		 if(e.keyCode == 13)
		 {
			 return false;
		 }
	 });

	 </script>

  </body>
</html>