
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
		var $name;
		var $Entrate;
    var $Uscite;
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
        
        $WHERE = " WHERE 1=1 "; //AND (`viewmovimenti`.`Segno` = '-') AND (ID_TRANSAZIONE IS NULL)";
        //if ($ID_CONTO != null)
        //  $WHERE .= " AND (`viewmovimenti`.`ID_CONTO` = " .$ID_CONTO. ") ";
        //if ($ID_CAUSALE != null)
        //  $WHERE .= " AND `viewmovimenti`.ID_CAUSALE_MOVIMENTO = " .$ID_CAUSALE. " ";            
        //if ($startDate != null)
        //  $WHERE .= " AND `viewmovimenti`.DataMovimento >= '" .$startDate. "' ";
        //if ($endDate != null)
        //  $WHERE .= " AND `viewmovimenti`.DataMovimento <= '" .$endDate. "' ";        
          
         //echo   $WHERE;
         
         //$WHERE = "";  
          
$query = "select `viewstatistichemese2`.`Mese`, `viewstatistichemese2`.`Entrate`, `viewstatistichemese2`.`Uscite` from `moneydb`.`viewstatistichemese2` " .$WHERE. "";// group by `viewmovimenti`.`Causale` ORDER BY sum(`viewmovimenti`.`Importo`)";
$query1 = "select COUNT(*) from `moneydb`.`viewstatistichemese2` " .$WHERE. "";
      //logger('ciao');
      logger($query);
       
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
    $user->name=$row['Mese'];
    $user->Entrate=$row['Entrate'];
	  $user->Uscite=$row['Uscite'];
    
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