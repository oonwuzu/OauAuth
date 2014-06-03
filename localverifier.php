
<?php

include("config.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $matricno = addslashes($_POST['matricno']);
    $select_query = "select sd.emailaddress, sd.password, concat(sd.firstname,' ',sd.lastname) as fullname from studentdetails sd where instr(emailaddress,'.') < instr(emailaddress,'@') and emailaddress like '%@student.oauife.edu.ng%' and lower(matricno) = '" . $matricno . "'";

     $search_result = mysql_query($select_query);
      $search_resul_array = mysql_fetch_array($search_result);
      if ($search_result) {
      if (mysql_num_rows($search_result) == '0') {
      echo 'false';
      } else {
      echo json_encode($search_resul_array);
      }
      } else {
      echo 'false';
      } 
}
?>