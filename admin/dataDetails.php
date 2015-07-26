<?php
// $link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
// 
// 	 mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
// 
// $result = mysql_query("SELECT Causale, Importo FROM statistiche");
// 
// $rows = array();
// while($r = mysql_fetch_array($result)) {
// 	$row[0] = $r[0];
// 	$row[1] = $r[1];
// 	array_push($rows,$row);
// }
// 
// print json_encode($rows, JSON_NUMERIC_CHECK);
// 
// mysql_close($link);
?> 

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
		var $ID;
		var $Conto;
    var $DataMovimento;
    var $Causale;
    var $Pagamento;
    var $Importo;
    var $Descrizione;
    var $Transazione;
    var $Consolidato;
         
}
//Make DB connection
$link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");

mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");

        
        if (isset($_REQUEST["Conto"])) { $ID_CONTO  = $_REQUEST["Conto"]; } 
        else if (isset($_SESSION["Conto"])) { $ID_CONTO  = $_SESSION["Conto"]; }
        else { $ID_CONTO=null; };
            
        if (isset($_REQUEST["Causale"])) { $ID_CAUSALE  = $_REQUEST["Causale"]; } 
        else if (isset($_SESSION["Causale"])) { $ID_CAUSALE  = $_SESSION["Causale"]; }
        else { $ID_CAUSALE=null; };
            
            
            
        if (isset($_REQUEST["startDate"])) { $startDate  = $_REQUEST["startDate"]; } 
        else if (isset($_SESSION["startDate"])) { $startDate  = $_SESSION["startDate"]; }
        else { $startDate=null; };
        
        
        if (isset($_REQUEST["endDate"])) { $endDate  = $_REQUEST["endDate"]; } 
        else if (isset($_SESSION["endDate"])) { $endDate  = $_SESSION["endDate"]; }
        else { $endDate=null; };
                        
        //$ID_CONTO =  $_REQUEST['Conto']; 
  
        $_SESSION["Conto"] = $ID_CONTO;
        $_SESSION["Causale"] = $ID_CAUSALE;
        
        $_SESSION["startDate"] = $startDate;
        $_SESSION["endDate"] = $endDate;
        
       $paramCausale = $_REQUEST['causale'];
       $paramAnno = $_REQUEST['anno'];
       $paramMese = $_REQUEST['mese'];
               
       $days = get_number_of_days_in_month($paramMese,$paramAnno);
       $startDate = $paramAnno.'-'.$paramMese.'-01';
       $endDate = $paramAnno.'-'.$paramMese.'-'.$days;
       
        $query2 = "SELECT ID FROM Causali WHERE Descrizione = '".str_replace('Importo', '', $paramCausale)."'";
        
        $myarray = mysql_query($query2);
        $record = mysql_fetch_array($myarray);
        $ID_CAUSALE_MOVIMENTO =($record[0]);
        
        $WHERE = " WHERE 1=1 AND (`viewmovimenti`.`Segno` = '-') AND (ID_TRANSAZIONE IS NULL)";
        
        if ($ID_CONTO != null)
          $WHERE .= " AND (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = " .$ID_CAUSALE_MOVIMENTO. ") ";
       
        if ($startDate != null)
          $WHERE .= " AND `viewmovimenti`.DataMovimento >= '" .$startDate. "' ";
        if ($endDate != null)
          $WHERE .= " AND `viewmovimenti`.DataMovimento <= '" .$endDate. "' ";        
       
       
	 	   $query="SELECT 
          `ID` 
          ,`Conto`
          ,`DataMovimento` AS DataMovimento 
          ,`TipoMovimento` AS Pagamento 
          ,`Causale`            
          ,(case when (`viewmovimenti`.`Segno` = '-') then -`viewmovimenti`.`Importo` else `viewmovimenti`.`Importo` end) AS `Importo` 
          ,`Descrizione` 
          ,`Transazione` 
          ,`Consolidato` 
        FROM viewmovimenti 
        " .$WHERE. "
        ORDER BY DataMovimento DESC, ID DESC ";
        
        //logger($query);
        //LIMIT $start_from, " . $ENTRIES;
//" .$WHERE. " 
$query1 = "select COUNT(*) from `moneydb`.`viewmovimenti` " .$WHERE. "";
      //logger('ciao details');
      //logger($query);
       
$result = mysql_query($query);
$totalquery = mysql_query($query1);
$total = mysql_fetch_array($totalquery);
$total =($total[0]);
$query_array=array();
$i=0;
//Iterate all Select
while($row = mysql_fetch_array($result))
  {
    //Create New User instance
    $user = new User();
    //Fetch User Info
    $user->ID=$row['ID'];
    $user->Conto=$row['Conto'];
    $user->DataMovimento=$row['DataMovimento'];
    $user->Pagamento=$row['Pagamento'];
    $user->Causale=$row['Causale'];
    $user->Importo=$row['Importo'];
    $user->Descrizione=$row['Descrizione'];
    $user->Transazione=$row['Transazione'];
    $user->Consolidato=$row['Consolidato'];
    
    //Add User to ARRAY
    $query_array[$i]=$user;
    $i++;
  }
mysql_close($link);

//Creating Json Array needed for Extjs Proxy
$res = new Response();
$res->success = true;
$res->message = "Loaded data";
$res->total = $total;
$res->data = $query_array;
//Printing json ARRAY
print_r($res->to_json());

?>