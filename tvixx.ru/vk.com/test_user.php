<?php

$token = "7b9e42abd931169822f31efdc346fa9a5b9d154ba93afebcc56401a49fed4ae554aca2dac3f59ffc89da81";
$data = json_decode(file_get_contents("php://input"));

function send($text, $peer_id) {
    global $token;

    $reg_mes = [
        'message' => $text,
        'access_token' => $token,
        'v' => '5.80',
        'random_id' => rand(1111, 9999) . rand(1111, 9999) . rand(1111, 9999) . rand(1111, 9999) . rand(1111, 9999),
        'peer_id' => $peer_id
    ];
    $get = http_build_query($reg_mes);
    file_get_contents("https://api.vk.com/method/messages.send?" . $get);
}
function setStatus($id){
	global $token;
	
	$data = json_decode(file_get_contents("https://api.vk.com/method/status.get?user_id=".$id."&v=5.100&access_token=".$token));
	echo $data;
	$req_mes = [
	'text' => "test",
	'access_token' => $token,
	'v' => '5.100'
	];
	$get = http_build_query($req_mes);
	file_get_contents("https://api.vk.com/method/status.set?".$get);
	
}
if(isset($_GET['message'])){
	$arrr = array(277318898, 100785305, 235508674, 515483259, 83216598, 498446909);
	for($i = 0; $i < count($arrr); $i++){
	    send("test bot php",$arrr[$i]);
	}
}
if(isset($_GET['status']) && isset($_GET['id'])){
	setStatus($_GET['id']);
}
if (isset($data->type)) {
    switch ($data->type) {
        case 'message_new':
            $peer_id = $data->object->peer_id ?: $data->object->from_id;
            $userID = $data->object->from_id;
            $userInfo = json_decode(file_get_contents("https://api.vk.com/method/users.get?user_ids=" . $userID . "&v=5.80&access_token=" . $token));
            
            $userName = $userInfo->response[0]->first_name;
            $text = $data->object->text;
            if($text == "test"){
            	send("tesr2",$peer_id);
            }
            header("HTTP/1.1 200 OK");
            echo 'ok';
            break;
     }
}
            
            
            
            
            
            