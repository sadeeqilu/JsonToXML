<?php

interface converter
{
    public function convert($data);
}

class JsonToXmlConverterClass implements converter
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

class XmlToJsonConverterClass implements converter
{
	public function convert($data)
	{
		//implement conversion from xml to json
	}
}