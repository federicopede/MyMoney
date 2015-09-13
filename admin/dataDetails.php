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
            
            
            
        //$ID_CONTO =  $_REQUEST['Conto']; 
  
        $_SESSION["Conto"] = $ID_CONTO;
        $_SESSION["Causale"] = $ID_CAUSALE;
        
        $startDate = $_REQUEST['dataInizio'];
        $endDate = $_REQUEST['dataFine'];
        
        $paramCausale = $_REQUEST['causale'];
        $paramAnno = $_REQUEST['anno'];
        $paramMese = $_REQUEST['mese'];
        $settimana = $_REQUEST['settimana'];
        $Tipo = $_REQUEST['Tipo'];
        $listaCausali = $_REQUEST['listaCausali'];
        
        $days = get_number_of_days_in_month($paramMese,$paramAnno);
        //$startDate = $paramAnno.'-'.$paramMese.'-01';
        //$endDate = $paramAnno.'-'.$paramMese.'-'.$days;
       
        $query2 = "SELECT ID FROM Causali WHERE Descrizione = '".str_replace('Importo', '', $paramCausale)."'";
        
        $myarray = mysql_query($query2);
        $record = mysql_fetch_array($myarray);
        $ID_CAUSALE_MOVIMENTO =($record[0]);
        
        $WHERE = " WHERE 1=1 AND (ID_TRANSAZIONE IS NULL) ";
        //logger('Tipo : '.$Tipo);
        if ($Tipo == null || $Tipo == '-1' || $Tipo == '0')
        {
          $WHERE .= " AND (`viewmovimenti`.`Segno` = '-')  ";
        }
        else
        {          
          $WHERE .= " AND (`viewmovimenti`.`Segno` = '+')  ";
        }
        
        //logger('WHERE: '.$WHERE);
        
        if ($ID_CAUSALE_MOVIMENTO != null)
          $WHERE .= " AND (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` = " .$ID_CAUSALE_MOVIMENTO. ") ";
        
        if ($listaCausali != null && $listaCausali != "")
          $WHERE .= " AND (`viewmovimenti`.`ID_CAUSALE_MOVIMENTO` IN (" .$listaCausali. ")) ";        
        
       
        // WHERE YEAR(Date) = 2011 AND MONTH(Date) = 5
       
        if ($paramAnno != null)
          $WHERE .= " AND YEAR(`viewmovimenti`.DataMovimento) = " .$paramAnno. " ";
        if ($paramMese != null)
          $WHERE .= " AND MONTH(`viewmovimenti`.DataMovimento) = " .$paramMese. " ";
        if ($settimana != null)
          $WHERE .= " AND WEEK(`viewmovimenti`.DataMovimento, 1) = " .$settimana. " ";
        //if ($paramMese != null)
        //  $WHERE .= " AND MONTH(`viewmovimenti`.DataMovimento) = '" .$paramMese. "' ";       
                         
        if ($startDate != null)
          $WHERE .= " AND `viewmovimenti`.DataMovimento >= '" .$startDate. "' ";
        if ($endDate != null)
          $WHERE .= " AND `viewmovimenti`.DataMovimento <= '" .$endDate. "' ";        
       /*
                               anno: anno,
                        mese: mese,
                        settimana: settimana,
                        causale: causale,
                        dataInizio: dataInizio,
                        dataFine: dataFine
       */
       
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
        
        //logger('SoloUscite: '.(($soloUscite != null) and ($soloUscite)));
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