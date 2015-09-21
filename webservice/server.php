<?php
require_once "../lib/nusoap/nusoap.php";
require_once '../lib/response.php';
include '../lib/functions.php';
	
 //Creating User Class
class Movimenti
{		
		var $id;
		var $descrizione;
}	


 	function getMovimenti($name) {
		
		//logger("Richiesta funzione getMovimenti<br>");

		//Make DB connection
		$link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
		
		mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
				
		$query = "select `Causali`.`ID` AS `id`, `Causali`.`Descrizione` AS `descrizione` from `moneydb`.`Causali` ";
		$result = mysql_query($query);
		$total =0;
		$query_array=array();
		$i=0;
		$user = null;
		//Iterate all Select
		while($row = mysql_fetch_array($result))
		{
			//Create New User instance
			$user = new Movimenti();
			//Fetch User Info
			$user->id=$row['id'];
			$user->descrizione=$row['descrizione'];
			
			//Add User to ARRAY
			$query_array[$i]=$user;
			$i++;
		}
		mysql_close($link);
		
		//Creating Json Array needed for Extjs Proxy
		$res = new Response();
		$res->success = true;
		$res->message = "Loaded data";
		$res->total = $i;
		$res->data = $query_array;

	    //return $res->to_json();
		return $query_array;
	}

$server = new soap_server();
$server->configureWSDL("mymoney", "urn:mymoney");

//$server->register("getMovimenti");

$server->wsdl->addComplexType(
    'Movimenti',
    'complexType',
    'struct',
    'all',
    '',
    array(
        'id' => array('name'=>'id','type'=>'xsd:int'),
        'descrizione' => array('name'=>'descrizione','type'=>'xsd:string')
    )
);

$server->wsdl->addComplexType(
    'MovimentiArray',
    'complexType',
    'array',
    '',
    'SOAP-ENC:Array',
    array(),
    array(
        array('ref'=>'SOAP-ENC:arrayType','wsdl:arrayType'=>'tns:Movimenti[]')
    ),
    'tns:Movimenti'
);

$server->register(
   'getMovimenti',
   array('name'=>'xsd:string'),
   array('return'=>'tns:MovimentiArray'),
    "urn:mymoney",
    "urn:mymoney#getMovimenti",
    "rpc",
    "encoded",
    "Get a listing of movimenti");
   
// $server->register("getMovimenti",
//     array("name" => "xsd:string"),
//     array("return" => "xsd:string"),
//     "urn:mymoney",
//     "urn:mymoney#getMovimenti",
//     "rpc",
//     "encoded",
//     "Get a listing of movimenti");
	
$server->service($HTTP_RAW_POST_DATA);


