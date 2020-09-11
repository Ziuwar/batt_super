<?php

function db_session(){
    
    $servername = "localhost";
    $username = "remote";
    $password = "remoteavsr";
    $dbname = "batt_super";
    
    $conn = new mysqli($servername,$username,$password,$dbname);
    
    //Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

function table_units($conn,$unit_requested)
{
    
    if($unit_requested==0){$sql_units = "SELECT batt_super.units.serial,batt_super.units.partnumber,batt_super.units.pcb_label, batt_super.units.software, batt_super.users.forename, batt_super.users.surname, batt_super.units.created FROM batt_super.units INNER JOIN batt_super.users ON batt_super.units.user_uid = batt_super.users.uid ORDER BY batt_super.units.serial";
        } else{$sql_units = "SELECT batt_super.units.serial,batt_super.units.partnumber,batt_super.units.pcb_label, batt_super.units.software, batt_super.users.forename, batt_super.users.surname, batt_super.units.created FROM batt_super.units INNER JOIN batt_super.users ON batt_super.units.user_uid = batt_super.users.uid WHERE batt_super.units.serial=".$unit_requested;
              }
    
    $result = $conn->query($sql_units);
    
    echo '<table class = "data" style="width:100%" align="center">';
    echo '<tr class = "header"> <th>Serial Number</th> <th>Partnumber</th> <th>PCB Marking</th> <th>Software Revision</th> <th>EEPROM File</th>
         <th>Worker</th> <th>First Start-Up</th></tr>';
    
    if ($result->num_rows > 0) {
        //output of each row
        while ($row = $result->fetch_assoc()){
            echo "<tr class = 'datarow'><td> <a href=/php/bs_update_data.php?serial=" .$row["serial"]. "> ".$row["serial"]."</a>";
            echo "</td>" , "<td>" .$row["partnumber"]. "</td>" , "<td>" .$row["pcb_label"]. "</td>" , "<td>" .$row["software"]. "</td>" , "<td>";
            echo "<a target='_blank' href=/php/bs_get_eeprom.php?serial=" .$row['serial']. "> <img src='/images/sql_icon.png' alt='EEPROM SQL query' style='width:35px;height:35px;border:0;'> </a>";
            echo "</td>", "<td>" .$row["forename"]. "." .$row["surname"]. "</td>", "<td>" .$row["created"]. "</td></tr>" ;
        }
    }
    echo "</table>";  
}

function table_calibration($conn,$unit_requested){
    
    if($unit_requested==0){$sql_calibration = "SELECT batt_super.calibration.serial, batt_super.calibration.calibration_const, batt_super.calibration.warning_voltage, batt_super.calibration.maintenance_voltage, batt_super.calibration.error_voltage, batt_super.users.forename, batt_super.users.surname, batt_super.calibration.created, batt_super.calibration.updated FROM calibration INNER JOIN batt_super.users ON batt_super.calibration.user_uid = batt_super.users.uid ORDER BY serial";
    }else {$sql_calibration = "SELECT batt_super.calibration.serial, batt_super.calibration.calibration_const, batt_super.calibration.warning_voltage, batt_super.calibration.maintenance_voltage, batt_super.calibration.error_voltage, batt_super.users.forename, batt_super.users.surname, batt_super.calibration.created, batt_super.calibration.updated FROM calibration INNER JOIN batt_super.users ON batt_super.calibration.user_uid = batt_super.users.uid WHERE batt_super.calibration.serial=".$unit_requested;  
    }
    
    $result = $conn->query($sql_calibration);
    
    echo '<table class = "data" style="width:100%" align="center">';
    echo '<tr class = "header"> <th>Serial Number</th> <th>Calibration Constant</th> <th>Warning Voltage [V]</th> <th>Maintenance Voltage [V]</th>
		<th>Error Voltage [V]</th> <th>Worker</th> <th>Initial Calibration</th> <th>Calibration Updated</th></tr>';
    
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            echo "<tr class = 'datarow'><td> <a href=/php/bs_update_data.php?serial=" .$row["serial"]. "> ".$row["serial"]."</a>";
            echo "</td>" , "<td>" .$row["calibration_const"]. "</td>" , "<td>" .$row["warning_voltage"]. "</td>" , "<td>" .$row["maintenance_voltage"]. "</td>" ,
            "<td>" .$row["error_voltage"]. "</td>" , "<td>" .$row["forename"]. "." .$row["surname"]. "</td>" , "<td>" .$row["created"]. "</td>" , "<td>" .$row["updated"]. "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";  
}

function table_maintenance($conn,$unit_requested){
    
    if($unit_requested==0){$sql_maintenance = "SELECT batt_super.maintenance.serial, batt_super.maintenance.operating_minutes, batt_super.maintenance.power_cycles, batt_super.maintenance.recovery_errors, batt_super.maintenance.maintenance_count, batt_super.maintenance.error_count, batt_super.users.forename, batt_super.users.surname, batt_super.maintenance.created FROM maintenance INNER JOIN batt_super.users ON batt_super.maintenance.user_uid = batt_super.users.uid ORDER BY serial";
    }else{$sql_maintenance = "SELECT batt_super.maintenance.serial, batt_super.maintenance.operating_minutes, batt_super.maintenance.power_cycles, batt_super.maintenance.recovery_errors, batt_super.maintenance.maintenance_count, batt_super.maintenance.error_count, batt_super.users.forename, batt_super.users.surname, batt_super.maintenance.created FROM maintenance INNER JOIN batt_super.users ON batt_super.maintenance.user_uid = batt_super.users.uid WHERE batt_super.maintenance.serial=".$unit_requested;
    }
    
    $result = $conn->query($sql_maintenance);
    
    echo '<table class = "data" style="width:100%" align="center">';
    echo '<tr class = "header"> <th>Serial Number</th> <th>Operating Minutes</th> <th>Power Cycles</th> <th>Recovery Errors</th>
		<th>Maintenance Count</th> <th>Error Count</th> <th>Worker</th> <th>Maintenance Date</th></tr>';
    
    if ($result->num_rows > 0){
        while ($row = $result->fetch_assoc()){
            echo "<tr class = 'datarow'><td> <a href=/php/bs_update_data.php?serial=" .$row["serial"]. "> ".$row["serial"]."</a>";
            echo "</td>" , "<td>" .$row["operating_minutes"]. "</td>" , "<td>" .$row["power_cycles"]. "</td>" , "<td>" .$row["recovery_errors"]. "</td>" ,
            "<td>" .$row["maintenance_count"]. "</td>" , "<td>" .$row["error_count"]. "</td>" , "<td>" .$row["forename"]. "." .$row["surname"]. "</td>" , "<td>" .$row["created"]. "</td>";
            echo "</tr>";
        }
    }
    echo "</table>";
}

function update_basic_config($conn,$serial){
 
     $serial_number = $v_partnumber = $v_pcb_marking = $v_software_revision = $v_eeprom_file = $v_worker = '';
        
     $sql_prefill_basic = "SELECT batt_super.units.serial, batt_super.units.partnumber, batt_super.units.pcb_label, batt_super.units.software, batt_super.units.eeprom_file FROM batt_super.units WHERE batt_super.units.serial = ". $serial;
     
     if ($serial!=''){
     $result = $conn->query($sql_prefill_basic);
        
     if ($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
        
            $serial_number = $row["serial"];
            $v_partnumber = $row["partnumber"];
            $v_pcb_marking = $row["pcb_label"];
            $v_software_revision = $row["software"];
            $v_eeprom_file = $row["eeprom_file"];
            $v_worker = $_SESSION['userid'];
            }
       }
    }        
?>
    <form method="post" action="<?php echo htmlspecialchars("bs_update_handler.php?action=1&serial=".$serial_number);?>">
    	<table class = "data" style="width:100%">
    
    	<tr class = "header"> <th>Serial Number</th> <th>Partnumber</th> <th>PCB Marking</th> <th>Software Revision</th> <th>EEPROM File</th> <th>Worker</th></tr>
    	<tr>
    		<td> <?php echo $serial_number;?> </td>
    		<td> <input type = "text" name="v_partnumber" 			value="<?php echo $v_partnumber;?>">  		</td>
    		<td> <input type = "text" name="v_pcb_marking" 			value="<?php echo $v_pcb_marking;?>">  		</td>
    		<td> <input type = "text" name="v_software_revision" 	value="<?php echo $v_software_revision;?>"> </td>
    		<td> <input type = "text" name="v_eeprom_file" 			value="<?php echo $v_eeprom_file;?>">  		</td>
    		<td> <?php echo $v_worker;?> </td>
    	</tr>
    	</table>
    	<input class ="data_update_button" type = "submit" name="update_user" value="Update">
    </form>
  
  <?php  
    	if(isset($_GET['error_b'])){
	    
    	    $error = $_GET['error_b']; ?>
			<span class="result_nok"> <?php echo $error;?></span><br>
		
    <?php } ?>
    
<?php }

function update_calibration_record($conn,$serial){
    
    $serial_number = $c_calibration_constant = $c_warning_voltage = $c_maintenance_voltage = $c_error_voltage = $c_worker = '';
    
    $sql_prefill_calibration = "SELECT batt_super.calibration.serial, batt_super.calibration.calibration_const, batt_super.calibration.warning_voltage, batt_super.calibration.maintenance_voltage, batt_super.calibration.error_voltage FROM batt_super.calibration WHERE batt_super.calibration.serial = ". $serial;
    
    if ($serial!=''){
        $result = $conn->query($sql_prefill_calibration);

        //Prefill if a record is found (a calibration was already performed on that serial)
        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                
                $serial_number = $row["serial"];
                $c_calibration_constant = $row["calibration_const"];   
                $c_warning_voltage = $row["warning_voltage"];
                $c_maintenance_voltage = $row["maintenance_voltage"];
                $c_error_voltage = $row["error_voltage"];
                $c_worker = $_SESSION['userid'];
            }
        }else{
            //Prefill serial and worker name if no reccord is found (no calibration performed)
            $serial_number = $serial;
            $c_worker = $_SESSION['userid'];
        }
    } 
    
    ?>
    
    <form method="post" action="<?php echo htmlspecialchars("bs_update_handler.php?action=2&serial=".$serial_number);?>">  
      <table class = "data" style="width:100%">
   		
   		<tr class = "header"> <th>Serial Number</th> <th>Calibration Constant</th> <th>Warning Voltage [V]</th> <th>Maintenance Voltage [V]</th> <th>Error Voltage [V]</th> <th>Worker</th></tr>
		<tr>
			<td> <?php echo $serial_number;?> </td>
			<td> <input type = "text" name="c_calibration_constant" 	value="<?php echo $c_calibration_constant;?>">  </td>
			<td> <input type = "text" name="c_warning_voltage" 			value="<?php echo $c_warning_voltage;?>">  		</td>
			<td> <input type = "text" name="c_maintenance_voltage" 		value="<?php echo $c_maintenance_voltage;?>"> 	</td>
			<td> <input type = "text" name="c_error_voltage" 			value="<?php echo $c_error_voltage;?>">  		</td>
			<td> <?php echo $c_worker;?> </td>
		</tr>
		</table>
			<input class ="data_update_button" type = "submit" name="update_user" value="Update">
	</form>    
    
      <?php  
    	if(isset($_GET['error_c'])){
	    
	        $error = $_GET['error_c']; ?>
	   	 	<span class="result_nok"> <?php echo $error;?></span><br>
	    
    <?php } ?>
    
<?php }

function add_maintenance_record($conn,$serial){
    
    $serial_number = $m_operating_minutes = $m_power_cycles = $m_recovery_errors = $m_maintenace_count = $m_error_count = $m_worker ='';
    
    $sql_prefill_maint = "SELECT batt_super.units.serial FROM batt_super.units WHERE batt_super.units.serial = ".$serial;
    
    if($serial!=''){
        
        $result = $conn->query($sql_prefill_maint);
        
        if($result->num_rows > 0){
            
            while($row = $result->fetch_assoc()){
                
                $serial_number = $row["serial"];
                $m_worker = $_SESSION["userid"];
            }
        }
    }

  ?>  
    
    <form method="post" action="<?php echo htmlspecialchars("bs_update_handler.php?action=3&serial=".$serial_number);?>"> 
		<table class = "data" style="width:100%">
	
		<tr class = "header"> <th>Serial Number</th> <th>Operating Minutes</th> <th>Power Cycles</th> <th>Recovery Errors</th> <th>Maintenance Count</th> <th>Error Count</th> <th>Worker</th></tr>
		<tr>
			<td> <?php echo $serial_number;?> </td>
			<td> <input type = "text" name="m_operating_minutes" 		value="<?php echo $m_operating_minutes;?>"> </td>
			<td> <input type = "text" name="m_power_cycles" 			value="<?php echo $m_power_cycles;?>">  	</td>
			<td> <input type = "text" name="m_recovery_errors" 			value="<?php echo $m_recovery_errors;?>"> 	</td>
			<td> <input type = "text" name="m_maintenace_count" 		value="<?php echo $m_maintenace_count;?>">  </td>
			<td> <input type = "text" name="m_error_count" 				value="<?php echo $m_error_count;?>">  		</td>
			<td> <?php echo $m_worker;?> </td>
		</tr>
		</table>
			<input class ="data_update_button" type = "submit" name="update_user" value="Add">
	</form>
	
	    <?php  
    	if(isset($_GET['error_m'])){
	    
	        $error = $_GET['error_m'];?>
	    	<span class="result_nok"> <?php echo $error;?></span><br>
	    
    	<?php } ?>  
    
<?php } ?>

<!-- ------------------------------------------------------------------------------------ -->

<?php function check_for_serial($conn,$serial){
    
    if($serial != ''){
    
    $sql_find_serial = "SELECT batt_super.units.serial FROM batt_super.units WHERE batt_super.units.serial=".$serial;
    
        $result = $conn->query($sql_find_serial);
        
        if($result->num_rows > 0){
            
            $serial_found = $result->fetch_all();
            
            return $serial_found[0][0];
            
        }else{
            
            return '';
        }
    }
}
?>

<!-- ------------------------------------------------------------------------------------ -->

