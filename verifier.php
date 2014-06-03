<?php
extract($_GET);

$ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $googleurl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch,CURLOPT_HEADER,false);
    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
    $result = curl_exec($ch);
    echo $result;
    $output = json_decode($result);
    $info = curl_getinfo($ch);

    curl_close($ch);
    
?>
