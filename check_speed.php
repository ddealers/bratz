<?php
$times = Array(microtime(true));
$f = fsockopen("google.com",80);
$times[] = microtime(true);
$data = "POST / HTTP/1.0\r\n"
       ."Host: google.com\r\n"
       ."\r\n"
       .str_repeat("a",1000000); // send one megabyte of data
$sent = strlen($data);
fputs($f,$data);
$firstpacket = true;
$return = 0;
while(!feof($f)) {
    $return += strlen(fgets($f));
    if( $firstpacket) {
        $firstpacket = false;
        $times[] = microtime(true);
    }
}
$times[] = microtime(true);
fclose($f);
echo "RESULTS:\n"
    ."Connection: ".(($times[1]-$times[0])*1000)."ms\n"
    ."Upload: ".number_format($sent)." bytes in ".(($times[2]-$times[1]))."s (".($sent/($times[2]-$times[1])/1024)."kb/s)\n"
    ."Download: ".number_format($return)." bytes in ".(($times[3]-$times[2]))."s (".($return/($times[3]-$times[2])/1024)."kb/s)\n";


?>