<?php

require("validate.php");

interface Converter
{
    public function convert($data);
}

class JsonToXmlConverterClass implements Converter
{
	// public function __construct()
	// {

	// }

	public function convert($data)
	{
		//implement the convertion from json to xml

		try{
			// validate required fields
			$newArray['from_msisdn'] = Validator::int($_POST['from_msisdn']);
			$newArray['message'] = Validator::str($_POST['message']);
			$newArray['to_msisdn'] = Validator::int($_POST['to_msisdn']);

			//validator for field_map 
			// get clarity on this.
			$xml = new SimpleXMLElement('<request/>');
			array_walk_recursive($newArray, array($xml,'addChild'));
			print $xml->asXML();

		}catch(\Exception $e){
			echo $e->getMessage();
		}
		

	}

}

class XmlToJsonConverterClass implements Converter
{
	public function convert($data)
	{
		//implement conversion from xml to json
	}
}

function convertData(Converter $converter)
{
	return $converter->convert([]);
}

// flow diagram
// component in sequence diagram
// Trail audits : for accounting and audit purposes
// step by step sequential record that provides  evidence of
// the doctd history of financial trans to its src
// helps auditors to trace trnxs
// monitors org finances
// starts from invoice receipt
// 