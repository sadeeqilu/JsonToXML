<?php

require_once("./src/validate.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

function convertToXML($data)
{
	//implement the convertion from json to xml

	try{

		//validator for field_map 
		// $xml = new SimpleXMLElement('<request/>');
		// array_walk_recursive($newArray, array($xml,'addChild'));
		// print $xml->asXML();
		$xml = arrayToXml($data,"",false);
		echo "xml = ".$xml;
	}catch(\Exception $e){
		echo $e->getMessage();
	}
}

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

function arrayToXml($array, $parentkey="", $xml = false){

	if($xml === false){
		$xml = new SimpleXMLElement('<request/>');
	}
 
	foreach($array as $key => $value){
		if(is_array($value)){
			array2xml($value, is_numeric((string) $key)?("n".$key):$key, $xml->addChild(is_numeric((string) $key)?$parentkey:$key));
		} else {
			$xml->addAttribute(is_numeric((string) $key)?("n".$key):$key, $value);
		}
	}
 
	return $xml->asXML();
 }

// function convertData(Converter $converter)
// {
// 	return $converter->convert([]);
// }

if(isset($_POST['from_msisdn']) && isset($_POST['to_msisdn']) && isset($_POST['message'])){
	try{
		$data['from_msisdn'] = Validator::int($_POST['from_msisdn']);
		$data['message'] = Validator::str($_POST['message']);
		$data['to_msisdn'] = Validator::int($_POST['to_msisdn']);

		convertToXML($data);
	}catch(\Exception $e){
		header('Content-Type: application/json');
		echo json_encode($e->getMessage());
	}
}else{
	header('Content-Type: application/json');
	$error_messages = [];
	if(!isset($_POST['from_msisdn']))
		array_push($error_messages,'from_msisdn field is required');
	if(!isset($_POST['to_msisdn']))
		array_push($error_messages,'to_msisdn field is required');
	if(!isset($_POST['message']))
		array_push($error_messages,'message field is required');
    echo json_encode($error_messages);

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