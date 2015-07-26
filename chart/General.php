<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>Charts</title>
    <!--<link rel="stylesheet" type="text/css" href="../shared/example.css" />-->

    <!-- GC -->

    <!--<script type="text/javascript" src="../../examples/shared/include-ext.js"></script>
    <script type="text/javascript" src="../../examples/shared/options-toolbar.js"></script>-->
    
        	<script type="text/javascript" src="../Resources/js/ext-all.js"></script>
	<link rel="stylesheet" type="text/css" href="../Resources/css/ext-all-neptune-debug.css"/>
    
    <script type="text/javascript" src="./example-data.js"></script>
    <script type="text/javascript" src="General.js"></script>
    <script type="text/javascript" src="store.js"></script>
    <script type="text/javascript" src="storeinout.js"></script>
    <script type="text/javascript" src="storecategory.js"></script>
    <script type="text/javascript" src="storedetails.js"></script>
    

    <style>
        .x-tip {
        background-color: #fff;
        border-radius: 0.5em;
        -moz-border-radius: 0.5em;
        -webkit-border-radius: 0.5em;
        border-radius: 0.5em;
        border: 1px solid rgba(134, 84, 41, 0.5);
        opacity: 0.95;
    }
    .x-tip-header {
        margin-bottom: 5px;
    }
    .x-tip .x-panel .x-panel-body.x-layout-fit {
        border: none;
    }
    .x-tip .x-panel.x-grid-section.x-panel-noborder.x-fit-item {
        margin: 0;
    }
    .x-tip .x-panel.x-box-item {
        top: 0 !important;
    }
    .x-tip-header-body .x-component.x-box-item {
        width: 100%;
        text-align: center;
    }
    .x-tip-body {
        text-shadow: none;
    }
    /*.x-panel {
            margin: 20px;
        }*/
    ul {
        margin-left: 10px;
    }
    ul li {
        display: block;
        font-weight: normal;
        color: #444;
        padding: 2px;
    }
    h1 {
        font-size: 18px;
        margin: 10px;
    }
    </style>
        
</head>
    <body id="docbody">
 <?php 
    include '../lib/functions.php';
    // Start the session
    session_start();
    
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
       ?>
    </body>
</html>
