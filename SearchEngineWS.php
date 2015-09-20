<?php 
    class SearchEngineWS {
function getWebUrl($name){
    $engines = array(
        'google' => 'www.google.it',
        'yahoo'  => 'www.yahoo.it'
    );
    return isset($engines[$name]) ? $engines[$name] : “Search Engine unknown”);
}
}
$server= new SoapServer("search_engine.wsdl");
$server->setClass("SearchEngineWS");
$server->handle();
?>
