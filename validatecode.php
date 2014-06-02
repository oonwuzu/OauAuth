<?php
include("header.php");
session_start();
include("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otcode = addslashes($_POST['otcode']);
    $tries_count = addslashes($_POST['tries_count']);
    $emailaddress = addslashes($_POST['emailaddress']);
    if ($emailaddress != '') {
        $_SESSION['emailaddress'] = $emailaddress;
    }
// username and password sent from Form
    $select_query = "select * from authcode where otcode = '" . $otcode . "' and emailaddress = '" . $_SESSION['emailaddress'] . "'";
    $update_query = "update authcode set validated = true where otcode = '" . $otcode . "' and emailaddress = '" . $_SESSION['emailaddress'] . "'";
    $search_result = mysql_query($select_query);
    if ($search_result) {
        if (mysql_num_rows($search_result) == '0') {
            $_SESSION['tries_count'] = ($tries_count - 1);
//DBE002 ==>insert didn't work
            if ($_SESSION['tries_count'] == '0') {
                $_SESSION['errormessage'] = 'Regeneratae a new verification code';
                $_SESSION['emailaddress'] = '';
                header("location: newtoken.php");
            } else {

                $_SESSION['errormessage'] = 'Invalid code entered. ' . $_SESSION['tries_count'] . ' tries left.';
                header("location: emailvalidation.php");
            }
            exit();
        } else {
            mysql_query($update_query);
            $_SESSION['errormessage'] = '';
            $_SESSION['emailaddress'] = '';
        }
    }
}
?><div id="abw">
    <div class="verification_div"><img id="" src="images/success.png" alt="Success image"/><span>Successful!</span></div>
</div>
<?php
include("footer.php");
?>
