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
			// validator for required fields
			//validator for field_map
			// if()
			// $xml = new SimpleXMLElement('<request/>');
		}catch(\Exception $e){

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