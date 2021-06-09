
<!-- 
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With"); -->

<?php

require_once("./src/validate.php");

header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');

function convertToXML()
{
	//implement the convertion from json to xml

	try{
		// validate required fields
		$newArray['from_msisdn'] = $_POST['from_msisdn'];
		$newArray['message'] = $_POST['message'];
		$newArray['to_msisdn'] = $_POST['to_msisdn'];

		//validator for field_map 
		// get clarity on this.
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
	$data['from_msisdn'] = Validator::int($_POST['from_msisdn']);

	$this->convertToXML();
}else{
	$error_message = '';
	response("required fields missing", 200, null);
}
echo "hello there";

// flow diagram
// component in sequence diagram
// Trail audits : for accounting and audit purposes
// step by step sequential record that provides  evidence of
// the doctd history of financial trans to its src
// helps auditors to trace trnxs
// monitors org finances
// starts from invoice receipt
// 