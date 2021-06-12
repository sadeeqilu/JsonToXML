<?php


function test($data)
{
    $defaults = array(
        CURLOPT_URL => 'http://localhost/JsonToXML/index.php',
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
    );
        $ch = curl_init();
        curl_setopt_array($ch, ($options + $defaults));
        $result = curl_exec($ch);
        curl_close($ch);
        return json_decode($result,true);
}

echo "Tests on JsonToXML API.";

$totalTests = 0;
$passed = 0;
$failed = 0;

$data = [];
try{
    $test1 = test($data);
    if($test1 == ['from_msisdn field is required'])
    {
        $passed += 1;
    }

    echo "Passed test cases = ".$passed;
}catch(\Exception $exception){
    echo $exception->getMessage();
}
