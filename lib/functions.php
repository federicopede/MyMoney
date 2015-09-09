<?php

function get_number_of_days_in_month($month, $year) {
    // Using first day of the month, it doesn't really matter
    $date = $year."-".$month."-1";
    return date("t", strtotime($date));
}

      function logger($content) {
            $file = 'log.txt';
            // The new person to add to the file
            // Write the contents to the file, 
            $content .= "\n";
            // using the FILE_APPEND flag to append the content to the end of the file
            // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
            file_put_contents($file, $content, FILE_APPEND | LOCK_EX);
      }
      
	function sec_session_start() {
        $session_name = 'sec_session_id'; // Imposta un nome di sessione
        $secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
        $httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
        ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
        $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
        session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly); 
        session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
        session_start(); // Avvia la sessione php.
        session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
	}	

	function login($email, $password, $mysqli) {
         //logger('login called ' .$email);
	   // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
	   if ($stmt = $mysqli->prepare("SELECT id, username, password, salt FROM members WHERE email = ? LIMIT 1")) { 
	      $stmt->bind_param('s', $email); // esegue il bind del parametro '$email'.
	      $stmt->execute(); // esegue la query appena creata.
	      $stmt->store_result();
	      $stmt->bind_result($user_id, $username, $db_password, $salt); // recupera il risultato della query e lo memorizza nelle relative variabili.
	      $stmt->fetch();
	      $password = hash('sha512', $password.$salt); // codifica la password usando una chiave univoca.
            //logger('$password    :' .$password);
            //logger('NumRows :' .$stmt->num_rows);
	      if($stmt->num_rows == 1) { // se l'utente esiste
	         // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.
	         if(checkbrute($user_id, $mysqli) == true) { 
	            // Account disabilitato
                  //logger('checkbrute!!');
	            // Invia un e-mail all'utente avvisandolo che il suo account è stato disabilitato.
	            return false;
	         } else {
               //logger('$db_password :' .$db_password);
               //logger('$password    :' .$password);
	         if($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
	            // Password corretta!            
	               $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
	 
	               $user_id = preg_replace("/[^0-9]+/", "", $user_id); // ci proteggiamo da un attacco XSS
	               $_SESSION['user_id'] = $user_id; 
	               $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
	               $_SESSION['username'] = $username;
	               $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
	               // Login eseguito con successo.
	               return true;    
	         } else {
	            // Password incorretta.
	            // Registriamo il tentativo fallito nel database.
	            $now = time();
	            $mysqli->query("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
	            return false;
	         }
	      }
	      } else {
	         // L'utente inserito non esiste.
	         return false;
	      }
	   }
	}
	
	function checkbrute($user_id, $mysqli) {
	   // Recupero il timestamp
	   $now = time();
	   // Vengono analizzati tutti i tentativi di login a partire dalle ultime due ore.
	   $valid_attempts = $now - (2 * 60 * 60); 
	   if ($stmt = $mysqli->prepare("SELECT time FROM login_attempts WHERE user_id = ? AND time > '$valid_attempts'")) { 
	      $stmt->bind_param('i', $user_id); 
	      // Eseguo la query creata.
	      $stmt->execute();
	      $stmt->store_result();
	      // Verifico l'esistenza di più di 5 tentativi di login falliti.
	      if($stmt->num_rows > 5) {
	         return true;
	      } else {
	         return false;
	      }
	   }
	}
	
	function login_check($mysqli) {
	   // Verifica che tutte le variabili di sessione siano impostate correttamente
	   if(isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
	     $user_id = $_SESSION['user_id'];
	     $login_string = $_SESSION['login_string'];
	     $username = $_SESSION['username'];     
	     $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
	     if ($stmt = $mysqli->prepare("SELECT password FROM members WHERE id = ? LIMIT 1")) { 
	        $stmt->bind_param('i', $user_id); // esegue il bind del parametro '$user_id'.
	        $stmt->execute(); // Esegue la query creata.
	        $stmt->store_result();
	 
	        if($stmt->num_rows == 1) { // se l'utente esiste
	           $stmt->bind_result($password); // recupera le variabili dal risultato ottenuto.
	           $stmt->fetch();
	           $login_check = hash('sha512', $password.$user_browser);
	           if($login_check == $login_string) {
	              // Login eseguito!!!!
	              return true;
	           } else {
	              //  Login non eseguito
	              return false;
	           }
	        } else {
	            // Login non eseguito
	            return false;
	        }
	     } else {
	        // Login non eseguito
	        return false;
	     }
	   } else {
	     // Login non eseguito
	     return false;
	   }
	}
      
      function redirect($extra) {
      
            /* 
             * Redirect to a different page in the current directory that was requested 
             */
            $host  = $_SERVER['HTTP_HOST'];
            //logger($host);
            $uri  = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
            $uri = "/money";
            //$extra = 'account/login.php';  // change accordingly
            //logger($uri);
            header("Location: http://$host$uri/$extra");      
      
      }	
      
      function redirectToLogin() {
            redirect('/account/login.php');
      }		
?>