<?php

// hearders that will allow application to receive a
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

//recursive function for changing json data to xml
function arrayToXml($array, $parentkey="", $xml = false){

	// recursive function terminator
	if($xml === false){
		$xml = new SimpleXMLElement('<request/>');
	}
 
	foreach($array as $key => $value){
		// if value is an array, call the arrayToXml function again else append it as a child.
		if(is_array($value)){
			arrayToXml($value, is_numeric((string) $key) ? ("n".$key) : $key, $xml->addChild(is_numeric((string) $key) ? $parentkey : $key));
		} else {
			$xml->addAttribute(is_numeric((string) $key) ? ("n".$key) : $key, $value);
		}
	}
 
	return $xml->asXML();
 }

 // get post data
$data = json_decode(file_get_contents('php://input'),true);

// check if all required fields are available
if(isset($data['from_msisdn']) && isset($data['to_msisdn']) && isset($data['message'])){
	// display error message for any first missing required field

	try{

		// validate input data types
		if(!is_int($data['from_msisdn']))
			response(400,'Invalid integer input');
		if(!is_string($data['message']))
			response(400,'Invalid string input');
		if(!is_int($data['to_msisdn']))
			response(400,'Invalid integer input');

		// if data has more than 4 inputs, that means extra fields are in the request as well
		if(count($data) > 4){
			// check if field_map variable is available
			if(!isset($data['field_map']))
				response(400,"field_map does not exist");

			// loop through all data to get extra fields
			foreach($data as $key => $value){
				// if key is one the required fields or the field_map object, move to the next key
				if($key == "from_msisdn" || $key == "to_msisdn" || $key == "message" || $key == "field_map")
					continue;
				else {
					// check if key is in field_map object
					if(array_key_exists($key,$data['field_map'])){
						// get the type of the field
						$type = $data['field_map'][$key];
						// validate the key type
						switch($type){
							case 'boolean':
								$check = is_bool($data[$key]) ? true : response(400,"Invalid boolean input.");
							break;
							case 'integer':
								$check = is_int($data[$key]) ? true : response(400,"Invalid integer input");
							break;
							case 'string':
								$check = is_string($data[$key]) ? true : response(400,"Invalid string input");
							break;
							case 'float':
								$check = is_float($data[$key]) ? true : response(400,"Invalid float input.");
							break;
							default:
							response(400,$type . " is not a valid type."); // type not found
						}
					}else {
						response(400, $key . " does not exist in field_map");
					}
				}
					
			}
		}

		//implement the conversion from json to xml
		$xml = arrayToXml($data,"",false);
		response(200, "Successfully completed process", $xml);
	}catch(\Exception $e){
		// display exception/error message
		response(500, $e->getMessage(),[]);
	}
}else{
	if(!isset($data['from_msisdn']))
		response(400,'from_msisdn field is required',[]);
	elseif(!isset($data['to_msisdn']))
		response(400,'to_msisdn field is required',[]);
	elseif(!isset($data['message']))
		response(400,'message field is required',[]);
}

// json response
function response($status,$status_message,$data = [])
{
	header("HTTP/1.1 ".$status);
	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
	exit();
}
