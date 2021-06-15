<?php

// hearders that will allow application to receive a
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
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
$data = json_decode(file_get_contents("php://input"));

// check if all required fields are available
if(isset($data['from_msisdn']) && isset($data['to_msisdn']) && isset($data['message'])){
	try{

		// validate input data types
		$data['from_msisdn'] = is_int($data['from_msisdn']) ? $data['from_msisdn'] : throwError("Invalid integer input");
		$data['message'] = is_string($data['message']) ? $data['message'] : throwError("Invalid string input");
		$data['to_msisdn'] = is_int($data['to_msisdn']) ? $data['to_msisdn'] : throwError("Invalid integer input");

		// if data has more than 4 inputs, that means extra fields are in the request as well
		if(count($data) > 4){
			// check if field_map variable is available
			if(isset($data['field_map']))
				throwError("field_map does not exist");

			// loop through all data to get extra fields
			foreach($data as $key){
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
								$check = is_bool($data[$key]) ? true : throwError("Invalid boolean input.");
							break;
							case 'integer':
								$check = is_int($data[$key]) ? true : throwError("Invalid integer input");
							break;
							case 'string':
								$check = is_string($data[$key]) ? true : throwError("Invalid string input");
							break;
							case 'float':
								$check = is_float($data[$key]) ? true : throwError("Invalid float input.");
							break;
							default:
							throwError($type . " is not a valid type."); // type not found
						}
					}
				}
				
			}
		}

		//implement the conversion from json to xml
		$xml = arrayToXml($data,"",false);
		echo "xml = ".$xml;
	}catch(\Exception $e){
		// display exception/error message
		echo json_encode($e->getMessage());
	}
}else{
	// display error message for any first missing required field
	$error_messages = [];
	if(!isset($data['from_msisdn'])){
	 	array_push($error_messages,'from_msisdn field is required');
		echo json_encode($error_messages);
	}
	else if(!isset($data['to_msisdn'])){
		array_push($error_messages,'to_msisdn field is required');
		echo json_encode($error_messages);
	}
	else if(!isset($data['message'])){
		array_push($error_messages,'message field is required');
		echo json_encode($error_messages);
	}

}
