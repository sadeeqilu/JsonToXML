<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

chdir(__DIR__);
require "vendor/autoload.php";

putenv('abcvyzSilentTest=true');

$config = new \abcvyz\lib\config(realpath("logs/config.yaml"));

$config = $config->asArray();

$log = new \abcvyz\lib\logger_v21($config);

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

try{
    // get post data
    $data = json_decode(file_get_contents('php://input'),true);
}catch(\Exception $e){
    $log->critical($e->getMessage());
    response(200, "Exception error.",[]);
}

// check if all required fields are available and 
// display error message for any first missing required field
if(!isset($data['from_msisdn'])){
    response(200,'from_msisdn field is required',[]);
    return;
}

if(!isset($data['to_msisdn'])){
    response(200,'to_msisdn field is required',[]);
    return;
}

if(!isset($data['message'])){
    response(200,'message field is required',[]);
    return; 
}

// validate input data types
if(!is_int($data['from_msisdn'])){
    response(200,'Invalid integer input',[]);
    return;
}
if(!is_string($data['message'])){
    response(200,'Invalid string input',[]);
    return;
}
if(!is_int($data['to_msisdn'])){
    response(200,'Invalid integer input',[]);
    return;
}


// loop through all data to get extra fields
foreach($data as $key => $value){
    // if key is one the required fields or the field_map object, move to the next key
    if($key == "from_msisdn" || $key == "to_msisdn" || $key == "message" || $key == "field_map")
        continue;
    else {
         // check if field_map variable is available
        if(!isset($data['field_map'])){
            response(200, "field_map does not exist.",[]);
            return;
        }
        elseif(!array_key_exists($key,$data['field_map'])){  // check if key is not in field_map object
            response(200, $key . " does not exist in field_map");
            return;
        }else {
            // get the type of the field
            $type = $data['field_map'][$key];

            // validate the key type (share with users this naming convention)
            if($type == "boolean" && !is_bool($data[$key])){
                response(200,"Invalid boolean input.",[]);
                return;
            } 
            elseif($type == "integer" && !is_int($data[$key])){
                response(200,"Invalid integer input");
                return;
            }
               
            elseif($type == 'string' && !is_string($data[$key])){
                response(200,"Invalid string input");
                return;
            }
            elseif($type == "float" && !is_float($data[$key])){
                response(200,"Invalid float input.");
                return;
            }
            else{
                response(200,$type . " is not a valid type."); // type not found
                return;
            }
        }
    }
                
}
// }
try{
    //implement the conversion from json to xml
    $xml = arrayToXml($data,"",false);
    response(200, "Successfully completed process", $xml);
}catch(\Exception $e){
    $log->critical($e->getMessage());
    // display exception/error message
    response(200, "Exception error.",[]);
}

// json response
function response($status,$status_message,$data = [])
{
    header("HTTP/1.1 ".$status);
    
    $response['status']=$status;
    $response['status_message']=$status_message;
    $response['data']=$data;
    
    $json_response = json_encode($response);
    echo $json_response;
    // exit();
}
