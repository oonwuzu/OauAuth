<!DOCTYPE html>
<?php
include("header.php");
include("config.php");
extract($_POST);
if (array_key_exists('regno', $GLOBALS) && array_key_exists('regname', $GLOBALS) && !empty($regno) && !empty($regname)) {
    ?><div id="abw">
        <form id="login" action="emailvalidation.php" method="post">
            <div class="verification_div">
                <input type="text" size="20" disabled="disabled" id="emailaddress" name="emailaddress" value="<?php echo $regno?>"/>
                <input type="button" id="snazzy_button" name="snazzy_button" title="Enter a valid registration number" value="Verify Reg. Number"/>
                <input type="hidden" name="password" id="password" />
                <input type="hidden" name="email" id="email" />
                <input type="hidden" id="matricno" name="matricno"/>
                <input type="hidden" id="fullname" name="fullname"/>
            </div>
            <div class='message-display'>
                <strong>Instructions:</strong>
                <p><h3>IT IS RECOMMENDED THAT YOU USE A COMPUTER FOR THIS PROCESS AND NOT A MOBILE PHONE.</h3></p>
                <p>Ensure you are signed in as a valid student to be able to access this service.</p>
                <p>This is a one step process. You are not expected to repeat this process except when the administrator deems it.</p>
                <p>Kindly note your email address and password as it is available just once on the next screen.</p>
                <p>It is recommended that you <strong>COPY</strong> your email address and password to the google login screen instead of typing it.</p>
            </div>

        </form>
    </div><?php
} else {
    ?>
    <div id="abw">
        <div class='message-display'>
            <strong>THIS PAGE IS ONLY AVAILABLE WHEN ACCESSED VIA THE PORTAL.</strong>
        </div>
    </div>
    <?php
}
session_start();
$select_query = "select * from accesstoken where current is true";
$search_result = mysql_query($select_query);
if ($search_result) {
    if (mysql_num_rows($search_result) == '0') {
        $_SESSION['errormessage'] = 'This has not been activated by the administrator.';
//DBE001 ==>no current access token exists
        echo $_SESSION['errormessage'];
        header("location: newtoken.php");
        exit();
    } else {
        $result = mysql_fetch_array($search_result);
        ?>
        <input type="hidden" size="20" id="authcode" name="authcode" value="<?php echo $result['token']; ?>"/>

        <?php
    }
} else {
    $_SESSION['errormessage'] = 'Application temporarily unavailable : DBE001';
//DBE001 ==>search didn't work for accesstoken
    echo $_SESSION['errormessage'];
    header("location: newtoken.php");
    exit();
}
if (!isset($_SESSION['emailaddress']) && empty($_SESSION['emailaddress'])) {
    $_SESSION['emailaddress'] = '';
    $_SESSION['errormessage'] = '';
}
$errormessage = $_SESSION['errormessage'];
?>
<div class="<?php if ($errormessage != '') { ?>message error<?php } ?>"/>
<?php
echo $errormessage;
?>
</div><?php
$_SESSION['emailaddress'] = '';
$_SESSION['errormessage'] = '';
$_SESSION['tries_count'] = '3';
include("footer.php");
?>
<script src="js/jquery.js"></script>
<script type="text/javascript">
    // Shorthand for $( document ).ready()
    $(function() {
        $("#snazzy_button").click(function() {
            var matricno = $("#emailaddress").val();
            var suffixalias = '@Students.oauife.edu.ng';
            var mainurl = 'https://accounts.google.com/o/oauth2/auth?response_type=code&scope=https://www.googleapis.com/auth/admin.directory.user&redirect_uri=http://www.swifta.co/oauauth/&client_id=872938699767.apps.googleusercontent.com&state=initial&approval_prompt=force&login_hint=googleapps@oauife.edu.ng'
            //   if (!isValidEmailAddress(emailaddress)) {
            //     alert("Enter a valid email address.");
            if (!isValidRegistrationNumber(matricno)) {
                alert("Enter a valid registration number.");
            } else {
                var emailaddress = matricno.replace(/[^a-zA-Z 0-9]+/g, '');
                emailaddress = emailaddress + suffixalias;
                var googleurl = 'https://www.googleapis.com/admin/directory/v1/users/' + emailaddress;
                var dataString = '&matricno=' + matricno;
                $.ajax({
                    type: "POST",
                    url: "localverifier.php",
                    data: dataString,
                    cache: false,
                }).success(function(data, text) {
                    //        alert(data);
                    var result = data.replace(/^\s+|\s+$/g, '');
                    //    alert(data);
                    data = $.parseJSON(result);
                    if (result == 'false') {
                        alert("Enter a valid registration number.");
                    } else {
                        //      $("email").val();
                        $("#email").val(data.emailaddress);
                        $("#password").val(data.password);
                        $("#fullname").val(data.fullname);
                        $("#matricno").val(matricno);
                        $("#login").submit();
                        //        alert('this email >'+data.emailaddress+ 'is not fair password >'+data.password);
                    }
                    //       $("#login").submit();

                }).error(function(request, status, error) {
                    var strvalu = JSON.stringify(request);
                    alert("Could not validate registration number. Try again later.");
                });

            }
        });
    });
    function isValidEmailAddress(emailAddress) {
        var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
        return pattern.test(emailAddress);
    }
    function isValidRegistrationNumber(emailAddress) {
        var pattern = new RegExp("^\\w{3}/\\d{4}/\\d{3}$", "i");
        return pattern.test(emailAddress);
    }
    ;
</script>