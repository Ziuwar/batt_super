<?php

include 'bs_db_ops.php';
include 'bs_html_common.php';

session_start();
$conn = db_session();

if (isset($_SESSION["userid"])){
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        
        define("d_serial", $_GET["serial"]);
        $action = $_GET["action"];
        
        //UPDATE of the basic configuration table
        if($action == 1){
            
            $partnumber = $_POST["v_partnumber"];
            $pcb_marking = $_POST["v_pcb_marking"];
            $software_revision = $_POST["v_software_revision"];
            $eeprom_file = $_POST["v_eeprom_file"];
            
            //Check for valid input data
            
            $check_result = check_basic_input($partnumber,$pcb_marking,$software_revision,$eeprom_file);
            
            if($check_result == null){
                // STEP 1 -> Copy old data into histroy table
                
                $sql_select_unit = "SELECT batt_super.units.serial, batt_super.units.partnumber, batt_super.units.pcb_label, batt_super.units.software, batt_super.units.eeprom_file, batt_super.units.user_uid FROM batt_super.units WHERE batt_super.units.serial = ".d_serial;
         
                $result = $conn->query($sql_select_unit);
                $select_all = $result->fetch_all(MYSQLI_ASSOC);
                
                $sql_insert_history = "INSERT INTO batt_super.units_history 
                                       (batt_super.units_history.serial, batt_super.units_history.partnumber, batt_super.units_history.pcb_label, batt_super.units_history.software, batt_super.units_history.eeprom_file, batt_super.units_history.user_uid)
                                       VALUES ('".$select_all[0]["serial"]."','".$select_all[0]["partnumber"]."','".$select_all[0]["pcb_label"]."','".$select_all[0]["software"]."','".$select_all[0]["eeprom_file"]."','".$select_all[0]["user_uid"]."')";
                
                $result->free();
                
                //echo $sql_insert_history;
                $conn->query($sql_insert_history);
                
                // STEP 2 -> Update new data into the basic configuration table
                
                $sql_update_basic = "UPDATE batt_super.units SET  
                                    
                                    batt_super.units.partnumber = '".$partnumber."',
                                    batt_super.units.pcb_label = '".$pcb_marking."',
                                    batt_super.units.software = '".$software_revision."',
                                    batt_super.units.eeprom_file = '".$eeprom_file."',
                                    batt_super.units.user_uid = '".$_SESSION["useruid"]."' 
                                    
                                    WHERE batt_super.units.serial = ".d_serial;
                
                $conn->query($sql_update_basic);
                header('Location: bs_update_data.php?serial='.d_serial);
            }else {
                header('Location: bs_update_data.php?serial='.d_serial.'&error_b='.$check_result);
            }
            
        //UPDATE of the calibration table
        }elseif ($action == 2){
            
            $calibration_constant = $_POST["c_calibration_constant"];
            $warning_voltage = $_POST["c_warning_voltage"];
            $maintenance_voltage = $_POST["c_maintenance_voltage"];
            $error_voltage = $_POST["c_error_voltage"];
            
            $check_result = check_calibration_input($calibration_constant,$warning_voltage,$maintenance_voltage,$error_voltage);
            
            if($check_result == null){
                // STEP 1 -> Copy old data into histroy table
                $sql_select_unit = "SELECT batt_super.calibration.serial, batt_super.calibration.calibration_const, batt_super.calibration.warning_voltage, batt_super.calibration.maintenance_voltage, batt_super.calibration.error_voltage, batt_super.calibration.user_uid, batt_super.calibration.updated 
                                    FROM batt_super.calibration WHERE batt_super.calibration.serial = ".d_serial;
        
                $result = $conn->query($sql_select_unit);
                $select_all = $result->fetch_all(MYSQLI_ASSOC);
                
                //Check if the serial has already a calibration record: If not -> Insert new record. If yes -> update the record and write history.
                if(isset($select_all[0]["serial"])){
                
                $sql_insert_cal_history = "INSERT INTO batt_super.calibration_history
                                           (batt_super.calibration_history.serial, batt_super.calibration_history.calibration_const, batt_super.calibration_history.warning_voltage, batt_super.calibration_history.maintenance_voltage, batt_super.calibration_history.error_voltage, batt_super.calibration_history.user_uid)
                                           VALUES ('".$select_all[0]["serial"]."', '".$select_all[0]["calibration_const"]."', '".$select_all[0]["warning_voltage"]."', '".$select_all[0]["maintenance_voltage"]."', '".$select_all[0]["error_voltage"]."', '".$select_all[0]["user_uid"]."')";
                
                $conn->query($sql_insert_cal_history);
                $result->free();
                
                // STEP 2 -> Update new data into the basic configuration table
                        $sql_update_cal = "UPDATE batt_super.calibration SET
        
                                    batt_super.calibration.calibration_const = '".$calibration_constant."',
                                    batt_super.calibration.warning_voltage = '".$warning_voltage."',
                                    batt_super.calibration.maintenance_voltage = '".$maintenance_voltage."',
                                    batt_super.calibration.error_voltage = '".$error_voltage."',
                                    batt_super.calibration.user_uid = '".$_SESSION["useruid"]."'
        
                                    WHERE batt_super.calibration.serial = ".d_serial;
                
                $conn->query($sql_update_cal);
                } else {
                
                    $sql_insert_cal = "INSERT INTO batt_super.calibration 
                                       (batt_super.calibration.serial, batt_super.calibration.calibration_const, batt_super.calibration.warning_voltage, batt_super.calibration.maintenance_voltage, batt_super.calibration.error_voltage, batt_super.calibration.user_uid) 
                                       VALUES ('".d_serial."','".$calibration_constant."','".$warning_voltage."','".$maintenance_voltage."','".$error_voltage."','".$_SESSION["useruid"]."')";
                    $conn->query($sql_insert_cal);
                    
                }
                header('Location: bs_update_data.php?serial='.d_serial);
            }else {
                header('Location: bs_update_data.php?serial='.d_serial.'&error_c='.$check_result);
            }
       
        //ADD a entry into the maintenance table
        }elseif ($action == 3){
            
            $operating_minutes = $_POST["m_operating_minutes"];
            $power_cycles = $_POST["m_power_cycles"];
            $recovery_errors = $_POST["m_recovery_errors"];
            $maintenance_count = $_POST["m_maintenace_count"];
            $error_count = $_POST["m_error_count"];
            
            $check_result = check_maintenance_input($operating_minutes,$power_cycles,$recovery_errors,$maintenance_count,$error_count);
                
                if($check_result == null){
                $sql_insert_call = "INSERT INTO batt_super.maintenance 
                                    
                                    (batt_super.maintenance.serial, batt_super.maintenance.operating_minutes, batt_super.maintenance.power_cycles, batt_super.maintenance.recovery_errors, batt_super.maintenance.maintenance_count, batt_super.maintenance.error_count, batt_super.maintenance.user_uid)
                                    VALUES ('".d_serial."','".$operating_minutes."','".$power_cycles."','".$recovery_errors."','".$maintenance_count."','".$error_count."','".$_SESSION["useruid"]."')";
                
                $conn->query($sql_insert_call);
                header('Location: bs_update_data.php?serial='.d_serial);
                } else {
                    header('Location: bs_update_data.php?serial='.d_serial.'&error_m='.$check_result); 
                }
        
        }else { echo "Wrong action!"; }
        
        $conn->commit();
        $conn->close();
    }
}