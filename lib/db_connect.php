<?php 
	define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
	define("USER", "root@localhost"); // sec_user E' l'utente con cui ti collegherai al DB.
	define("PASSWORD", ""); // eKcGZr59zAa2BEWU Password di accesso al DB.
	define("DATABASE", "moneydb"); // Nome del database.
	$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
	//$mysqli = mysqli_connect("localhost","root@localhost","","MoneyDB");
	// Se ti stai connettendo usando il protocollo TCP/IP, invece di usare un socket UNIX, ricordati di aggiungere il parametro corrispondente al numero di porta.
?>