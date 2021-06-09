<?php

require_once("./src/validate.php");

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

function convertToXML($data)
{
	//implement the convertion from json to xml

	try{

		//validator for field_map 
		$xml = new SimpleXMLElement('<request/>');
		array_walk_recursive($newArray, array($xml,'addChild'));
		print $xml->asXML();
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
			echo $e->getMessage();
	}
}else{
    $error_message = '';
	echo "errors";
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