



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>

<body>
<h1 align="center" class="heading">My Money</h1>
<br />
<?php
	
try {
	$gsearch = new SoapClient('http://localhost/money/search_engine.wsdl');
	print_r('ciao<br/>');
	print_r($gsearch->getWebUrl('google'));
} catch (SoapFault $e) {
	print_r($e);
}

?>

</body>

