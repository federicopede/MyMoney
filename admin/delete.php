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
    <td><h1 align="center" class="heading2">Cancellazione eseguita</h1>
  <p align="center">
    <?php 
	 $id=$_REQUEST['id']; 
	 
	 $link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
	
	 mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
	 $query="DELETE FROM Movimenti WHERE id='".$id."'";
		
		  if(!mysql_query($query,$link))
		  {die ("An unexpected error occured while <b>deleting</b> the record, Please try again!");}
		  else
		 {
		  echo "Spesa ".$id." rimossa coorettamente!";}
	 ?>

      </p>
      <p align="center">
        <a href="../"><img border="0" src="../resources/images/check-32.png" />
        </a>        
      </p>
      <p align="left">&nbsp;</p>
    <p align="left">&nbsp;</p></td>
  </tr>
</table>
<h1 align="center" class="heading">&nbsp;</h1>
</body>
</html>