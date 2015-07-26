<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Control Panel :: Student Information Panel:: Institute of Information Technology, BZU</title>
<link type="text/css" rel="stylesheet" href="../resources/css/base.css">
<link type="text/css" rel="stylesheet" href="../resources/css/table.css">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" src="../resources/js/jquery.ui.datepicker-it.js" defer="defer"></script>
  <script src="../resources/js/numeric.js" defer="defer"></script>

    <?php 
      include '../lib/functions.php';
	 $id=$_REQUEST['id']; 
	 ?>  
  
  <script>
  $(function() {    
    $.datepicker.setDefaults($.datepicker.regional['it']);
    $( "#datepicker" ).datepicker();
    $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
  });
  
  
function confirmation() {
	var answer = confirm("Sei sicuro di voler eliminare la spesa?")
	if (answer){
    document.form2.submit(); 
	}
	else{
		
	}
}
  
  </script>
  
</head>

<body >

<br />
<br />
<br />

   <?php
	 $link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
	
	 mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
		$query="SELECT `ID`, `Conto`, `TipoMovimento`, `Causale`, DataMovimento, `Segno`, `Importo`, `Descrizione`, ID_TIPO_MOVIMENTO, ID_CAUSALE_MOVIMENTO, ID_CONTO, Consolidato, ID_TRANSAZIONE FROM viewmovimenti WHERE id='".$id."'";
		
		 $resource=mysql_query($query,$link) or die ("An unexpected error occured while <b>deleting</b> the record, Please try again!");
		  $result=mysql_fetch_array($resource);
		  $Consolidato = $result[11] == 1 ? true : false;
      $Trasferimento = $result[12] === null ? false : true;
      $READONLY = $Consolidato || $Trasferimento;
	 ?>
   
   
<table align="center" cellpadding="0" align="center" class="form-table"  border="0">
  <tr>
    <td><h1 align="center" class="heading2">Modifica Spesa <?php if ($Trasferimento) { echo "(Trasferimento)"; } else { echo ""; } ?></h1>
  <p align="center">


   
   <form id="form2" name="form2" method="get" action="delete.php">
             <input type="hidden" name="id" id="iddb2" value="<?php echo $id ?>">
   </form>
     <form id="form1" name="form1" method="get" action="modifyResult.php">
             <input type="hidden" name="id" id="iddb" value="<?php echo $id ?>">
         <table align="center" width="auto" border="0">
          <tr>
            <td width="160" align="right"><strong>Conto:&nbsp;&nbsp;</strong></td>
            <td width="152">
                  <?php 
        	        $query="SELECT ID, Nome FROM Conti";
        		
                  $rs = mysql_query($query) or die(mysql_error());
                  if ($READONLY) { echo "<select name=\"Conto\" disabled >"; } else { echo "<select name=\"Conto\" >"; }
                  while($row = mysql_fetch_array($rs)){
                    if ($result[10] != $row["ID"])
                    echo "<option value='".$row["ID"]."'>".$row["Nome"]."</option>";
                    else
                    echo "<option selected='selected' value='".$row["ID"]."'>".$row["Nome"]."</option>";
                    }mysql_free_result($rs);
                  echo "</select>"; 
	                ?>
            </td> 
          </tr>
          <tr>
            <td width="160" align="right"><strong>Data:&nbsp;&nbsp;</strong></td>
            <td width="152"><label>
              <?php 
                	 $format = 'd/m/Y';
                   $date = date_create($result[4]);
              ?>
              <input type="text" name="Data" id="<?php if ($READONLY) { echo "datereadonly"; } else { echo "datepicker"; }?>" value="<?php echo $date->format($format) ?>"  <?php if ($READONLY) { echo "readonly"; } else { echo ""; }?>/>
            </label></td>
          </tr>
          <tr>
            <td width="160" align="right"><strong>Causale:&nbsp;&nbsp;</strong></td>
            <td width="152">
                  <?php 
        	        $query="SELECT ID, Descrizione FROM Causali";
        		
                  $rs = mysql_query($query) or die(mysql_error());
                  if ($Trasferimento) { echo "<select name=\"Causale\" disabled >"; } else { echo "<select name=\"Causale\" >"; }
                  while($row = mysql_fetch_array($rs)){
                    if ($result[9] != $row["ID"])
                    echo "<option value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                    else
                    echo "<option selected='selected' value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                    }mysql_free_result($rs);
                  echo "</select>"; 
	                ?>
            </td> 
          </tr>
          <tr>
            <td width="160" align="right"><strong>Tipo Movimento:&nbsp;&nbsp;</strong></td>
            <td width="152">
                  <?php 
        	        $query="SELECT ID, Descrizione FROM TipiMovimenti";
        		
                  $rs = mysql_query($query) or die(mysql_error());
                  if ($Trasferimento) { echo "<select name=\"TipoMovimento\" disabled >"; } else { echo "<select name=\"TipoMovimento\" >"; }
                  while($row = mysql_fetch_array($rs))
                  {
                    if ($result[8] != $row["ID"])
                    echo "<option value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                    else
                    echo "<option selected='selected' value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                    
                   }mysql_free_result($rs);
                  echo "</select>"; 
	                ?>
            </td> 
          </tr>          
          <tr>
            <td width="160" align="right"><strong>Segno:&nbsp;&nbsp;</strong></td>
            <!--<td><input type="text" name="Segno" id="textfield3" value=""/></td>-->
            
              <td>
              Dare (-) <input type="radio" name="Segno" value="-" <?php if ($result[5] == "-") echo "checked=\"checked\""; else echo ""; ?> <?php if ($READONLY) { echo "disabled"; } else { echo ""; }?> />
              Avere (+)  <input type="radio" name="Segno" value="+" <?php if ($result[5] == "+") echo "checked=\"checked\""; else echo ""; ?> <?php if ($READONLY) { echo "disabled"; } else { echo ""; }?> />
              <!--<input type="text" name="Segno" id="textfield3" /></td>-->
              </td>
              
          </tr>
          <tr>
            <td width="160" align="right"><strong>Importo:&nbsp;&nbsp;</strong></td>
            <td width="152"><input type="text" name="Importo" width="50" id="textfield4" class="numeric"  value="<?php echo $result[6] ?>"  <?php if ($READONLY) { echo "readonly"; } else { echo ""; }?> /></td>
          </tr>          
          <tr>
            <td width="160" align="right"><strong>Descrizione:&nbsp;&nbsp;</strong></td>
            <td width="152"><input type="text" width="50" name="Descrizione" id="textfield5" value="<?php echo $result[7] ?>" /></td>
          </tr>
        </table>
        <p align="center">
          <label>
            
          </label>
        </p>
        <p align="center">
          <!--<input type="submit" name="button" id="button" value="Modify" />-->
          <input type="image" name="button" id="button" src="../resources/images/Save-32.png" onclick="document.getElementById('form1').submit();" value="Submit" />&nbsp;&nbsp;&nbsp;&nbsp;
          <!--<a href="../"><img border="0" src="../resources/images/check-32.png" />-->
          <!--<a href="delete.php?id=<?php echo $id ?>"><img border="0" src="../resources/images/delete-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
          <a href='#' onclick="javascript:confirmation();"><img border="0" name="test" src="../resources/images/delete-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
          <a href="../"><img border="0" src="../resources/images/home-32-2.png" alt="Go Back"  /></a></p>
      </form>
      
      

      </p>
      <p align="center"><a href="delete.php"><a href="../"></a></p>
      <p align="left">&nbsp;</p>
    <p align="left">&nbsp;</p></td>
  </tr>
</table>
<h1 align="center" class="heading">&nbsp;</h1>
</body>
</html>