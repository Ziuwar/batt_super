<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>1105 Battery Supervisory</title>
    <link rel="stylesheet" type="text/css" href="../css/batt_super_app.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  </head>
  <body>

  <?php
    include 'bs_db_ops.php';
    include 'bs_html_common.php';
    
    //Open the database connection
    $conn = db_session();
  
    //Generate the navigation bar at the top, fixed version for browser compatibility.
    navbar();
    ?>
   
  <h2 id="basic" class = "table_header" align="center"> Basic configuration </h2>
  <p class = "basic_text" align="center"> To get the EEPROM code, click on the corresponding link under "EEPROM File".<br> 
  										  Click on the serial number to update the part (login is required for access).</p>
  
  <!-- Generation of the unit table -->
  <?php table_units($conn,filter_serial(0)); ?>
  
  <hr>
  
  <h2 id ="cal" class = "table_header" align="center"> Calibration </h2>
  	
   <!-- Generation of the calibration table -->
  <?php table_calibration($conn,filter_serial(0)); ?>
	
  <hr>
  
  <h2 id="maint" class = "table_header" align="center"> Maintenance </h2>
   
    <!-- Generation of the maintenance table -->
  <?php table_maintenance($conn,filter_serial(0));

        $conn->close();
  ?>
  
  <h4 class="to_top" align="center"><a href="#top">Back to top</a> </h4>    

  <?php footer(); ?>
  
  </body>
</html>
