<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Control Panel :: Student Information Panel:: Institute of Information Technology, BZU</title>
<link type="text/css" rel="stylesheet" href="../resources/css/base.css">
<link type="text/css" rel="stylesheet" href="../resources/css/table.css">
</head>

<body >

<br />
<br />
<br />
<table align="center" cellpadding="0" align="center" class="form-table"  border="0">
  <tr>
    <td><h1 align="center" class="heading">Welcome to Admin Panel</h1>
  <p align="center">
<?php 
	$ID_CONTO_ORIGINE=$_REQUEST['ContoOrigine'];
	$ID_CONTO_DESTINAZIONE=$_REQUEST['ContoDestinazione'];
	$Data=$_REQUEST['Data'];
	$Importo=$_REQUEST['Importo'];
	 
	$ID_CAUSALE=27; // Trasferimento
	$ID_TIPO_MOVIMENTO=$_REQUEST['TipoMovimento']; 
	$Descrizione=$_REQUEST['Descrizione']; 
	 
	$format = 'd/m/Y';
	$mysqldate = DateTime::createFromFormat($format, $Data);
	$sqldate = $mysqldate->format('Y-m-d');
	 
	//$link=mysqli_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
	//mysqli_select_db("MoneyDB",$link) or die ("Cannot select the database!");
	
	$link = mysqli_connect("localhost","root@localhost","","MoneyDB");
	if (mysqli_connect_errno($con))
	{
		echo "Failed to connect to MySQL: " . mysqli_connect_error() . "<br/>";
	}
	try {
		
		// Set autocommit to off
		mysqli_autocommit($link,FALSE);
		
		// Insert transaction
		$query="INSERT INTO Trasferimenti (ID_CONTO_ORIGINE, ID_CONTO_DESTINAZIONE, DataTrasferimento, Importo, Descrizione) values('".$ID_CONTO_ORIGINE."', '".$ID_CONTO_DESTINAZIONE."', '".$sqldate."',  '".$Importo."',  '".$Descrizione."')";
		
		if(!mysqli_query($link, $query)) {
			die ("An unexpected error occured while saving the record, Please try again!<br/>");
		} else {
			echo "Trasferimento inserito!<br/>";
		}
		
		$id = $link->insert_id;
		
		// Insert Movimento Origine
		$query="INSERT INTO Movimenti (ID_CONTO, ID_TIPO_MOVIMENTO, ID_CAUSALE_MOVIMENTO, ID_TRANSAZIONE, DataMovimento, Segno, Importo, Descrizione) values('".$ID_CONTO_ORIGINE."', '".$ID_TIPO_MOVIMENTO."', '".$ID_CAUSALE."', '".$id."',  '".$sqldate."',  '-',  '".$Importo."',  '".$Descrizione."')";
		
		if(!mysqli_query($link, $query)) {
			die ("An unexpected error occured while saving the record, Please try again!<br/>");
		} else {
			echo "Movimento Origine Inserito!<br/>";
		}
		
		// Insert Movimento DEstinazione
		$query="INSERT INTO Movimenti (ID_CONTO, ID_TIPO_MOVIMENTO, ID_CAUSALE_MOVIMENTO, ID_TRANSAZIONE, DataMovimento, Segno, Importo, Descrizione) values('".$ID_CONTO_DESTINAZIONE."','".$ID_TIPO_MOVIMENTO."','".$ID_CAUSALE."', '".$id."',  '".$sqldate."',  '+','".$Importo."', '".$Descrizione."')";
		
		if(!mysqli_query($link, $query)) {
			die ("An unexpected error occured while saving the record, Please try again!<br/>");
		} else {
			echo "Movimento Destinazione Inserito!<br/>";
		}
		
		// Commit transaction
		mysqli_commit($link);
		

	 
 	} catch (Exception $e) {
 		echo $e;
 	}	
	
	
	// Close connection
	mysqli_close($link);

	 ?>

      </p>
      <p align="center">
		   <a href="../"><img border="0" src="../resources/images/check-32.png" />
		  </p>
      <p align="left">&nbsp;</p>
    <p align="left">&nbsp;</p></td>
  </tr>
</table>
<h1 align="center" class="heading">&nbsp;</h1>
</body>
</html>