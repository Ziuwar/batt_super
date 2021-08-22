<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>1105 Battery Supervisory EEPROM File</title>
    <link rel="stylesheet" type="text/css" href="../css/batt_super_app.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
  </head>
  <body>
	
<?php

include 'bs_db_ops.php';
	
	$requested_eeprom = $_GET['serial'];
	
		$sql = "SELECT eeprom_file FROM batt_super.units WHERE serial = " .$requested_eeprom;

		//Create the connection
		$conn = db_session();
		//Check connection
		if ($conn->connect_error) {
			die("Connection failed: " . $conn->connect_error);
		}		
		
		$results = $conn->query($sql);
		
		if ($results->num_rows > 0){
			while($row = $results->fetch_assoc()){
			 $first_run = TRUE;					
			 foreach (str_split($row["eeprom_file"]) as $single_char){
			     if ($single_char == ":" && $first_run == FALSE) {echo '<br>';}
			     echo $single_char;
			     $first_run=FALSE;
			 }
			}
		} else{ echo "Data not found."; }
		
		$conn->close();
?>
	</body>
</html>
