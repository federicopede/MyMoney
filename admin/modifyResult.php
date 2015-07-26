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
<table align="center" cellpadding="0"  align="center"  class="form-table"  border="0">
  <tr>
    <td><h1 align="center" class="heading2">Modifica Spesa</h1>
  <p align="center">
    <?php 
		include '../lib/functions.php';
		
		$id=$_REQUEST['id']; 
	 $ID_CONTO=$_REQUEST['Conto']; 
	 $Data=$_REQUEST['Data'];
	 $ID_CAUSALE=$_REQUEST['Causale'];
	 $ID_TIPO_MOVIMENTO=$_REQUEST['TipoMovimento']; 
	 $Segno=$_REQUEST['Segno']; 
	 $Importo=$_REQUEST['Importo']; 
	 $Descrizione=$_REQUEST['Descrizione']; 
	 
	$format = 'd/m/Y';
	 $mysqldate = DateTime::createFromFormat($format, $Data);
	 $sqldate = $mysqldate->format('Y-m-d');
	 	 
	 $link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
	
	 mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
	 $query="UPDATE Movimenti SET ID_CONTO='".$ID_CONTO."', DataMovimento='".$sqldate."', ID_CAUSALE_MOVIMENTO='".$ID_CAUSALE."', ID_TIPO_MOVIMENTO='".$ID_TIPO_MOVIMENTO."', Segno='".$Segno."', Importo='".$Importo."', Descrizione='".$Descrizione."' WHERE id='".$id."'";
	//echo 	$query;
		  if(!mysql_query($query,$link))
		  {die ("An unexpected error occured while saving the record, Please try again!");}
		  else
		 {
		  //echo "Spesa aggiornata correttamente!";
		  redirect();
		  }
		  
		  
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