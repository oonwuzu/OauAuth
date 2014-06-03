<?php
include("header.php");
session_start();
include("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $otcode = addslashes($_POST['otcode']);
    $tries_count = addslashes($_POST['tries_count']);
    $matricno = addslashes($_POST['matricno']);
    $password = addslashes($_POST['password']);

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
                $_SESSION['errormessage'] = 'Regeneratae a new verification code by loging in through the portal again.';
                $_SESSION['emailaddress'] = '';
                header("location: newtoken.php");
            } else {

                $_SESSION['errormessage'] = 'Invalid code entered. ' . $_SESSION['tries_count'] . ' tries left.';
                header("location: emailvalidation.php");
            }
            exit();
        } else {
            mysql_query($update_query);
            //curl implementation http://192.168.1.14/Pharmarays/pharma-public/index.php/mobile/mobile_controller/retrieve_drugs
            $pharmacyid = '1';
            $member_id = '6';


            /*   $fields = array(
              "pharmacyid" => urlencode($pharmacyid),
              "member_id" => urlencode($member_id),
              );
             */
            $action = "Y";
            $fields = array(
                "studid" => urlencode($matricno),
                "check" => urlencode($password),
                "action" => urlencode($action),
                "email" => urlencode($emailaddress),
            );
            // $fields = array();
            //   $url = ("http://192.168.1.14/Pharmarays/pharma-public/index.php/mobile/mobile_controller/retrieve_drugs");

            $url = ("http://eportal.oauife.edu.ng/googleoaucclink.php");

            $fields_string = "";

            foreach ($fields as $key => $value) {
                $fields_string[] = $key . '=' . $value . '&';
            }
            $urlStringData = $url . '?' . implode('&', $fields_string);


            // echo $urlStringData;


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
            curl_setopt($ch, CURLOPT_URL, $urlStringData); #set the url and get string together

            $result = curl_exec($ch);

            //echo($result);
            if (strlen($result) > 0) {
                if (strpos(strtolower($result), 'error') !== FALSE) {
                    $_SESSION['errormessage'] = "An error has occurred while updating your details on OAU portal. Please try again later." . $fields_string;
                }
                else
                    $_SESSION['errormessage'] = '';
            } else {
                $_SESSION['errormessage'] = '';
            }
            curl_close($ch);
//
            $_SESSION['emailaddress'] = '';
        }
    }
}
?><div id="abw">
    <div class="verification_div"><img id="" src="images/success.png" alt="Success image"/><span>Verification Successful!</span></div>
   <p><a href="http://eportal.oauife.edu.ng" style="font-size: 18px;">Click to continue...</a></p> <?php
    if (!isset($_SESSION['emailaddress']) && empty($_SESSION['emailaddress'])) {
        $_SESSION['emailaddress'] = '';
        $_SESSION['errormessage'] = '';
    }
    $errormessage = $_SESSION['errormessage'];
    ?>
    <div class="<?php if ($errormessage != '') { ?>message-display<?php } ?>"/><strong>
        <?php
        echo $errormessage;
        ?></strong>
    <?php
    $_SESSION['errormessage'] = '';
    ?>
</div>
</div>
<?php
include("footer.php");
?>
