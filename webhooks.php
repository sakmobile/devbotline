<?php 

require "vendor/autoload.php";
// include "admin/config.php";
require_once('vendor/linecorp/line-bot-sdk/line-bot-sdk-tiny/LINEBotTiny.php');

$access_token = "F4ptH38wFe7cYDr7B+0L+GxHxFDEixnLmeNIF5d+NYzh2ne0QgFNTNeceqyz95dwe+TW7DaVgb/qWr4RC9+M+xLFgCGx/BRF8UVaaHU1T1zIv/OqFUWytzW3mzZGbh0MiBqcLb0IJMqj30vQHWt7fgdB04t89/1O/w1cDnyilFU=";

$content = file_get_contents('php://input');
$events = json_decode($content, true);


if (!is_null($events['events'])) {
	foreach ($events['events'] as $event) {
	
		if ($event['type'] == 'message' && $event['message']['type'] == 'text') {
			
			error_log($event['message']['text']);
			$text = $event['message']['text'];
			$replyToken = $event['replyToken'];
			
			//setFlex($text,$replyToken,$access_token);
			if($text == "วีรชัย"){
				$data_podt = "{\"birthday\":\"25351227\",\"cid\":\"1341500202156\",\"mobile\":\"0991013326\",\"page\":\"cvda002\"}";
				send_data($data_podt,$access_token,$replyToken);
						// $messages = '
						// 		{
						// 			"type": "text",
						// 			"text": "วีรชัย",
						// 			"align": "center"
						// 		}
						// 		';
								//sentToLine($replyToken , $access_token  , $messages );
			}else if($text == "รุ่งทิวา"){
				$message = '
				{
					"type": "text",
					"text": "วีรชัย",
					"align": "center"
				}
				';
			return $message;
			}
			
		}
	}
}



// function setFlex( $text,$replyToken,$access_token){
// 	if($text == "วีรชัย"){
// 		$data_podt = "{\"birthday\":\"25351227\",\"cid\":\"1341500202156\",\"mobile\":\"0991013326\",\"page\":\"cvda002\"}";
// 		send_data($data_podt,$access_token,$replyToken);
// 				// $message = '
// 				// 		{
// 				// 			"type": "text",
// 				// 			"text": "วีรชัย",
// 				// 			"align": "center"
// 				// 		}
// 				// 		';
// 				// 	return $message;
// 	}else if($text == "รุ่งทิวา"){
// 		$message = '
// 		{
// 			"type": "text",
// 			"text": "วีรชัย",
// 			"align": "center"
// 		}
// 		';
// 	return $message;
// 	}
// }


function send_data( $data_podt, $replyToken,$access_token){
	
	$url = "https://appealcovid19.xn--12cl1ck0bl6hdu9iyb9bp.com/appeal-web/api/appeal-api/personal-info/verify";
	
	$json = $data_podt;
	
	$ret = Curl($url, $json, $http_status);
	
	
	
	// Convert JSON string to Array
	$someArray = json_decode($ret, true);
print_r($someArray['data']['dataRegis']['payment']['paymentHistory'][1]['effDate']);  
$res = $someArray['data']['dataRegis']['payment']['paymentHistory'][1];      // Dump all data of the Array
print_r($res);
if($res  == ""){
  echo "ไม่พบ";
}else{
	print_r($someArray['data']['dataRegis']['payment']['paymentHistory'][1]['effDate']);
	$date = $someArray['data']['dataRegis']['payment']['paymentHistory'][1]['effDate'];
	$messages = '
		{
			"type": "text",
			"text": "โอนวันที่ "'.$date.',
			"align": "center"
		}
		';
	sentToLine($replyToken , $access_token  , $messages );
   }
}

function Curl($url, $post_data, &$http_status, &$header = null) {

	$ch=curl_init();
	// user credencial
	
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);

	// post_data
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

	if (!is_null($header)) {
		curl_setopt($ch, CURLOPT_HEADER, true);
	}
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));

	curl_setopt($ch, CURLOPT_VERBOSE, true);
	
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

	$response = curl_exec($ch);
	
	  
	$body = null;
	// error
	if (!$response) {
		$body = curl_error($ch);
		// HostNotFound, No route to Host, etc  Network related error
		$http_status = -1;
	   
	} else {
	   //parsing http status code
		$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if (!is_null($header)) {
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

			$header = substr($response, 0, $header_size);
			$body = substr($response, $header_size);
		} else {
			$body = $response;
		}
	}

	curl_close($ch);

	return $body;
}


function sentToLine($replyToken , $access_token  , $messages ){
	error_log("send");
	$url = 'https://api.line.me/v2/bot/message/reply';
	
	$data = '{
		"replyToken" : "'. $replyToken .'" ,
		"messages" : ['. $messages .']
	}';
	$post = $data;

	error_log($post);
	$headers = array('Content-Type: application/json', 'Authorization: Bearer ' . $access_token);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	$result = curl_exec($ch);
	curl_close($ch);
	echo $result . "\r\n";
	error_log($result);
	error_log("send ok");
}