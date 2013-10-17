<?php
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

include_once('data.php');

if( isset($_REQUEST['action']) && $_REQUEST['action'] != NULL ){
	switch($_REQUEST['action']){
		case 'login':
		$fields = array(
			array('name'=>'nombre', 'value'=>$_REQUEST['nombre'], 'rules'=>'required'),
			array('name'=>'mail', 'value'=>$_REQUEST['mail'], 'rules'=>'required,email'),
			array('name'=>'ticket', 'value'=>$_REQUEST['ticket'], 'rules'=>'required,ticket'),
		);
		Validator::validate($fields);
		if(Validator::$valid){
			$user = new User();
			$data = $user->create( $_REQUEST['nombre'], $_REQUEST['mail'], $_REQUEST['ticket'] );
			//$data = 1;
		}
		break;
		case 'save':
		$fields = array(
			array('name'=>'id', 'value'=>$_REQUEST['id'], 'rules'=>'required'),
			array('name'=>'message', 'value'=>$_REQUEST['message'], 'rules'=>'required'),
			array('name'=>'image', 'value'=>$_REQUEST['image'], 'rules'=>'required'),
		);
		Validator::validate($fields);
		if(Validator::$valid){
			$brat = new Bratz();
			$data = $brat->create( $_REQUEST['id'], $_REQUEST['message'], $_REQUEST['image'] );
		}
		break;
	}
	if(!Validator::$valid){
		$response = array('success'=>false, 'errors'=>Validator::$errors);
	}else{
		$response = array('success'=>true, 'data'=>$data);	
	}
}else{
	$response = array('success'=>false);
}

header("Content-type: application/json");
$jsonp_callback = isset($_GET['callback']) ? $_GET['callback'] : null;
$json = json_encode( $response );
print $jsonp_callback ? "$jsonp_callback($json)" : $json;