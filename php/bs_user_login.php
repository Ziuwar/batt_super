<?php 
session_start();

include 'bs_html_common.php';
include 'bs_db_ops.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $name = explode(".", $username);
    $password_sha = sha1($password);
    
    $conn = db_session();
    
    $sql_get_user = "SELECT uid, forename, surname, password FROM batt_super.users WHERE forename='" .$name[0]. "' AND surname='" .$name[1]."'";
    $query_result = $conn->query($sql_get_user);
    $result = $query_result->fetch_row();
    
    if ($password_sha == $result[3]) {        
        $_SESSION['userid'] = $username;
        $_SESSION['ident_fail'] = "";
        $_SESSION['login_fails'] = 0;
        $_SESSION['useruid'] = $result[0];
        
        
        header('Location: '. $_SESSION['login_return']);
    } else{
        $_SESSION['ident_fail'] = "Wrong password/username.";
        
        $_SESSION['login_fails'] = $_SESSION['login_fails'] + 1;
        
        header('Location: bs_user_ops.php');
    }
    $conn->close();

}

?>