<?php

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
		// $xml = new SimpleXMLElement('<request/>');
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