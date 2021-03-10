<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
	$serverKey = $_POST['serverkey'];
	$target = $_POST['target'];
	$title = $_POST['title'];
	$body = $_POST['body'];
	$sendtoMethod = $_POST['sendto'];
	$clickAction = $_POST['click_action'];
	$data = $_POST['data'];
	$dataArray = json_decode($data);

    define( 'API_ACCESS_KEY', $serverKey );
    
    $tokenOrTopic = $target;
    if($sendtoMethod == "topic") {
    	$tokenOrTopic = "/topics/".$target;
    }


     $notificationMsg = array(
 		'body' 	=> $body,
		'title'	=> $title,
		'click_action' => $clickAction,
		// 'icon'	=> 'myicon',/*Default Icon*/
		// 'sound' => 'mySound'/*Default sound*/
	);

	$fields = array
			(
				'to'		=> $tokenOrTopic,
				'notification'	=> $notificationMsg,
				'data' => $dataArray
			);
	
	$headers = array
			(
				'Authorization: key=' . API_ACCESS_KEY,
				'Content-Type: application/json'
			);
	
	$ch = curl_init();
	curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
	curl_setopt( $ch,CURLOPT_POST, true );
	curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
	curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
	$result = curl_exec($ch );
	curl_close($ch);

	$dataResult = json_decode($result);
	$dataResponse = array();
	if(count($dataResult) > 0) {
		$dataResponse = array(
			'success' => true,
			'result' => $dataResult
		);
	} else {
		$dataResponse = array(
			'success' => false,
			'result' => strip_tags($result)
		);
	}

	echo json_encode($dataResponse);
}
