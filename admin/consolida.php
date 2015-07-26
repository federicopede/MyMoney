<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Consolidamento</title>
    <script type="text/javascript" src="./ext-all.js"></script>
	<link rel="stylesheet" type="text/css" href="./resources/css/ext-all-neptune-debug.css"/>
    
    <script type="text/javascript" src="./example-data.js"></script>
    <script type="text/javascript" src="BarRenderer.js"></script>
    <script type="text/javascript" src="store.js"></script>
  	</head>
    <body id="docbody">
    <?php 
    	include '../lib/functions.php';
        include '../lib/db_connect.php';
    	// Start the session
    	session_start();
    
        if (isset($_REQUEST["Conto"])) { $ID_CONTO  = $_REQUEST["Conto"]; } 
        else if (isset($_SESSION["Conto"])) { $ID_CONTO  = $_SESSION["Conto"]; }
        else { $ID_CONTO=null; };
            
        if (isset($_REQUEST["startDate"])) { $startDate  = $_REQUEST["startDate"]; } 
        else if (isset($_SESSION["startDate"])) { $startDate  = $_SESSION["startDate"]; }
        else { $startDate=null; };
        
        if (isset($_REQUEST["endDate"])) { $endDate  = $_REQUEST["endDate"]; } 
        else if (isset($_SESSION["endDate"])) { $endDate  = $_SESSION["endDate"]; }
        else { $endDate=null; };
                        
        $_SESSION["Conto"] = $ID_CONTO;        
        $_SESSION["startDate"] = $startDate;
        $_SESSION["endDate"] = $endDate;
        
        try {
    	
        	// 1 Verifica che la data iniziale sia già stata consolidata o se non esistono altre date precedenti, quinti prendi importo iniziale del conto
            // 2 il conto deve essere valorizzato
            // 3 nel range di date per il conto non ci devono essere altri consolidamenti
            // 4 in addchange spesa non rendere modificabile importo, conto e data se consolidato e visualizza se la spesa è consolidata
            // 5 Itera ogni data, somma i pagamenti, segnali come consolidati e inserisci una riga in Consolidamenti
            // 6 Avvisa l'operatore dell'avvenuto consolidamento
            // 7 dai visibilità del consolidamento nella lista dei movimenti
            
            
            // 
            
    		// Set autocommit to off
    		mysqli_autocommit($mysqli,FALSE);
    		
            if ($ID_CONTO == null)
                die ("Impossibile proseguire, Conto non specificato!<br/>");
                
            $query = "SELECT COUNT(*) FROM Consolidamenti WHERE ID_CONTO = " .$ID_CONTO. " AND Data >= '".$startDate."' AND Data <= '".$endDate."'";
            $rs_result = mysqli_query($mysqli, $query); 
            $row = mysqli_fetch_row($rs_result); 
            $ConsolidamentiGiaFatti = $row[0];
        
            if($ConsolidamentiGiaFatti > 0) {
    			die ("Impossibile proseguire, una data tra quelle selezionate è già consolidata!<br/>");
    		} else {
    			echo "Date Valide!<br/>";
    		}
            
            $query = "SELECT COUNT(*) FROM Movimenti WHERE ID_CONTO = " .$ID_CONTO. " AND DataMovimento = '".$startDate."' AND Consolidato = 1";
            $rs_result = mysqli_query($mysqli, $query); 
            $row = mysqli_fetch_row($rs_result); 
            $ConsolidamentiGiaFatti = $row[0];
        
            if($ConsolidamentiGiaFatti > 0) {
    			die ("Impossibile proseguire, una data tra quelle selezionate è già consolidata!<br/>");
    		} else {
    			echo "Date Valide!<br/>";
    		}
            
            $query = "SELECT COUNT(*) FROM Consolidamenti WHERE ID_CONTO = " .$ID_CONTO. " AND Data < '".$startDate."'";
            $rs_result = mysqli_query($mysqli, $query); 
            $row = mysqli_fetch_row($rs_result); 
            $EsisteConsolidamentoPrecedente = $row[0];
            $ImportoIniziale = 0;
            
            if($EsisteConsolidamentoPrecedente > 0) {
    			$query = "SELECT ImportoSaldo FROM Consolidamenti WHERE ID_CONTO = " .$ID_CONTO. " AND Data < '".$startDate."' ORDER BY Data DESC LIMIT 1";
                $rs_result = mysqli_query($mysqli, $query); 
                $row = mysqli_fetch_row($rs_result); 
                $ImportoIniziale = $row[0];
    		} else {
                $query = "SELECT ImportoIniziale FROM Conti WHERE ID = " .$ID_CONTO. " ";
                echo $query. "<br/>";
                $rs_result = mysqli_query($mysqli, $query); 
                $row = mysqli_fetch_row($rs_result); 
                $ImportoIniziale = $row[0];
    		}
            
            if($ImportoIniziale <= 0) {
    			//die ("Impossibile proseguire, Importo iniziale non configurato!<br/>");
    		} 
            
            echo "Start date : " .$startDate. "<br/>";
            echo "End date : " .$endDate. "<br/>";
            echo "Importo Iniziale : " .$ImportoIniziale. "<br/>";
            
            echo "<br/>";
            
            $query = "SELECT DISTINCT DataMovimento FROM Movimenti WHERE ID_CONTO = " .$ID_CONTO. " AND DataMovimento >= '".$startDate."' AND DataMovimento <= '".$endDate."' ORDER BY DataMovimento ASC";
            $rs_result = mysqli_query($mysqli, $query); 
            
            $ImportoSaldo = $ImportoIniziale;
            //$queryResultArray = mysqli_fetch_array($rs_result);
            while($row = mysqli_fetch_array($rs_result))
            {
                echo "<br/><br/>Data: ".$row['DataMovimento']."<br/>";
                
                $format = 'Y-m-d';
	            $tmp = date_create($row['DataMovimento']);
                $data = $tmp->format($format);
                
                //echo $data."br/>";
    			$query = "SELECT SUM(IF (Segno = '-',-Importo,Importo)) FROM Movimenti WHERE ID_CONTO = " .$ID_CONTO. " AND DataMovimento = '".$data."'";
                //echo $query;
                $rs_result2 = mysqli_query($mysqli, $query); 
                $row = mysqli_fetch_row($rs_result2); 
                $ImportoDaConsolidare = $row[0];
                $ImportoSaldo += $ImportoDaConsolidare;
                
                echo "Importo Data : " .$ImportoDaConsolidare. "<br/>";
                echo "Importo Saldo : " .$ImportoSaldo. "<br/>";
                
	           $query="INSERT INTO Consolidamenti (ID_CONTO, Data, ImportoSaldo) values('".$ID_CONTO."', '".$data."', '".$ImportoSaldo."')";
		       //echo $query;
		       if(!mysqli_query($mysqli, $query))
		       {
                   die ("Si e' verificato un errore nel salvataggio del Consolidamento, Riprova!");
               } else {
                   echo "Consolidamento Inserito!<br/>";
               }
                          
               $query="UPDATE Movimenti SET Consolidato = 1 WHERE ID_CONTO = " .$ID_CONTO. " AND DataMovimento = '".$data."'";
		       //echo $query;
		       if(!mysqli_query($mysqli, $query))
		       {
                   die ("Si e' verificato un errore nel salvataggio dei Movimenti, Riprova!");
               } else {
                   echo "Movimenti Aggiornati!<br/>";
               }    
                                        
            }            
            /*
    		// Insert transaction
    		$query="INSERT INTO Trasferimenti (ID_CONTO_ORIGINE, ID_CONTO_DESTINAZIONE, DataTrasferimento, Importo, Descrizione) values('".$ID_CONTO_ORIGINE."', '".$ID_CONTO_DESTINAZIONE."', '".$sqldate."',  '".$Importo."',  '".$Descrizione."')";
    		
    		if(!mysqli_query($mysqli, $query)) {
    			die ("An unexpected error occured while saving the record, Please try again!<br/>");
    		} else {
    			echo "Trasferimento inserito!<br/>";
    		}
    		
    		$id = $mysqli->insert_id;
    		
    		// Insert Movimento Origine
    		$query="INSERT INTO Movimenti (ID_CONTO, ID_TIPO_MOVIMENTO, ID_CAUSALE_MOVIMENTO, ID_TRANSAZIONE, DataMovimento, Segno, Importo, Descrizione) values('".$ID_CONTO_ORIGINE."', '".$ID_TIPO_MOVIMENTO."', '".$ID_CAUSALE."', '".$id."',  '".$sqldate."',  '-',  '".$Importo."',  '".$Descrizione."')";
    		
    		if(!mysqli_query($mysqli, $query)) {
    			die ("An unexpected error occured while saving the record, Please try again!<br/>");
    		} else {
    			echo "Movimento Origine Inserito!<br/>";
    		}
    		
    		// Insert Movimento DEstinazione
    		$query="INSERT INTO Movimenti (ID_CONTO, ID_TIPO_MOVIMENTO, ID_CAUSALE_MOVIMENTO, ID_TRANSAZIONE, DataMovimento, Segno, Importo, Descrizione) values('".$ID_CONTO_DESTINAZIONE."','".$ID_TIPO_MOVIMENTO."','".$ID_CAUSALE."', '".$id."',  '".$sqldate."',  '+','".$Importo."', '".$Descrizione."')";
    		
    		if(!mysqli_query($mysqli, $query)) {
    			die ("An unexpected error occured while saving the record, Please try again!<br/>");
    		} else {
    			echo "Movimento Destinazione Inserito!<br/>";
    		}
    		
            */
            
    		// Commit transaction
    		mysqli_commit($mysqli);
    		
    
    	 
     	} catch (Exception $e) {
     		echo $e;
     	}	
    	
    	
    	// Close connection
    	mysqli_close($mysqli);
        
    ?>
    </body>
</html>
