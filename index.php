
<!DOCTYPE html>
<?php
$mainurl = 'https://accounts.google.com/o/oauth2/auth?response_type=code&scope=https://www.googleapis.com/auth/admin.directory.user&redirect_uri=http://www.swifta.co/oauauth/&client_id=178391919605-25jnntdepcq0tmlrgoqmuehb50u1q8br.apps.googleusercontent.com&state=initial&approval_prompt=force&login_hint=googleapps@oauife.edu.ng';
if (!isset($_GET['code'])) {
    header("Location:" . $mainurl);
} else {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://accounts.google.com/o/oauth2/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    $data = array(
        'code' => $_GET['code'],
        'client_id' => '178391919605-25jnntdepcq0tmlrgoqmuehb50u1q8br.apps.googleusercontent.com',
        'client_secret' => 'ggej6hFojyoUbwilhJtpca__',
        'redirect_uri' => 'http://www.swifta.co/oauauth/',
        'grant_type' => 'authorization_code'
    );

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = json_decode(curl_exec($ch));
    $info = curl_getinfo($ch);

    curl_close($ch);
    include("config.php");
    $datecode = date("Y-m-d H:i:s");
    $insert_query = "insert into accesstoken values(DEFAULT,'" . $output->{'access_token'} . "','" . $output->{'expires_in'} . "','" . $datecode . "',true)";
    $insert_result = mysql_query($insert_query);

    if ($insert_query) {
        $insert_id = mysql_insert_id();
        $update_query = "update accesstoken set current = false where id !=" . $insert_id;
        mysql_query($update_query);
        echo 'Successful';
    } else {
        $_SESSION['errormessage'] = 'Application temporarily unavailable : DBE002';
//DBE002 ==>insert didn't work on access token
        echo $_SESSION['errormessage'];
        header("location: index.php");
        exit();
    }
}
?>

