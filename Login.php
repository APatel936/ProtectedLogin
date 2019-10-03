<?php
    include ('account.php');
    include ('funcDef.php');
    
    session_start();
    
    $ucid = $_GET["ucid"];
    $pass = $_GET["pass"];
    $guess = $_GET["guess"];
    $delay = 15;
    
    $db = mysqli_connect($hostname, $username, $password, $project);
    $cap = $_SESSION["captcha"];
    
    //login.php failure connections - redirect to html form
    if(($guess != $cap) || !authenticate($ucid,$pass,$db))
    { //guess wrong or auth wrong
        echo "Bad guess or Bad creds<br>";
        header ("refresh: $delay; url = captcha.html");
        exit();
    }
    //login.php success
    $_SESSION ["logged - in"] = true;
    $_SESSION ["ucid"] = $ucid;
    
    echo "Being redirected to protected service page.<br>";
    header("refresh: $delay; url = protected.php");
    exit();
    
    
    
    
?>
