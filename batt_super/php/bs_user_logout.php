<?php

session_start();
session_destroy();

header('location: bs_view_data.php');

?>