<?php


function test($data)
{
    // $defaults = array(
    //     CURLOPT_URL => 'http://localhost/JsonToXML/index.php',
    //     CURLOPT_POST => true,
    //     CURLOPT_POSTFIELDS => http_build_query($data),
    // );

        $ch = curl_init();
        $url = 'http://localhost/JsonToXML/index.php';
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        if($e = curl_error($ch)){
            echo $e;
        }else{
            $decode_result = json_decode($result,true);
        }
        curl_close($ch);
        return $decode_result;
}

echo "Tests on JsonToXML API.";

$passed = 0;

$data1 = [];
$data2 = ["from_msisdn"=>123];
$data3 = ["from_msisdn" => 123,"to_msisdn" => 456];
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
