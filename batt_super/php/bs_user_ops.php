<?php session_start(); ?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Battery Supervisory Login</title>
		<link rel="stylesheet" type="text/css" href="/css/batt_super_app.css">
		<link href='https://fonts.googleapis.com/css?family=Headland One' rel='stylesheet'>
	</head>
	<style>

	</style>
	<body>

<?php //include_once './php/batt_super.php';

    include 'bs_html_common.php';
    include 'bs_db_ops.php';

	//Notice the user if a field is not populated
    $username = '';
    $password_sha1 = $old_password_sha1 = 0;
	$username_err = $password_err = $old_password_err = "";
	$sql_result = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   
	    //Username check
		if (empty($_POST["username"])) {
		    $username_err = "*Username is required";
		} else {
				if (!preg_match("/^[a-zA-Z.]*$/",$_POST['username'])) {
				    $username_err = "*Only letters allowed";
				} else {
				    $username = $_POST['username'];
				}
		}
		
		//Old Password check
		if (empty($_POST["old_password"])) {
		    $old_password_err = "*Old password is required";
		} else {
		    $old_password_sha1 = sha1($_POST['old_password']);
		}

		//Password check
		if (empty($_POST["password"])) {
		    $password_err = "*Password is required";
		} else {
				$password_sha1 = sha1($_POST['password']);
		}
		
	}

	//The new user can only be added if all fields are populated by the user
	if(empty($username_err) && empty($password_err) && empty($old_password_err) && $username !== "") {
	    
	    $username_exp = explode(".", $username);
	     
	    $sql_pwd = "SELECT password from batt_super.users WHERE forename='$username_exp[0]' AND surname='$username_exp[1]'";   
	    $sql_update = "UPDATE batt_super.users SET password='$password_sha1' WHERE forename='$username_exp[0]' AND surname='$username_exp[1]'";
	    
		//Create the connection
		$conn = db_session();
		//Check connection
		if ($conn->connect_error) {
			die("Connection failed: " .$conn->connect_error);
		}

		//Compare the old password given with the one stored in the database
		$result = $conn->query($sql_pwd);
		$pwd_old = $result->fetch_row();
		
		echo $pwd_old[0].'---';
		echo $old_password_sha1.'---';
		echo $password_sha1;

		if($pwd_old[0] == $old_password_sha1){

    		$conn->query($sql_update);
    		
    		//echo "Rows affected: " .$conn->affected_rows. " Error state: " .$conn->sqlstate;
    		if ($conn->affected_rows == 1 && $conn->sqlstate == "00000"){
    		    $sql_result = "Password updated.";
    		    $username = "";
    		} else{
    		    if($conn->affected_rows == 0){
    		        $sql_result = "User not found or same PW as the old one.";
    		    } else {
    		        $sql_result = "A SQL error occured: " .$conn->sqlstate;
    		    } 
    		}
		} else {
		    $sql_result ="Old password wrong, try again.";
		    //$username = '';
		}	

		$conn->commit();
		$conn->close();
		
 	}
 ?>

	<?php navbar(); ?>

		<form class="login_user" action="bs_user_login.php" method="post">

			<label class="login_user_label" for="username">Username: <br></label>
			<input type = "text" name="username"><br>

			<label class="login_user_label" for="password">Password: <br></label>
			<input type = "password" name="password"><br>

			<input class = "user_button" type = "submit" name="user_login" value="Login"><br>
			
			<?php 
			//Only updates the URL to return after a successfull login or wenn no login was attempted at all in this session
			if(!isset($_SESSION['login_fails']) || $_SESSION['login_fails'] == 0){
			    
			    if(isset($_SERVER['HTTP_REFERER'])){
			    $_SESSION['login_return'] = htmlspecialchars($_SERVER['HTTP_REFERER'],ENT_QUOTES,'UTF-8');
			    
			    }
			}
			
			//Prompts the user to login when no loggin was attempted
			if (!isset($_SESSION['userid'])){
			    echo "Please log on.";
            
			//Successfull login -- not longer in use since the user gets redirected to the last URL
 			}else{ ?>
			      
			      <span class="result_ok">You are logged in as: <?php echo $_SESSION['userid'];?> </span><br> 
			      
			 <?php      
			}
			
			//Feedback to the user that the username or password is wrong
			if(isset($_SESSION['ident_fail'])){
			    ?> <span class="result_nok"> <?php echo "<br>".$_SESSION['ident_fail']; ?> </span><br> <?php
			    $_SESSION['ident_fail'] = "";
			}
			?>
			
		</form>

		<form class="login_user" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">

			<label class="login_user_label" for="username">Username: <br></label>
			<input type = "text" name="username" value="<?php echo $username;?>">
			<span class="error"> <?php echo $username_err;?></span><br>
			
			<label class="login_user_label" for="old_password">Old Password: <br></label>
			<input type = "password" name="old_password">
			<span class="error"> <?php echo $old_password_err;?></span><br>

			<label class="login_user_label" for="password">New Password: <br></label>
			<input type = "password" name="password">
			<span class="error"> <?php echo $password_err;?></span><br>

			<input class = "user_button" type = "submit" name="update_user" value="Update"><br> 		
			
			<?php if($sql_result == "Password updated."){ ?>
			<span class="result_ok"> <?php echo $sql_result;?></span><br> <?php }
			     else{ ?>
				<span class="result_nok"> <?php echo $sql_result;?></span><br> 
				<?php } ?>

		</form>
		
	<?php footer(); ?>
		
	</body>
</html>
