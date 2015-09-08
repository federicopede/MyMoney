<?php
  include '../lib/functions.php';
  session_start();
?>
<?php

//Import Response Class
require_once '../lib/response.php';

//Creating User Class
class User
{		
		var $id;
		var $descrizione;
}
//Make DB connection
$link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");

mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
          
$query = "select `Causali`.`ID` AS `id`, `Causali`.`Descrizione` AS `descrizione` from `moneydb`.`Causali` ";
$result = mysql_query($query);
$total =0;
$query_array=array();
$i=0;
//Iterate all Select
while($row = mysql_fetch_array($result))
  {
    //Create New User instance
    $user = new User();
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
//Printing json ARRAY
print_r($res->to_json());


?>