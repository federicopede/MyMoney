<?php
require_once "../lib/nusoap/nusoap.php";
require_once '../lib/response.php';
$client = new nusoap_client("http://localhost/money/webservice/server.php");

$error = $client->getError();
if ($error) {
    echo "<h2>Constructor error</h2><pre>" . $error . "</pre>";
}

$result = $client->call("getMovimenti", array("name" => "books"));

if ($client->fault) {
    echo "<h2>Fault</h2><pre>";
    print_r($result);
    echo "</pre>";
}
else {
    $error = $client->getError();
    if ($error) {
        echo "<h2>Error</h2><pre>" . $error . "</pre>";
    }
    else {
        echo "<h2>Books</h2><pre>";
        echo $result; //->to_json();
        echo "</pre>";
    }
}

echo "<h2>Request</h2>";
echo "<pre>" . htmlspecialchars($client->request, ENT_QUOTES) . "</pre>";
echo "<h2>Response</h2>";
echo "<pre>" . htmlspecialchars($client->response, ENT_QUOTES) . "</pre>";