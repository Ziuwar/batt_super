<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>1105 Battery Supervisory Data Update</title>
    <link rel="stylesheet" type="text/css" href="../css/batt_super_app.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  </head>
  <body>

  <?php
    include 'bs_db_ops.php';
    include 'bs_html_common.php';
    
    //Open the database connection
    $conn = db_session();
  
    navbar();
    
    if (isset($_SESSION['userid'])){
        
        if(isset($_GET['serial'])){
            $serial = $_GET['serial'];
        }else {
            //Returns the serial if found, or '' if not
            $serial = check_for_serial($conn,filter_serial(0));
        }
    ?>
  
  <h2 class = "table_header" align="center"> Basic configuration </h2>
	
	<?php update_basic_config($conn,$serial);?>
  
  <hr>
  
  <h2 class = "table_header" align="center"> Calibration </h2>
  
	<?php update_calibration_record($conn,$serial); ?>
	
  <hr>
  
  <h2 class = "table_header" align="center"> Maintenance </h2>
  
	<?php add_maintenance_record($conn,$serial); ?>
	
	
  <?php $conn->close(); 
            
    } else {
        
        echo "<h4 align='center'> You are not logged in. Login here: <a href='bs_user_ops.php'> Click Me </a> </h4>";        
    }
    
  ?>

  <?php footer(); ?>
  
  </body>
</html>