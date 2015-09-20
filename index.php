<?php
// Inserisci in questo punto il codice per la connessione al DB e l'utilizzo delle varie funzioni.
include './lib/db_connect.php';
include './lib/functions.php';
sec_session_start();
if(login_check($mysqli) == true) {
 
   // Inserisci qui il contenuto delle tue pagine!
 ?>
 

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to Student Information Center:: Institute of Computing, BZU</title>
<link type="text/css" rel="stylesheet" href="./resources/css/base.css">
<link type="text/css" rel="stylesheet" href="./resources/css/table.css">
<link type="text/css" rel="stylesheet" href="./resources/css/enriched.css">

<link type="text/css" rel="stylesheet" href="./resources/css/fixedTable.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script src="./resources/js/fixedTable.js" defer="defer"></script>
  
  <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">
      <!--
      <link href="http://netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">-->
      <link rel="stylesheet" type="text/css" media="all" href="./resources/css/daterangepicker-bs3.css" />
      <!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>-->
      <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="./resources/js/moment.js"></script>
      <script type="text/javascript" src="./resources/js/daterangepicker.js"></script>
        
</head>

<body>
<h1 align="center" class="heading">My Money</h1>
<br />
<h4 align="center" >Benvenuto <b><?php echo $_SESSION['username']; ?></b></h2>
<br />
<table  align="center" cellpadding="0"   width="100%" border="0">
  <tr>
    <td>
      
     
     <p align="center"> 
		  <a href="admin/insert.php"><img border="0" name="test" src="resources/images/Add-New-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="admin/insertTransaction.php"><img border="0" name="test" src="resources/images/Transport-Van-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;		  
      <!--<a href="chart/Pie.html"><img border="0" name="test" src="resources/images/Graph-02-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
      <a href='#' onclick="javascript:submitpie();"><img border="0" name="test" src="resources/images/Bar-Chart-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
      <!--<a href="chart/BarRenderer.html"><img border="0" name="test" src="resources/images/Bar-Chart-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
      <!--<a href='#' onclick="javascript:submitchart();"><img border="0" name="test" src="resources/images/Graph-02-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;-->
      <!--<a href='#' onclick="javascript:submitchart(2);"><img border="0" name="test" src="resources/images/Graph-02-32.png" /></a>-->      
      <a href='#' onclick="javascript:consolida();"><img border="0" name="test" src="resources/images/rubber-stamp-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
      <a href='#' onclick="javascript:submitform();"><img border="0" name="test" src="resources/images/command-refresh-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
      <a href="account/logout.php"><img border="0" name="test" src="resources/images/Logout-32.png" /></a>&nbsp;&nbsp;&nbsp;&nbsp;
      
	  </p>
    
 
                  
      <p align="center">
      
      <?php 
  	 	  $link=mysql_connect("localhost","root@localhost","") or die("Cannot Connect to the database!");
  		 	mysql_select_db("MoneyDB",$link) or die ("Cannot select the database!");
    
        $month_start = strtotime('first day of this month', time());
        $month_end = strtotime('last day of this month', time());    
      	
        if (isset($_GET["page"])) { $page  = $_GET["page"]; } 
        else if (isset($_SESSION["page"])) { $page  = $_SESSION["page"]; }
        else { $page=1; };
            
        if (isset($_GET["entries"])) { $ENTRIES  = $_GET["entries"]; } 
        else if (isset($_SESSION["entries"])) { $ENTRIES  = $_SESSION["entries"]; }
        else { $ENTRIES=200; };
              
  		  $start_from = ($page-1) * $ENTRIES; 
        
        if (isset($_GET["filtroDescrizione"])) { $filtroDescrizione  = $_GET["filtroDescrizione"]; } 
        else if (isset($_SESSION["filtroDescrizione"])) { $filtroDescrizione  = $_SESSION["filtroDescrizione"]; }
        else { $filtroDescrizione=""; };        
        
        
        if (isset($_REQUEST["Conto"])) { $ID_CONTO  = $_REQUEST["Conto"]; } 
        else if (isset($_SESSION["Conto"])) { $ID_CONTO  = $_SESSION["Conto"]; }
        else { $ID_CONTO=null; };
            
        if (isset($_REQUEST["Causale"])) { $ID_CAUSALE  = $_REQUEST["Causale"]; } 
        else if (isset($_SESSION["Causale"])) { $ID_CAUSALE  = $_SESSION["Causale"]; }
        else { $ID_CAUSALE=null; };
        
        if (isset($_REQUEST["Segno"])) { $Segno  = $_REQUEST["Segno"]; } 
        else if (isset($_SESSION["Segno"])) { $Segno  = $_SESSION["Segno"]; }
        else { $Segno="*"; };        
            
        if (isset($_REQUEST["startDate"])) { $startDate  = $_REQUEST["startDate"]; } 
        else if (isset($_SESSION["startDate"])) { $startDate  = $_SESSION["startDate"]; }
        else { $startDate=date('Y-m-01'); };
        
        
        
        if (isset($_REQUEST["endDate"])) { $endDate  = $_REQUEST["endDate"]; } 
        else if (isset($_SESSION["endDate"])) { $endDate  = $_SESSION["endDate"]; }
        else { $endDate=null; };
        //echo 'ciao '.date('Y-m-d', $month_end);
        //$ID_CONTO =  $_REQUEST['Conto']; 
  
        $_SESSION["page"] = $page;
        $_SESSION["entries"] = $ENTRIES;
        $_SESSION["filtroDescrizione"] = $filtroDescrizione;
        $_SESSION["Conto"] = $ID_CONTO;
        $_SESSION["Causale"] = $ID_CAUSALE;
        $_SESSION["Segno"] = $Segno;
        
        $_SESSION["startDate"] = $startDate;
        $_SESSION["endDate"] = $endDate;
        //echo "Conto: ".$ID_CONTO;
        //if ($startDate != null)
          //$('#reportrange').data('daterangepicker').setStartDate($startDate);
        //else
        
        //if ($endDate != null)
          //$('#reportrange').data('daterangepicker').setEndDate($endDate);
        //else
        
        $WHERE = " WHERE 1=1 ";
        if ($ID_CONTO != null)
          $WHERE .= " AND v.ID_CONTO = " .$ID_CONTO. " ";
        if ($ID_CAUSALE != null)
          $WHERE .= " AND ID_CAUSALE_MOVIMENTO = " .$ID_CAUSALE. " ";  
        if ($Segno != null && $Segno != '*')
          $WHERE .= " AND Segno = '" .$Segno. "' AND ID_TRANSAZIONE IS NULL";                    
        if ($startDate != null)
          $WHERE .= " AND DataMovimento >= '" .$startDate. "' ";
        if ($endDate != null)        
          $WHERE .= " AND DataMovimento <= '" .$endDate. "' ";
          
        if ($filtroDescrizione != null && $filtroDescrizione != "")
          $WHERE .= " AND Descrizione like '%" .$filtroDescrizione. "%' ";                  

          //echo   $WHERE;            
      ?>

      <form id="form4" name="form4" method="get" action="admin/consolida.php">                 
      <input type="hidden" name="startDate" value="" />
      <input type="hidden" name="endDate" value="" />
      <input type="hidden" name="Conto" value="" />
      </form>
      
      <form id="form3" name="form3" method="get" action="chart/General.php">                 
      <input type="hidden" name="startDate" value="" />
      <input type="hidden" name="endDate" value="" />
      </form>
      
    <form id="form2" name="form2" method="get" action="chart/BarRenderer.php">                 
      <input type="hidden" name="startDate" value="" />
      <input type="hidden" name="endDate" value="" />
      </form>

    <form id="form1" name="form1" method="get" action="index.php">                 
      <input type="hidden" name="page" value="1" />
      <input type="hidden" name="startDate" value="" />
      <input type="hidden" name="endDate" value="" />


    <!--<tr>
      <td></td>-->
  
  
<table class="TableDueColonne">
  <tbody>
<tr>
  <td>
     Record :&nbsp; 
    </td>
    
    <td>
      
              <select name="entries" aria-controls="example" class="">
          <option value="10" <?php if ($ENTRIES == 10) { echo "selected='selected'";} else {echo "";}; ?>>10</option>
          <option value="25" <?php if ($ENTRIES == 25) { echo "selected='selected'";} else {echo "";}; ?>>25</option>
          <option value="50" <?php if ($ENTRIES == 50) { echo "selected='selected'";} else {echo "";}; ?>>50</option>
          <option value="100" <?php if ($ENTRIES == 100) { echo "selected='selected'";} else {echo "";}; ?>>100</option>
          <option value="200" <?php if ($ENTRIES == 200) { echo "selected='selected'";} else {echo "";}; ?>>200</option>
        </select> 
      </td>
  </tr>
  
  <tr>
    <td> Conto :&nbsp;</td> 
  <td>
   <?php
       $query="SELECT ID, Nome FROM Conti";
        		
                  $rs = mysql_query($query) or die(mysql_error());
                  echo "<select class=\"\"  name=\"Conto\">  onchange=\"javascript:submitform();\"";
                  echo "<option value=''>TUTTI</option>";
                  while($row = mysql_fetch_array($rs))
                  {
                    if ($ID_CONTO != $row["ID"])
                    echo "<option value='".$row["ID"]."'>".$row["Nome"]."</option>";
                    else
                    echo "<option selected='selected' value='".$row["ID"]."'>".$row["Nome"]."</option>";
                    
                  }
                  mysql_free_result($rs);
                  echo "</select>"; 
   ?>
   </td>
   </tr>
  <tr>
    <td>
      Causale :&nbsp;
    </td>
    <td>
 <?php 
        	        $query="SELECT ID, Descrizione FROM Causali";
        		
                  $rs = mysql_query($query) or die(mysql_error());
                  echo "<select name=\"Causale\">";
                   echo "<option value=''>TUTTI</option>";
                  while($row = mysql_fetch_array($rs)){
                    //echo "<option value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                    
                    if ($ID_CAUSALE != $row["ID"])
                    echo "<option value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                    else
                    echo "<option selected='selected' value='".$row["ID"]."'>".$row["Descrizione"]."</option>";
                                        
                    }mysql_free_result($rs);
                  echo "</select>"; 
	                ?>      
    </td>
   </tr>
          <tr>
            <td>Tipo :&nbsp;</td>
            <td>
              Tutte (*)  <input type="radio" name="Segno" value="*" <?php if ($Segno == "*") { echo "checked=\"checked\"";} else {echo "";}; ?> />
              Uscite (-) <input type="radio" name="Segno" value="-" <?php if ($Segno == "-") { echo "checked=\"checked\"";} else {echo "";}; ?>/>
              Entrate (+)  <input type="radio" name="Segno" value="+" <?php if ($Segno == "+") { echo "checked=\"checked\"";} else {echo "";}; ?>/>
              <!--<input type="text" name="Segno" id="textfield3" /></td>-->
              </td>
          </tr>   
   <tr> <td>Date :&nbsp;</td><td>
    
    <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 2px 5px; border: 1px solid #ccc">
      <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
      <span></span> <b class="caret"></b>
    </div>
    
</td>
</tr>


    <tr>
      <td> Descrizione :&nbsp; </td>
      <td>
        <?php
                 if ($filtroDescrizione != null)
                    echo "<input name='filtroDescrizione' type='text' class='' id='filtroDescrizione' value='".$filtroDescrizione."' />";
                    else
                    echo "<input name='filtroDescrizione' type='text' class='' id='filtroDescrizione' value='' />";
             ?>             
        
         
    </td>
  <tr/>
  
  
</tbody>
</table>
  
  
   </form> 
   
   
   
  <SCRIPT language="JavaScript"> 
    
    $('#reportrange').on('apply.daterangepicker', function(ev, picker) {
      document.form1.startDate.value = picker.startDate.format('YYYY-MM-DD');
      document.form1.endDate.value = picker.endDate.format('YYYY-MM-DD');
    });
    
    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
      //do something, like clearing an input
      var picker = $('#reportrange').data('daterangepicker');
      picker.setStartDate(null);
      picker.setEndDate(null);
      
      document.form1.startDate.value =  null;
      document.form1.endDate.value =  null;
      $('#reportrange span').html('--');
    });    
    
    function submitchart() 
    { 
      var picker = $('#reportrange').data('daterangepicker');
      document.form2.startDate.value = picker.startDate.format('YYYY-MM-DD');
      document.form2.endDate.value = picker.endDate.format('YYYY-MM-DD');      
      document.form2.submit(); 
    } 
    
    function consolida()
    {
      var picker = $('#reportrange').data('daterangepicker');
      document.form4.startDate.value = picker.startDate.format('YYYY-MM-DD');
      document.form4.endDate.value = picker.endDate.format('YYYY-MM-DD');
      document.form4.Conto.value = document.form1.Conto.value;    
      document.form4.submit(); 
    }
    function submitpie() 
    { 
      var picker = $('#reportrange').data('daterangepicker');
      document.form3.startDate.value = picker.startDate.format('YYYY-MM-DD');
      document.form3.endDate.value = picker.endDate.format('YYYY-MM-DD');      
      document.form3.submit(); 
    }     
    function submitform() 
    { 
      var picker = $('#reportrange').data('daterangepicker');
      document.form1.startDate.value = picker.startDate.format('YYYY-MM-DD');
      document.form1.endDate.value = picker.endDate.format('YYYY-MM-DD');      
      document.form1.submit(); 
    } 
    function submitformPaged(page) 
    { 
      var picker = $('#reportrange').data('daterangepicker');
      document.form1.startDate.value = picker.startDate.format('YYYY-MM-DD');
      document.form1.endDate.value = picker.endDate.format('YYYY-MM-DD');      
      document.form1.page.value = page;
      document.form1.submit(); 
    } 
</SCRIPT> 

        
  <br/>
  <br/>
  <?php

        $sql = "SELECT COUNT(ID) FROM viewmovimenti v " .$WHERE; 
		
  
$rs_result = mysql_query($sql,$link); 
$row = mysql_fetch_row($rs_result); 
$total_records = $row[0];
$total_pages = ceil($total_records /   $ENTRIES);
$pageLinks = "";
$table = "";

for ($i=1; $i<=$total_pages; $i++) {
  //href='index.php?page= 
    if ($page == $i)
    $pageLinks .= "<a href='#' onclick='javascript:submitformPaged(".$i.");' style='color:yellow;'><b>".$i."</b></a> ";
    else
    $pageLinks .= "<a href='#' onclick='javascript:submitformPaged(".$i.");' style='color:maroon;'>".$i."</a> "; 
}; 
	
  
  
 
     $ImportoIniziale =0.0;
     $TotaleSpeso = 0.0;
     $TotaleUscito = 0.0;
     $TotaleEntrato = 0.0;
     
      if ($ID_CONTO != null)
      {
          //$sql = "SELECT ImportoIniziale FROM Conti WHERE ID = " .$ID_CONTO. ""; 
		      //$rs_result = mysql_query($sql,$link); 
          //$row = mysql_fetch_row($rs_result); 
          //$ImportoIniziale = $row[0];
        
          //if ($ID_CONTO != null)
              $query = "SELECT COUNT(*) FROM Consolidamenti WHERE ID_CONTO = " .$ID_CONTO. " AND Data < '".$startDate."'";
          //else
          //    $query = "SELECT COUNT(*) FROM Consolidamenti WHERE Data < '".$startDate."'";
              
          $rs_result = mysqli_query($mysqli, $query); 
          $row = mysqli_fetch_row($rs_result); 
          $EsisteConsolidamentoPrecedente = $row[0];
          $ImportoIniziale = 0;
            
          if ($EsisteConsolidamentoPrecedente > 0) {
            //if ($ID_CONTO != null)
    			     $query = "SELECT ImportoSaldo FROM Consolidamenti WHERE ID_CONTO = " .$ID_CONTO. " AND Data < '".$startDate."' ORDER BY Data DESC LIMIT 1";
            //else
            //   $query = "SELECT ImportoSaldo FROM Consolidamenti WHERE Data < '".$startDate."' ORDER BY Data DESC LIMIT 1"; 
            $rs_result = mysqli_query($mysqli, $query); 
            $row = mysqli_fetch_row($rs_result); 
            $ImportoIniziale = $row[0];
    		  } else {
            $query = "SELECT ImportoIniziale FROM Conti WHERE ID = " .$ID_CONTO. " ";
            //echo $query. "<br/>";
            $rs_result = mysqli_query($mysqli, $query); 
            $row = mysqli_fetch_row($rs_result); 
            $ImportoIniziale = $row[0];
    		  }          
      }
  $tmp = $ImportoIniziale;
  ?>
  

  
  <?php

	 	   $query="
       SELECT 
           v.`ID`, 
           v.`Conto`, 
           v.`TipoMovimento`, 
           v.`Causale`, 
           v.`DataMovimento`, 
           v.`Segno`, 
           v.`Importo`, 
           v.`Descrizione`, 
           v.`Transazione`, 
           v.`Consolidato`,
           c.`ImportoSaldo`
       FROM viewmovimenti v
       LEFT JOIN Consolidamenti c on c.Data = v.DataMovimento and c.ID_CONTO = v.ID_CONTO
       " .$WHERE. " 
       ORDER BY DataMovimento DESC, ID DESC 
       LIMIT $start_from, " . $ENTRIES;
		
		  $resource=mysql_query($query,$link);
		  
		  /*Data DEscrizione Tipo TipoTrasferimento Importo*/
		  $table .= "
		<table id=\"rounded-corner\" summary=\"2007 Major IT Companies' Profit\" class=\"rwd-table\" align=\"center\" width=\"70%\">
    <thead>
		<tr>
    <th scope=\"col\" class=\"rounded-company\"><b></b></th> 
    <th scope=\"col\" class=\"rounded-company\"><b>Nr.</b></th> 
		<th scope=\"col\" class=\"rounded-company\"><b>ID</b></th> 
    <th scope=\"col\" class=\"rounded-q1\"><b>Conto</b></th>
    <th scope=\"col\"><b>Data</b></th>
    <th scope=\"col\"><b>Pagamento</b></th>
    <th scope=\"col\"><b>Causale</b></th>
    <th scope=\"col\"><b>Importo</b></th>
    <th scope=\"col\"><b>Importo<br/>Saldo</b></th>
    <th scope=\"col\"><b>Descrizione</b></th>
    <th scope=\"col\" align=\"center\"><b><--></b></th>
    <th scope=\"col\" align=\"center\"><b>Mod.</b></th></tr>
    </thead> ";
    $table .= "<tbody>";
    
  
      $Prog = 1;
while($result=mysql_fetch_array($resource))
	{ 
    if ($result[5] == '-')
    {
      $tmp -= $result[6];
      $TotaleSpeso -= $result[6];
      $TotaleUscito += $result[6];
    }
    else
    {
      $tmp += $result[6];
      $TotaleSpeso += $result[6];
      $TotaleEntrato += $result[6];
    }
    $phpdate = strtotime( $result[4] );
		$mysqldate = date( 'd/m/Y', $phpdate  );
		$imp = strval($result[5]).strval($result[6])." €";
    
    if ($result[9] == true)
    {
      $table .= "<tr style='color:gray;'>";      
    }
    else
    
   {
     $table .= "<tr>";
   }
	$table .= "
  <td><input type=\"checkbox\"/></td>
  <td>".$Prog."</td>
  <td>".$result[0]."</td>
  <td>".$result[1]."</td>
  <td>".$mysqldate."</td>
  <td>".$result[2]."</td>
  <td>".$result[3]."</td>
  <td style='text-align:right'>".$imp."</td>
  <td style='text-align:right'>".$result[10]."</td>
  <td>".$result[7]."</td>
  <td align=\"center\">".$result[8]."</td>
  <td align=\"center\">
	<a href=\"admin/modify.php?id=".$result[0]."\"><img border=\"0\" src=\"resources/images/editor-32.png\"/></a>
	</td></tr>";
	$Prog++;
  } 
  $table .= "</tbody>";
  $table .= "<tfoot>
    	<tr>
        	<td colspan=\"4\" class=\"rounded-foot-left\"><em>The above data were fictional and made up, please do not sue me</em></td>
        	<td class=\"rounded-foot-right\">&nbsp;</td>
        </tr>
    </tfoot>";
  $table .= "</table>";
	
	
  
	 ?>
        
      </p>
  <p align="center">&nbsp;
    <center>
    <table>
      <tr><td align="right">Importo Iniziale : </td><td align="right"><?php echo strval(number_format($ImportoIniziale, 2, '.', '')); ?> €</td></tr>
    
    <tr><td align="right">Entrate : </td><td align="right"><?php echo strval(number_format($TotaleEntrato, 2, '.', '')); ?> €</td></tr>
    <tr><td align="right">Uscite : </td><td align="right"><?php echo strval(number_format($TotaleUscito, 2, '.', '')); ?> €</td></tr>
    <tr><td colspan="2">-------------------------------------------------</td></tr>
    <tr><td align="right">Importo Residuo : </td><td align="right"><?php echo strval(number_format($tmp, 2, '.', '')); ?> €</td></tr>
    </table>
    </center>
  </p>
  Pagine : <?php echo $pageLinks; ?>
  <br/><br/>
  <?php echo $table; ?>
  <br/>
  Pagine : <?php echo $pageLinks; ?>
    <p align="left">&nbsp;</p></td>
  </tr>
</table>
<h1 align="center" class="up">&uarr; UP &uarr;</h1>




<script>
 $(document).ready(function(){
     //$("table").fixMe();
     $(".up").click(function() {
        $('html, body').animate({
        scrollTop: 0
     }, 2000);
 });
 
 //$('#reportrange span').html(moment().startOf('month').format('DD, MMMM YYYY') + ' - ' + moment().endOf('month').format('DD, MMMM YYYY'));

    $('#reportrange').daterangepicker({
        format: 'YYYY-MM-DD',
        startDate: moment().startOf('month'),
        endDate: moment().endOf('month'),
        minDate: '2015/01/01',
        maxDate: '2099/12/31',
        dateLimit: { days: 355 },
        showDropdowns: true,
        showWeekNumbers: true,
        timePicker: false,
        timePickerIncrement: 1,
        timePicker12Hour: true,
        ranges: {
           'Oggi': [moment(), moment()],
           'Ieri': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Ultimi 7 Giorni': [moment().subtract(6, 'days'), moment()],
           'Ultimi 30 Giorni': [moment().subtract(29, 'days'), moment()],
           'Questo Mese': [moment().startOf('month'), moment().endOf('month')],
           'Questo Anno': [moment().startOf('year'), moment().endOf('year')],
           'Ultimo Mese': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
           'Penultimo Mese': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
           'Terzultimo Mese': [moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')]           
        },
        opens: 'right',
        drops: 'down',
        buttonClasses: ['btn', 'btn-sm'],
        applyClass: 'btn-primary',
        cancelClass: 'btn-default',
        separator: ' to ',
        locale: {
            applyLabel: 'Conferma',
            cancelLabel: 'Annulla',
            fromLabel: 'Da',
            toLabel: 'A',
            customRangeLabel: 'Personalizzata',
            daysOfWeek: ['Do','Lu','Ma','Me','Gi','Ve','Sa'],
            monthNames: ['Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'],
            firstDay: 1
        }
    }, function(start, end, label) {
        //alert('2 ' + start);
        console.log(start.toISOString(), end.toISOString(), label);
        $('#reportrange span').html(start.format('DD, MMMM YYYY') + ' - ' + end.format('DD, MMMM YYYY'));
    });
    
    
    
   
});
  </script>
  
  <?php
    echo "<script>\n";
    echo "$(document).ready(function(){\n";
    
    echo "var drp = $('#reportrange').data('daterangepicker');\n";
    if ($startDate == null)
    {      
      echo "drp.setStartDate(moment().startOf('month'));";
    } 
    else
      echo "drp.setStartDate('" .$startDate. "');\n";
    if ($endDate == null)
    {
      echo "drp.setEndDate(moment().endOf('month'));";
    } 
    else      
      echo "drp.setEndDate('" .$endDate. "');\n";
    
    //echo " alert('startDate " .$startDate. "');\n";
    //echo " alert('endDate " .$endDate. "');\n";
    echo "$('#reportrange span').html(drp.startDate.format('DD, MMMM YYYY') + ' - ' + drp.endDate.format('DD, MMMM YYYY'));";
    echo "});\n";
    echo "</script>\n";
  ?>

</body>
</html>

 <?php
} else {
   //echo 'You are not authorized to access this page, please login. <br/>';

   redirectToLogin();
  exit;
   
}
?>