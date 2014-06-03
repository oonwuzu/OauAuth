<?php

include("config.php");


/*   $fields = array(
  "pharmacyid" => urlencode($pharmacyid),
  "member_id" => urlencode($member_id),
  );
 */
$startlimit = 0;
$endlimit = 500;
echo 'Start';
do {
    $search_data = array();
    $select_query = "select auc.id,auc.emailaddress, sd.matricno,sd.password from authcode auc join studentdetails sd ON auc.emailaddress = sd.emailaddress where validated = true order by auc.id limit " . $startlimit . ',' . $endlimit;
    $search_result = mysql_query($select_query);
    echo $search_result;
    if ($search_result) {
        $search_array = mysql_fetch_array($search_result);
        $errors1 = array_filter($search_array);

        echo '===================================+++++++++++++++++++++++++++++=========================' . $select_query . "<br />\r\n";
        //  foreach ($search_array as $search_data) {
        while ($search_data = mysql_fetch_assoc($search_result)) {
            // while ($search_data = $search_result->fetch_assoc()) {

            $action = "Y";
            $matricno = $search_data['matricno'];
            $password = $search_data['password'];
            $emailaddress = $search_data['emailaddress'];
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


            echo '||' . $search_data['id'] . '||' . $urlStringData . "<br />\r\n";



            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); # timeout after 10 seconds, you can increase it
            curl_setopt($ch, CURLOPT_URL, $urlStringData); #set the url and get string together
            $result = curl_exec($ch);
            curl_close($ch);
        }
        $startlimit += 500;
    }
} while (!empty($errors1));
?>
