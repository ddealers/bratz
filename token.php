<?php
require_once("src/facebook.php");

$config = array();
$config['appId'] = '446128772171362';
$config['secret'] = '6464e2ef8a34dbcb8c069f7fc8183050';

// cURL Load Function
function cURLget ($ch_url) {
  $ch = curl_init();
  curl_setopt($ch,CURLOPT_URL,$ch_url);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
  $ch_send = curl_exec($ch);
  curl_close($ch);
  return $ch_send;
};

$fb_token_get = cURLget("https://graph.facebook.com/oauth/access_token?grant_type=fb_exchange_token&client_id=446128772171362&client_secret=6464e2ef8a34dbcb8c069f7fc8183050&fb_exchange_token=CAAGVwHL9RmIBAOlHqgsziDOH0iGAC2YvW1HlArwPdKWLshjZC82VhaX799wFVmZAdZAtPXFKbDKKDj5SGqpoYsuvnFy789i8V6bn4qNhj8q2JzTr4iVxJLUaB2odZAGKtDDPBcEv2G72YVTfyvmm1Rzq8e7A8M7QVJz2C1uead5C9eJOhqZC78sBoCppqsaAZD");
$fb_token_params = null;
parse_str($fb_token_get,$fb_token_params);
echo "var token='" . $fb_token_params['access_token'] . "'";