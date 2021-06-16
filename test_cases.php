<?php

require("vendor/autoload.php");

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Max-Age: 1000');

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

function test($data)
{
    try{
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            'base_uri' => 'http://localhost:8001',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $request = $client->post('', [
            'body' => json_encode($data)
        ]);
        $response = $request->getBody();
        var_dump("Response ",json_decode($response));
        // var_dump("data = ",json_decode($response,true));
        return json_decode($response);
    }catch(\Exception $e){
        echo $e->getMessage();
    }
}

echo "Tests on JsonToXML API.";
ignore_user_abort(true);
$failed = 0;
function my_assert_handler($file, $line, $code, $desc = null)
{
    $failed++;
}
// Set up the callback
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

//error path
$data1 = [];
$data2 = ["from_msisdn"=>123];
$data3 = ["from_msisdn" => 123,"to_msisdn" => 456];
$data4 = ["from_msisdn" => 123,"message" => "test"];
$data5 = ["from_msisdn" => "test string", "to_msisdn" => 456, "message" => "test"];
$data6 = ["from_msisdn" => 123, "to_msisdn" => 456, "message" => 789];
$data7 = ["from_msisdn" => 123, "to_msisdn" => 456, "message" => "test", "field_1" => "test", "field_map" => ["field_1" => "integer"]];
$data8 = ["from_msisdn" => 123, "to_msisdn" => 456, "message" => "test", "field_1" => 20, "field_map" => ["field_1" => "int"]];
$data9 = ["from_msisdn" => 123, "to_msisdn" => 456, "message" => "test", "field_1" => 20, "field_map" => ["field_2" => "integer"]];

//happy path
$data10 = ["from_msisdn" => 123,"to_msisdn" => 456, "message" => "Test"];
$data11 = ["from_msisdn" => 123, "to_msisdn" => 456, "message" => "test", "field_1" => "testing", "field_map" => ["field_1" => "string"]];



try{
    var_dump("before first");
    $test1 = test($data1);
    var_dump("after first", $test1);

    $test2 = test($data2);
    var_dump("after 2", $test2);

    $test3 = test($data3);
    var_dump("after 3",$test3);

    $test4 = test($data4);
    var_dump("after 4",$test4);

    $test5 = test($data5);
    var_dump("after 5",$test5);

    $test6 = test($data6);
    var_dump("after 6",$test6);

    $test7 = test($data7);
    var_dump("after 7",$test7);

    $test8 = test($data8);
    var_dump("after 8",$test8);

    $test9 = test($data9);
    var_dump("after 9",$test9);

    $test10 = test($data10);
    var_dump("after 10",$test10);

    $test11 = test($data11);
    var_dump("after 11",$test11);


    assert($test1['status_message'] == "from_msisdn field is required");
    assert($test2["status_message"] == "to_msisdn field is missing.");
    assert($test3["status_message"] == "message field is missing.");
    assert($test4["status_message"] == "to_msisdn field is missing.");
    assert($test5["status_message"] == "Invalid integer input for from_msisdn field.");
    assert($test6["status_message"] == "Invalid string input for message field.");
    assert($test7["status_message"] == "field_1 value is an invalid input.");
    assert($test8["status_message"] == "int is not an accepted type for extra_fields.");
    assert($test9["status_message"] == "key field_1 does not exist in field_map.");
    assert($test10["status_message"] == "Successfully completed process");
    assert($test11["status_message"] == "Successfully completed process");

    echo "Failed test cases = ".$failed;
}catch(\Exception $exception){
    // show exception
    echo $exception->getMessage();
}
