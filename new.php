<?php
include("config.php");
$select_query = "select * from accesstoken where current is true";
$search_result = mysql_query($select_query);
echo $search_result;
if ($search_result) {
    if (mysql_num_rows($search_result) == '0') {
        echo 'This has not been activated by the administrator.';
//DBE001 ==>no current access token exists
   //      header("location: newtoken.php");
   //     exit();
    } else {
        $result = mysql_fetch_array($search_result);
        ?>
        <input type="hidden" size="20" id="authcode" name="authcode" value="<?php echo 'hjgfjh'.$result['token']; ?>"/>

        <?php
    }
} else {
    echo 'Application temporarily unavailable : DBE001';
//DBE001 ==>search didn't work for accesstoken
  //   header("location: newtoken.php");
  //  exit();
}
?>