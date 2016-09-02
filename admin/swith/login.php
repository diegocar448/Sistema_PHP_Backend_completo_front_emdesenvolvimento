<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
 	ob_start();
	session_start();	
	
	require_once('../../dts/configs.php');
	
	$acao = mysql_real_escape_string($_POST['acao']);
	
	switch ($acao){
		case 'login':
			$user = mysql_real_escape_string($_POST['user']);
			$pass = mysql_real_escape_string($_POST['pass']);
			
			if(!$user || !$pass){
				echo 'erroempty';
			}else{		
				$senha = md5($pass);							
				$readUser = read('usuarios', "WHERE login = '$user' AND senha = '$senha'");
				if($readUser):
					foreach($readUser as $userlogin);
					$_SESSION['userlogin'] = $userlogin;
					echo 'sucess';
				else:
					echo 'errosenha';
				endif;
			}
			
		break;
		default:
			echo 'Error';	
	}
	
	
	
	
	
	ob_end_flush();