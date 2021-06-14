<?php

// hearders that will allow application to receive a
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

function convertToJson()
{
	try{
		//implement conversion from xml to json
		$xml = simplexml_load_string($data);
		$json = json_encode($xml);
		print $json;
	}catch(\Exception $e){
		echo $e->getMessage();
	}
}

//recursive function for changing json data to xml
function arrayToXml($array, $parentkey="", $xml = false){

	if($xml === false){
		$xml = new SimpleXMLElement('<request/>');
	}
 
	foreach($array as $key => $value){
		if(is_array($value)){
			arrayToXml($value, is_numeric((string) $key)?("n".$key):$key, $xml->addChild(is_numeric((string) $key)?$parentkey:$key));
		} else {
			$xml->addAttribute(is_numeric((string) $key)?("n".$key):$key, $value);
		}
	}
 
	return $xml->asXML();
 }

$data = json_decode(file_get_contents("php://input"));

if(isset($data['from_msisdn']) && isset($data['to_msisdn']) && isset($data['message'])){
	try{
		$data['from_msisdn'] = is_int($data['from_msisdn']) ? $data['from_msisdn'] : throwError("Invalid integer input");
		$data['message'] = is_string($data['message']) ? $data['message'] : throwError("Invalid string input");
		$data['to_msisdn'] = is_int($data['to_msisdn']) ? $data['to_msisdn'] : throwError("Invalid integer input");

		if(count($data) > 4){
			if(isset($data['field_map']))
				echo "field_map does not exist";

			// extra fields
			foreach($data as $key){
				if($key == "from_msisdn" || $key == "to_msisdn" || $key == "message" || $key == "field_map")
					continue;
				else {
					if(array_key_exists($key,$data['field_map'])){
						$type = $data['field_map'][$key];
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
							throwError($type . " is not a valid type.");
						}
					}
				}
				
			}
		}

		//implement the conversion from json to xml
		$xml = arrayToXml($data,"",false);
		echo "xml = ".$xml;
	}catch(\Exception $e){
		echo json_encode($e->getMessage());
	}
}else{
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

// flow diagram
// component in sequence diagram
// Trail audits : for accounting and audit purposes
// step by step sequential record that provides  evidence of
// the doctd history of financial trans to its src
// helps auditors to trace trnxs
// monitors org finances
// starts from invoice receipt
// For api to work, put src code in /var/www/ and create an index.php file
// add headers
// start invoking functions