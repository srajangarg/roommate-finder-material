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


  <?php

    error_reporting(E_ALL & ~E_WARNING );

    if(!isset($_POST['roomno']) || !isset($_POST['hostel']) || !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['fbid']) )
    {
        $error = true;
    }
    else
    {
      $error = false;
    }

    if(!$error)
    {
        $name = $_POST['name'];
        $name = ucwords(strtolower($name));
        $email = $_POST['email'];
        $fbid = $_POST['fbid'];
        $hostelchoice = $_POST['hostel'];
        $roomnum = $_POST['roomno'];
        $wingnum = 3*(floor($roomnum/100)-1) + floor((($roomnum - floor($roomnum/100)*100)-1)/6) +1;

        function getfbimg($url)
        {
          ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
          $output = file_get_contents($url); 
          $occurence = strpos($output, '<img class="profilePic img');
          if(!$occurence)
          {
            return "notfound";
          }
          $src = strpos($output,'src="',$occurence);
          $end = strpos($output,'"',$src+5);
          $imgurl = substr($output,$src+5,$end - $src -5);
          $imgurl = str_replace('amp;', '', $imgurl);
          return $imgurl;
        }

        $fbimg = getfbimg($fbid);
        
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

        $queryemail = sprintf("SELECT * FROM allstudents WHERE email ='%s';",$email);
        $resultemail = $conn->query($queryemail);

        $emailnum = $resultemail->num_rows;

        if($emailnum == 0)
        {
          $alreadyexists = false;
          $insertq = sprintf("INSERT INTO allstudents (name,fbid,email,roomno,wingno,hostelno,fbimg) VALUES ('%s','%s','%s','%s','%s','%s','%s')",$name,$fbid,$email,$roomnum,$wingnum,$hostelchoice,$fbimg);
          if ($conn->query($insertq) === TRUE) 
          {
              //echo "New record created successfully";
          } 
          else 
          {
              echo "Error: " . $insertq . "<br>" . $conn->error;
          }
        }
        else
        {
          $alreadyexists = true;
        }
    }  

  ?>

  <nav class="teal lighten-1" role="navigation">
      <div class="nav-wrapper container"><a id="logo-container" href="index.html" class="brand-logo">Room-mate Finder</a>
      </div>
  </nav>

    <div class="section no-pad-bot" id="index-banner">
      <div class="container">

        <br><br>
        <h2 class="header center teal-text">

        <?php 

          if($error)
          {
            echo "Shucks!";
          }
          else
          {
            if($alreadyexists)
            {
              echo "Sorry!";
            }
            else
            {
              echo "Thanks!";
            }
          }
          
        ?>

        </h2>
            
        
        <div class="row center">
          <h5 class="header col s12 light">
          <?php 

            if($error)
            {
              echo "I think you meant to go <a style='color:teal' href='index.html' >here</a>!";
            }
            else
            {
              if($alreadyexists)
              {
                echo "Seems like you've already registered!";
              }
              else
              {
                echo "You've been successfully added! Please share the app!";
              }
            }
            
          ?>
          </h5>
          <?php 
            if(!$error)
            {
              echo '<div class="col offset-s8"><a class="modal-trigger" style="color:teal" href="#modal2">Filled something incorrectly?</a></div>';
            }
          ?>
        </div>
        
        <br><br>

      </div>

    </div>

    <div id="modal2" class="modal">
      <div class="modal-content">
        <h4 style="color:teal">Filled something incorrectly?</h4>
        <p>Just drop me an email at srajan.garg@gmail.com, or message me on <a href="https://facebook.com/srajan.garg" target="_blank" style="color:teal">Facebook</a>. I'll make the necessary changes.</p>
      </div>
    </div> 

    


    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.96.1/js/materialize.min.js"></script>
    <script>
    $(document).ready(function() {

        $('.modal-trigger').leanModal();

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