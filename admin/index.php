 <?php 
 	ob_start();
	session_start();	
	
	require_once('../dts/configs.php');
	
	/*se existir o sair fechar sessão*/
	if($_GET['sair']){
		unset($_SESSION['userlogin']);
	}
	/*se não validar então mate a sessão*/
	
	/*se existir a sessão autocompletar com dashboard.php?exe=sis/home*/
	if($_SESSION['userlogin']){
		$login = $_SESSION['userlogin']['login'];
		$pass = $_SESSION['userlogin']['pass'];
		$readAut = read('usuarios', "WHERE login = '$login' AND senha = '$senha'");
		if($readAut){
			header('Location: dashboard.php');
		}else{
			unset($_SESSION['userlogin']);
		}
	}
 ?>
 
 
 <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>LOGIN | Curso Pro jQuery - Criando Interfaces</title>
<meta name="robots" content="noindex, nofollow" />

<link rel="stylesheet" type="text/css" href="css/login.css" />
<link href='http://fonts.googleapis.com/css?family=Dosis:200;400,600,800' rel='stylesheet' type='text/css'>
<link rel="icon" type="image/png" href="../tpl/images/upico.png"/>

<script type="text/javascript" src="../jsc/jquery.js"></script>
<script type="text/javascript" src="jsc/login.js"></script>

</head>

<body>

<div class="loginbox">
	<h1>Efetuar Login: <img src="img/loader.gif" alt="Carregando" title="Carregando" /></h1>
    <form name="login" action="" method="post">
    	<label class="label">
        	<span class="field">Usuário:</span>
            <input type="text" name="user" />
        </label>
                
        <div class="label">
        	<span class="field">Senha:</span>
            <input type="password" name="pass" class="pass" />
            <input type="submit" value="Logar-se" class="btn" />
        </div>        
    </form>
    
    
     
    <?php
    	if(isset($_GET['sair'])):
			echo '<div class="msg" style="display:block"><p class="sucesso"><strong>Você deslogou com sucesso!</strong></p></div>';	
		endif;
		
		if(isset($_GET['restrito'])):/*se existir a ocorrencia de GET restrito mostrar mensagem de acesso restrito*/
			echo '<div class="msg" style="display:block"><p class="erro"><strong>Acesso restrito. Favor logue-se!</strong></p></div>';	
		endif;
	?>
    
    <div class="msg"></div><!--/msg-->

    
</div><!--/login-box-->

<a class="backsite" href="../" title="Voltar ao site">voltar ao site</a>


</body>
</html>

<?php ob_end_flush(); ?>