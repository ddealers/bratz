<?php
include_once('data.php');

$name = uniqid('img') . '.jpg';
$data = file_get_contents('php://input');
$filtered = substr($data, strpos( $data, ',' ) + 1 );
$decoded = base64_decode( $filtered );

$fp = fopen( 'uploads/' . $name, 'wb' );
fwrite( $fp, $decoded );
fclose( $fp );

$response = array('success'=>true, 'data'=>$name);	

header("Content-type: application/json");
$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;
$json = json_encode( $response );
print $jsonp_callback ? "$jsonp_callback($json)" : $json;