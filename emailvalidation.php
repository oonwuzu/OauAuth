<?php
include("header.php");
session_start();
if ($_SESSION['tries_count'] == '3') {
    $_SESSION['emailaddress'] = $_POST['email'];
}
?><div id="abw">
    <form id="login" action="validatecode.php" method="post">
        <div class="verification_div"> 
            <input type="hidden" name="tries_count" id="tries_count" value="<?php echo $_SESSION['tries_count'] ?>"/>
            <input type="hidden" name="emailaddress" id="emailaddress" value="<?php echo $_SESSION['emailaddress'] ?>"/>
            <input type="hidden" name="matricno" id="matricno" value="<?php echo $_POST['matricno'] ?>"/>
            <input type="hidden" name="password" id="password" value="<?php echo $_POST['password'] ?>"/>

            <input type="text" size="40" id="otcode" name="otcode"/>
            <input type="submit" id="snazzy_button" name="snazzy_button" value="Verify Code"/>
        </div>
    </form>
    <div class='message-display'/>

    <?php
    echo $_SESSION['errormessage'];
    include("config.php");
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
// username and password sent from Form
        $emailaddress = addslashes($_POST['email']);
        $matricno = addslashes($_POST['matricno']);
        $password = addslashes($_POST['password']);
        $processstatus = 'success';
        $fullname = addslashes($_POST['fullname']);
//   echo $emailaddress;
        $send_mail = true;
        $otcode = md5(uniqid(rand(), true));
        $datecode = date("Y-m-d H:i:s");
        $insert_query = "insert into authcode values(DEFAULT,'" . $emailaddress . "','" . $otcode . "','" . $datecode . "',false)";
        $select_query = "select * from authcode where emailaddress = '" . $emailaddress . "'";
        $update_query = "update authcode set otcode = '" . $otcode . "' where emailaddress = '" . $emailaddress . "'";
        $email_select_query = "select sd.emailaddress as emailaddress from studentdetails sd where lower(sd.matricno) = '" . strtolower($matricno) . "'  and instr(emailaddress,'.') > 2";
        $email_search_result = mysql_query($email_select_query);

        $search_result = mysql_query($select_query);
        $search_array = mysql_fetch_array($search_result);
        if ($search_result) {
            if (mysql_num_rows($search_result) == '0') {
                $insert_result = mysql_query($insert_query);
                if ($insert_query) {
                    $send_mail = true;
                } else {
                    $_SESSION['errormessage'] = 'Application temporarily unavailable : DBE002';
//DBE002 ==>insert didn't work
                    echo $_SESSION['errormessage'];
                    header("location: newtoken.php");
                    exit();
                }
            } else {
                if($search_array['validated'] == '0'){
                $update_result = mysql_query($update_query);
                if ($update_result) {
                    $send_mail = true;
                } else {
                    $_SESSION['errormessage'] = 'Application temporarily unavailable : DBE003';
//DBE003 ==>update didn't work
                    echo $_SESSION['errormessage'];
                    header("location: newtoken.php");
                    exit();
                }}else{
                    $_SESSION['errormessage'] = 'Student email validated already';
//DBE003 ==>update didn't work
                    echo $_SESSION['errormessage'];
                    header("location: newtoken.php");
                    exit();
                }
            }
        } else {
            $_SESSION['errormessage'] = 'Application temporarily unavailable : DBE001';
//DBE001 ==>search didn't work
            echo $_SESSION['errormessage'];
            header("location: newtoken.php");
            exit();
        }
        if ($send_mail == true) {
              $to = $emailaddress;
            //$to = 'testad@student.oauife.edu.ng';
           // $to = 'princeyekaso@gmail.com';
            $subjet = "Authentication Code for Login - " . strtoupper($matricno);
            $message = "Hello " . $fullname . ",\n\n\nThe access code generated on " . $datecode . " is :\n\n" . $otcode . "\n\n\n Br,\n Oau Team.";
            // $message = "Hello";
            $from = "info@student.oauife.edu.ng";
            $headers = "From:" . $from;
            if (mail($to, $subjet, $message, $headers)) {
                //        echo 'Email sent succesfully';
                echo 'A code has been sent to your email address. Kindly logon with these details <br/>' .
                '<strong>Matriculation number:</strong>&nbsp;&nbsp;' . $matricno . '<br/>' .
                '<strong>Email address:</strong>&nbsp;&nbsp;' . $emailaddress . '<br/>' .
                '<strong>Password:</strong>&nbsp;&nbsp;' . $password . '<br/>' .
                '<strong>Aliases:</strong><p style="padding-left:70px">';
                while ($email_search_resul_array = mysql_fetch_array($email_search_result)) {
                    echo $email_search_resul_array[0] . '<br/>';
                } echo '</p><br/>
 
 
<strong>LOGIN INSTRUCTIONS</strong><br/>
 
<br/>1.    Click <a href="http://Google.com/a/student.oauife.edu.ng" target="_blank">HERE</a> to access your Google apps account with the details above.
<br/>2.    On the new Google Apps login screen enter  the first part of your email address e.g. (ade.ajanaku)
<br/>3.     Enter your password (note: passwords are case sensitive i.e." a" is different from "A")
<br/>4.    You will be prompted to change your password  (You will not be able to gain access to your Google Apps  account until you have changed your password!)
<br/>5.    <strong>COPY</strong> the verification code from your mail and paste it in the field above.
<br/>6.     Click verify code to continue
 
<br/>';
            } else {
                die('failure: Email was not sent');
            }

            //     echo ' the code is...' . $otcode;
        }
    }
    ?>
</div>
</div>

<?php
include("footer.php")
?>  