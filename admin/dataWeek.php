  <?php
    include '../lib/functions.php';
// Start the session
session_start();
?>
<?php

//Import Response Class
require_once '../lib/response.php';

//Creating User Class
class User
{		
    //var $mese;
    var $anno;
    var $settimana;
		var $name;
		var $ImportoAuto;
    var $ImportoCasa;
    var $ImportoAlimenti;
    var $ImportoSpeseMediche;
    var $ImportoViaggio;
    var $ImportoTasse;
    var $ImportoTelefonia;
    var $ImportoVestiti;
    var $ImportoAnimali;
    var $ImportoGiardinaggio;
    var $ImportoTelevisione;
    var $ImportoAltro;
    var $ImportoRateizzazioni;
    var $ImportoMutuo;
    var $ImportoSvago;
    var $ImportoIntrattenimento;
    var $ImportoOggettiPersonali;
    var $ImportoUtilita;
    var $ImportoIstruzione; 
    var $ImportoTOTALE;  
}
//Make DB connection
$link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");

mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");

        
        if (isset($_REQUEST["Conto"])) { $ID_CONTO  = $_REQUEST["Conto"]; } 
        else if (isset($_SESSION["Conto"])) { $ID_CONTO  = $_SESSION["Conto"]; }
        else { $ID_CONTO=null; };
            
        $_SESSION["Conto"] = $ID_CONTO;
        $_SESSION["Causale"] = $ID_CAUSALE;
        
        //$_SESSION["startDate"] = $startDate;
        //$_SESSION["endDate"] = $endDate;
        $startDate = $_GET["DataInizio"];
        $endDate = $_GET["DataFine"];
        $Causali = $_GET["Causali"];
                
        $WHERE = " WHERE 1=1 AND (`viewmovimenti`.`Segno` = '-') AND (ID_TRANSAZIONE IS NULL)";
        //if ($ID_CONTO != null)
        //  $WHERE .= " AND (`viewmovimenti`.`ID_CONTO` = " .$ID_CONTO. ") ";
        if ($Causali != null && $Causali != "")
          $WHERE .= " AND `viewmovimenti`.ID_CAUSALE_MOVIMENTO IN (" .$Causali. ") ";            
        if ($startDate != null)
          $WHERE .= " AND `viewmovimenti`.DataMovimento >= '" .$startDate. "' ";
        if ($endDate != null)
          $WHERE .= " AND `viewmovimenti`.DataMovimento <= '" .$endDate. "' ";        
  
  //,(YEARWEEK(`viewmovimenti`.`DataMovimento`,1) / 100) AS anno        
$query = "
select 
  WEEK(`viewmovimenti`.`DataMovimento`,1) AS settimana
  ,2015 as `anno`
  ,CONCAT(WEEK(`viewmovimenti`.`DataMovimento`,1), CONCAT(' dal ', DATE_FORMAT(SUBDATE(`viewmovimenti`.`DataMovimento`, WEEKDAY(`viewmovimenti`.`DataMovimento`)),'%d %b'))) AS `name`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 1) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoAuto`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 2) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoCasa`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 3) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoAlimenti`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 4) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoSpeseMediche`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 5) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoViaggio`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 6) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoTasse`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 7) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoTelefonia`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 8) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoVestiti`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 9) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoAnimali`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 10) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoGiardinaggio`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 11) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoTelevisione`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 12) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoAltro`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 13) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoRateizzazioni`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 14) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoMutuo`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 15) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoSvago`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 16) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoIntrattenimento`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 17) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoOggettiPersonali`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 18) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoUtilita`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = 25) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoIstruzione`
  ,sum((case when (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,25)) then `viewmovimenti`.`Importo` else 0 end)) AS `ImportoTOTALE`  
from `moneydb`.`viewmovimenti`" .$WHERE. " 
group by WEEK(`viewmovimenti`.`DataMovimento`,1)
";

//logger($query);

$result = mysql_query($query);
$query_array=array();
$i=0;
//Iterate all Select
while($row = mysql_fetch_array($result))
  {
    //Create New User instance
    $user = new User();
    //Fetch User Info
    $user->mese=$row['settimana']; // Mese/Anno
    $user->anno=$row['anno']; // Mese/Anno
    $user->name=$row['name']; // Mese/Anno
    $user->ImportoAuto=$row['ImportoAuto'];
    $user->ImportoCasa=$row['ImportoCasa'];
    $user->ImportoAlimenti=$row['ImportoAlimenti'];
	  $user->ImportoSpeseMediche=$row['ImportoSpeseMediche'];
    $user->ImportoViaggio=$row['ImportoViaggio'];
    $user->ImportoTasse=$row['ImportoTasse'];
    $user->ImportoTelefonia=$row['ImportoTelefonia'];
    $user->ImportoVestiti=$row['ImportoVestiti'];
    $user->ImportoAnimali=$row['ImportoAnimali'];
    $user->ImportoGiardinaggio=$row['ImportoGiardinaggio'];
    $user->ImportoTelevisione=$row['ImportoTelevisione'];
    $user->ImportoAltro=$row['ImportoAltro'];
    $user->ImportoRateizzazioni=$row['ImportoRateizzazioni'];
    $user->ImportoMutuo=$row['ImportoMutuo'];
    $user->ImportoSvago=$row['ImportoSvago'];
    $user->ImportoIntrattenimento=$row['ImportoIntrattenimento'];
    $user->ImportoOggettiPersonali=$row['ImportoOggettiPersonali'];
    $user->ImportoUtilita=$row['ImportoUtilita'];
    $user->ImportoIstruzione=$row['ImportoIstruzione'];
    $user->ImportoTOTALE=$row['ImportoTOTALE'];
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