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
  <script>
  $(function() {    
    $.datepicker.setDefaults($.datepicker.regional['it']);
    $("#datepicker").datepicker();
    $('#datepicker').datepicker('setDate', new Date());
    $(".numeric").numeric({ decimal : ".",  negative : false, scale: 2 });
  });
  </script>
</head>

<body >

<br />
<br />
<br />
<table align="center" cellpadding="0" align="center" class="form-table"   border="0">
  <tr>
    
    <!--
      Conto
      Data
      Causale
      TipoMovimento
      Segno
      Importo
      Descrizione
    -->
  <?php 
    $link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
    mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
  ?>
                
    <td><h1 align="center" class="heading2">Inserisci Spesa</h1>
      <p align="center">&nbsp;</p>
      <form id="form1" name="form1" method="get" action="insertResult.php">
        <table align="center" width="auto" border="0">
          <tr>
            <th ></th><th></th>
            </tr>
          <tr>
            <td width="160" align="right"><strong>Conto:&nbsp;&nbsp;</strong></td>
            <td width="152">
                  <?php 
        	        $query="SELECT ID, Nome FROM Conti";
        		
                  $rs = mysql_query($query) or die(mysql_error());
                  echo "<select name=\"Conto\">";
                  while($row = mysql_fetch_array($rs)){
                    echo "<option value='".$row["ID"]."'>".$row["Nome"]."</option>";
                    }mysql_free_result($rs);
                  echo "</select>"; 
	                ?>
            </td> 
          </tr>
          <tr>
            <td width="160" align="right"><strong>Data:&nbsp;&nbsp;</strong></td>
            <td width="152"><label>
              <input type="text" name="Data" id="datepicker">
            </label></td>
          </tr>
          <tr>
            <td width="160" align="right"><strong>Causale:&nbsp;&nbsp;</strong></td>
            <td width="152">
                  <?php 
        	        $query="SELECT ID, Descrizione FROM Causali";
        		
                  $rs = mysql_query($query) or die(mysql_error());
                  echo "<select name=\"Causale\">";
                  while($row = mysql_fetch_array($rs)){
                    echo "<option value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
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
                  echo "<select name=\"TipoMovimento\">";
                  while($row = mysql_fetch_array($rs)){
                    echo "<option value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                    }mysql_free_result($rs);
                  echo "</select>"; 
	                ?>
            </td> 
          </tr>          
          <tr>
            <td width="160" align="right"><strong>Segno:&nbsp;&nbsp;</strong></td>
            <td>
              Dare (-) <input type="radio" name="Segno" value="-" checked="checked"/>
              Avere (+)  <input type="radio" name="Segno" value="+"/>
              <!--<input type="text" name="Segno" id="textfield3" /></td>-->
              </td>
          </tr>
          <tr>
            <td width="160" align="right"><strong>Importo:</strong></td>
            <td><input type="text" name="Importo" id="textfield4" class="numeric" /></td>
          </tr>          
          <tr>
            <td width="160" align="right"><strong>Descrizione:&nbsp;&nbsp;</strong></td>
            <td><input type="text" name="Descrizione" id="textfield5" /></td>
          </tr>
        </table>
        <p align="center">
          <label>
            
          </label>
        </p>
        <p align="center">
          <input type="image" name="button" id="button" src="../resources/images/Save-32.png" onclick="document.getElementById('form1').submit();" value="Submit" />
          &nbsp;&nbsp;&nbsp;&nbsp;
          <!--<button type="submit" name="button" value="Submit"><img src="../resources/images/Save-32.png" alt="Save"></button>-->
          <!--<a href="../"><img border="0" src="../resources/images/check-32.png" />-->
          <a href="../"><img border="0" src="../resources/images/home-32-2.png" alt="Go Back"  /></a></p>
      </form>
</td>
  </tr>
</table>
<h1 align="center" class="heading">&nbsp;</h1>
</body>
</html>