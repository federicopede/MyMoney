<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Control Panel :: Student Information Panel:: Institute of Information Technology, BZU</title>
<link type="text/css" rel="stylesheet" href="../resources/css/base.css">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>Highcharts Pie Chart</title>
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script type="text/javascript" defer="defer">
       $(function() {   
            var options = {
                chart: {
                    renderTo: 'container',
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: true
                },
                title: {
                    text: ''
                },
                tooltip: {
                    formatter: function() {
                        //debugger
                        return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(2) +' % ' + this.y + '€'; ;
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            color: '#000000',
                            connectorColor: '#000000',
                            formatter: function() {
                                return '<b>'+ this.point.name +'</b>: '+ this.percentage.toFixed(0) +' %';
                            }
                        }
                    }
                },
                series: [{
                    type: 'pie',
                    name: 'Browser share',
                    data: []
                }]
            }
           
           
// <?php
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
// $json = json_encode($rows, JSON_NUMERIC_CHECK);
//             
//             
// mysql_close($con);
// ?> 
// 
//             options.series[0].data = $json;
//                  chart = new Highcharts.Chart(options);
             jQuery.getJSON("/money/admin/data.php", function(json) {
                 options.series[0].data = json;
                 chart = new Highcharts.Chart(options);
             });
            
            
            
        });   
        </script>
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
    </head>
    <body>
        
        <br />
<br />
<br />
<table align="center" cellpadding="0" align="center" class="form-table"   border="0">
  <tr>
      <td><h1 align="center" class="heading">Welcome to Admin Panel</h1>
      <p align="center">&nbsp;</p>
      
        
</td>
  </tr>
</table>
<div id="container" style="heigth:800px; margin: 0 auto"></div>
<h1 align="center" class="heading">&nbsp;</h1>
</body>
</html>