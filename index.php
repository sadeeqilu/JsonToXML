<?php

// hearders that will allow application to receive a
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

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
$data = $_POST;

// check if all required fields are available
// display error message for any first missing required field
assert('!isset($data->from_msisdn);','from_msisdn field is required');
assert('!isset($data->to_msisdn);','to_msisdn field is required');
assert('!isset($data->message);','message field is required');
try{

	// validate input data types
	assert('is_int($data->from_msisdn);', 'Invalid integer input');
	assert('is_string($data->message);','Invalid string input');
	assert('is_int($data->to_msisdn);','Invalid integer input');

	// if data has more than 4 inputs, that means extra fields are in the request as well
	if(count($data) > 4){
		// check if field_map variable is available
		assert('isset($data->field_map);',"field_map does not exist");

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
							$check = assert('is_bool($data[$key]);','Invalid boolean input.');
						break;
						case 'integer':
							$check = assert('is_int($data[$key]);','Invalid integer input');
						break;
						case 'string':
							$check = assert('is_string($data[$key]);','Invalid string input');
						break;
						case 'float':
							$check = assert('is_float($data[$key]);','Invalid float input.');
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
	response(200, "Successfully completed process", $xml);
}catch(\Exception $e){
	// display exception/error message
	response(500, $e->getMessage(),[]);
}

function response($status,$status_message,$data)
{
	header("HTTP/1.1 ".$status);
	
	$response['status']=$status;
	$response['status_message']=$status_message;
	$response['data']=$data;
	
	$json_response = json_encode($response);
	echo $json_response;
}
