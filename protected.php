<?php
  
    session_start();
    
    if (! isset($_SESSION["logged - in"]))
    {
        echo "<br>login first <br><br>";
        header("refresh: 15; url = captcha.html");
        exit();
    }
    
    echo "<br>You are authenticated to be on protected.php";
    header("refresh: 2; url = services.html");
    exit();

?>

<br><br>
<a href="logout.php">Logout</a>
