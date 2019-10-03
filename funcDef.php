<?php
    
    function authenticate ( $ucid, $pass, $db )
    {
        $s =  "select * from users where ucid='$ucid' and pass='$pass'" ;
        
        //echo "<br>SQL Credentials select statement is $s<br>";
        
        ($t = mysqli_query ($db, $s)) or die (mysqli_error($db)) ; //get table or die
        $num = mysqli_num_rows($t) ;
        
        if ($num == 0)
        {
            //not valid
            return false;
        }
        else
        {
            return true;
        }
    }
    
    function display ($ucid, $account, $box, $number, $db) //displays transactions for ucid and account
    {
        
        $s1 = "select * from users where ucid = '$ucid'";
        $s2 = "select * from accounts where ucid = '$ucid'";
        
        if (! isset ($box)) //box is unchecked
        {
            $s3 = "select * from transactions where ucid='$ucid' and account='$account'";
        }
        
        if (isset ($box)) //box is checked
        {
            $s3 = "select * from transactions where ucid='$ucid'";
        }
        
        ($t1 = mysqli_query ($db, $s1)) or die (mysqli_error($db)) ; //get users
        ($t2 = mysqli_query ($db, $s2)) or die (mysqli_error($db)) ; //get acc
        ($t3 = mysqli_query ($db, $s3)) or die (mysqli_error($db)) ; //get trans
        
        $num2 = mysqli_num_rows($t2) ;
        print "<br>There were $num2 rows retrieved from the accounts table<br>";
        
        $num3 = mysqli_num_rows($t3) ;
        print "<br>There were $num3 rows retrieved from the transactions table<br><br>";
        
        print "<br>--------------------------------------------------------<br>";
        
        
        echo "<br><br><br>";
        echo "accounts follow.<br>";
        echo "<table border = 2 width = 30%>";
        echo "<tr>";
        echo "<th>account</th>";
        echo "<th>balance</th>";
        echo "</tr>";
        while ($row2 = mysqli_fetch_array($t2, MYSQLI_ASSOC))
        {
            $account   =    $row2["account"];
            $balance    =   $row2["balance"] ;
            echo "<tr>";
            echo "<td>$account</td>";
            echo "<td>$$balance</td>";
            echo "</tr>";
        };
        echo "</table>";
        
        
        echo "<br><br><br>";
        echo "transactions follow.<br>";
        echo "<table border = 2 width = 30%>";
        echo "<tr>";
        echo "<th>account</th>";
        echo "<th>amount</th>";
        echo "<th>timestamp</th>";
        echo "</tr>";
        while ( $row3 = mysqli_fetch_array ($t3, MYSQLI_ASSOC))
        {
            $account   =    $row3["account"];
            $amount    =    $row3["amount"] ;
            $timestamp =    $row3["timestamp"] ;
            echo "<tr>";
            echo "<td>$account</td>";
            echo "<td>$amount</td>";
            echo "<td>$timestamp</td>";
            echo "</tr>";
        };
        echo "</table>";
        
    }
    
    
    
    
    function get($fieldname, $db)  #better version of _GET function
    {
        if (!isset( $_GET [$fieldname] ) || $_GET [$fieldname] == "")
        {
            if ($fieldname == "number")
            {
                $v = 3;
            }
            if ($fieldname == "amount")
            {
                $v = 0.00;
            }
            
            
            
            echo "<br><br>The value of $fieldname is either NULL or empty.";
            $v = NULL;
            return $v;
        }
        
        
        $v = $_GET[$fieldname];
        $v = trim ($v);  #removes white spaces
        $v = mysqli_real_escape_string ($db, $v); #cleans data
        
        echo "The $fieldname is: $v<br>";
        return $v;
    }
    
    
    
    function transact ($ucid, $account, $amount, &$results, $db)
    {
        //1. check for overdraft w/ select
        $s1 = "select * from accounts where ucid = '$ucid' and account = '$account' and balance + $amount >= 0.00";
        
        $t = mysqli_query ($db, $s1) or die (mysqli_error($db));
        $num = mysqli_num_rows($t);
        
        if ($num == 0) //no rows
        {
            $results.= "<br>Overdraft<br>";  //put in some html to make it look good
            return ;
        }
        $results.= "No Overdraft";
        
        
        //2. insert new transaction into transaction table
        $s2 = "insert into transactions value ('$ucid', '$account', '$amount', NOW(), 'N')";  //overwrite $s   and 'N' is the value for Mail
        
        $results.= "<br>Insert is: $s2";
        $t2 = mysqli_query ($db, $s2) or die (mysqli_error($db));
        
        
        //3. change balance in account table (for that ucid and that account)
        $s3 = "update accounts Set balance = balance + '$amount' where ucid='$ucid' and account='$account'";  
        
        $results.= "<br>Update is: $s3";
        $t3 = mysqli_query ($db, $s3) or die (mysqli_error($db));
        
        display ($ucid, $account, NULL, 5, $db);  //null means box is off
        //need to go into display, and make those results .= too
        
    }
    
    
    
    
?>

