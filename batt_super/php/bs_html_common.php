<!-- This function defines the navbar used it this webpage -->
<?php function navbar(){ ?>

	<?php
	//Gives the active URL
    $url =  "//{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    //Last URL visited
    $escaped_url = htmlspecialchars( $url, ENT_QUOTES, 'UTF-8' );
    //echo '<a href="' . $escaped_url . '">' . $escaped_url . '</a>';
    $tab_active = 'class="active"';
    ?>
    
    <!-- The mighty navbar -->
    <ul id="top">
    	<!-- Navbar item to show the project name of the webapp -->
    	<li style="float:left"><a>1105 Battery Supervisory</a></li>
    	<!-- Navbar option to show the view page, this is also the default page shown -->
    	<li><a <?php if(preg_match('*bs_view_data.*', $escaped_url)) {echo $tab_active;} ?> href="/php/bs_view_data.php">View</a></li>
    	<!-- Navbar option to show the update page, login is required to show the page -->
    	<li><a <?php if(preg_match('*bs_update_data.*', $escaped_url)) {echo $tab_active;} ?> href="/php/bs_update_data.php">Update</a></li>
    	
    	<!-- Navbar option to access the login page or to logout -->
    	<?php if(!isset($_SESSION['userid'])){ ?>
    		<li style="float:right"><a <?php if(preg_match('*bs_user_ops.*', $escaped_url)) {echo $tab_active;} ?> target="_top" href="/php/bs_user_ops.php?action=login">Login</a></li>
    	<?php } else {?>
    		<li style="float:right"><a target="_top" href="/php/bs_user_logout.php?action=logout">Logout</a></li>
    	<?php }?>
    	
    	<!-- Information for the user if the loggin is done -->
    	<?php if(isset($_SESSION['userid'])){ echo '<li style = "float:right"><a>Logged in as: ' .$_SESSION['userid']. '</a></li>';}
    	else{ echo '<li style = "float:right"><a>Not logged in:</a></li>';}?>
    	
    	<!-- Filter field config when view page -->
    	 <?php if(preg_match('*bs_view_data.*', $escaped_url)) {echo
    	 '<li style="float:left"><form class="filter_units" method="post" action="/php/bs_view_data.php">
    								<label class="filter_label" for="filter_serial">SN-Filter:</label>
   									<input type="number" name="filter_serial">
   									<input type="submit" name="filter_go>" value="GO / Reset">
   								</form> </li>';} ?>
   		
   		<!-- Filter field when update page is shown -->
   		 <?php if(preg_match('*bs_update_data.*', $escaped_url)) {echo
    	 '<li style="float:left"><form class="filter_units" method="post" action="/php/bs_update_data.php">
    								<label class="filter_label" for="filter_serial">SN-Filter:</label>
   									<input type="number" name="filter_serial">
   									<input type="submit" name="filter_go>" value="GO / Reset">
   								</form> </li>';} ?>
    </ul>
<?php } ?>

<!-- ------------------------------------------------------------------------------------ -->

<?php function footer(){?>
    
      <footer>Copyright 2019 -  A. Schröder - Avionik Straubing Entwicklungs GmbH - V0.3</footer>
    
<?php } ?>

<!-- ------------------------------------------------------------------------------------ -->
   
<?php function filter_serial($serial_requested){
       
       //$serial_requested = 0;
       $serial_err = "";
   
   if ($_SERVER["REQUEST_METHOD"] == "POST") {
       if (empty($_POST["filter_serial"] || $_POST["filter_serial"] == 0)) {
           $serial_err = "Serial Required";
       } else {
           if (!preg_match("/^[0-9]*$/",$_POST['filter_serial'])) {
               $serial_err = "Only numbers allowed";
           } else {
               $serial_requested = $_POST['filter_serial'];
           }
       }
   }
   
     if (empty($serial_err)){
         return $serial_requested;
    } 
   }
?>
   
<!-- ------------------------------------------------------------------------------------ -->  

<?php function check_basic_input($partnumber,$pcb_marking,$software_revision,$eeprom_file){
    
   $input_error = null;
   
   if(empty($partnumber)||empty($pcb_marking)||empty($software_revision)||empty($eeprom_file)){
       $input_error = "Fill in all boxes!";
   } else {

       if(!preg_match("/^[P][C][2][4][B][A][1][-][0-9]{2}$/", $partnumber)){
           $input_error = "Partnumber wrong format.";
       }
       if(!preg_match("/^[B][a][t][t][S][u][p][e][r][V][i][s][-][0-9]{2}$/", $pcb_marking)){
           $input_error = "PCB marking wrong format.";
       }
       if(!preg_match("/^[0-9]{3}[.][0-9]{3}[.][0-9]{3}$/", $software_revision)){
           $input_error = "Software revision wrong format.";
       }
       if(!preg_match("/^[ABCDEF:0-9]*$/", $eeprom_file)){
           $input_error = "EEPROM data wrong format.";
       } 
   }
   return $input_error;
}?>

<!-- ------------------------------------------------------------------------------------ -->  

<?php function check_calibration_input($calibration_constant,$warning_voltage,$maintenance_voltage,$error_voltage){
    
   $input_error = null;
   
   if(empty($calibration_constant)||empty($warning_voltage)||empty($maintenance_voltage)||empty($error_voltage)){
       $input_error = "Fill in all boxes!";
   } else {
        
       if(!preg_match("/^[0][x][0-9]{4}$/", $calibration_constant)){
           $input_error = "Calibration constant wrong format.";
       }
       if(!preg_match("/^[0-9]{2}[.][0-9]{2}$/", $warning_voltage)){
           $input_error = "Warning voltage wrong format.";
       }
       if(!preg_match("/^[0-9]{2}[.][0-9]{2}$/", $maintenance_voltage)){
           $input_error = "Software revision wrong format.";
       }
       if(!preg_match("/^[0-9]{2}[.][0-9]{2}$/", $error_voltage)){
           $input_error = "Error voltage wrong format.";
       } 
   }
   return $input_error;
}?>

<!-- ------------------------------------------------------------------------------------ --> 

<?php function check_maintenance_input($operating_minutes,$power_cycles,$recovery_errors,$maintenance_count,$error_count){
    
   $input_error = null;
   
   if(empty($operating_minutes)||empty($power_cycles)||empty($recovery_errors)||empty($maintenance_count)||empty($error_count)){
       $input_error = "Fill in all boxes!";
   } else {
        
       if(!preg_match("/^[0-9]*$/", $operating_minutes)){
           $input_error = "Operating minutes wrong format.";
       }
       if(!preg_match("/^[0-9]*$/", $power_cycles)){
           $input_error = "Power cycles wrong format.";
       }
       if(!preg_match("/^[0-9]*$/", $recovery_errors)){
           $input_error = "Recovery errors wrong format.";
       }
       if(!preg_match("/^[0-9]*$/", $maintenance_count)){
           $input_error = "Maintenance count wrong format.";
       }
       if(!preg_match("/^[0-9]*$/", $error_count)){
           $input_error = "Error count wrong format.";
       }
   }
   return $input_error;
}?>

<!-- ------------------------------------------------------------------------------------ --> 