<?php

use GuzzleHttp\Client;

function test($data)
{
    $client = new Client([
        // Base URI is used with relative requests
        'base_uri' => 'http://localhost/JsonToXML/index.php',
        // You can set any number of default request options.
        'timeout'  => 2.0,
    ]);

        // $ch = curl_init();
        // $url = 'http://localhost/JsonToXML/index.php';
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // $result = curl_exec($ch);
        // if($e = curl_error($ch)){
        //     echo $e;
        // }else{
        //     $decode_result = json_decode($result,true);
        // }
        // curl_close($ch);
        $request = $client->post('', [
            'body' => json_encode($data)
        ]);
        $response = $request->getBody();
        return $json_decode($response,true);
}

echo "Tests on JsonToXML API.";

$passed = 0;

//error path
$data1 = [];
$data2 = ["from_msisdn"=>123];
$data3 = ["from_msisdn" => 123,"to_msisdn" => 456];
$data4 = ["from_msisdn" => 123,"to_msisdn" => 456];

try{
    $test1 = test($data1);
    $test2 = test($data2);
    $test3 = test($data3);
    if($test1 == ['from_msisdn field is required'])
        $passed++;
    if($test2 == ["to_msisdn field is required"])
        $passed++;
    if($test3 == ["message field is required"])
        $passed++;

    echo "Passed test cases = ".$passed;
}catch(\Exception $exception){
    echo $exception->getMessage();
}
