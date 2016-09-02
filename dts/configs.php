<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//DEFINE BANCO DEDADOS
define('HOST','localhost');
define('USER','root');
/*define('USER','1184578');*/
define('PASS','');
/*define('PASS','32658515');*/
define('DBSA','projquery');
/*define('DBSA','1184578');*/


/*BASE DO SITE*/
define('BASE','http://localhost/projeto');/*podera ser alterado na hora de hospedar*/
define('IMAGEW','700px'); /*tamanho em pixels do artigo*/

//CONECTA NO BANCO
$conn = mysql_connect(HOST, USER, PASS) or die ('Erro ao conectar: '.mysql_error());
$dbsa = mysql_select_db(DBSA) or die ('Erro ao selecionar banco: '.mysql_error());


//INCLUI FUNÇÕES DO PRO PHP
require_once('functions.php');

function myAut($nivel = NULL){
		/*criar variaveis e depois consultar o banco, verificar se existe a sessão e as variáveis*/
		if($_SESSION['userlogin']){
			$id = $_SESSION['userlogin']['id'];
			$login = $_SESSION['userlogin']['login'];
			$senha = $_SESSION['userlogin']['senha'];
			$readAutUser = read('usuarios', "WHERE id = '$id' AND login = '$login' AND senha = '$senha'");
			if(!$readAutUser):/*se não retornar e pq fez a leitura da sessao, mas nao e valida, matar a sessao*/
				unset($_SESSION['userlogin']);
				header('Location: index.php?restrito=true');
			else:
				if($nivel && $nivel != $_SESSION['userlogin']['nivel']):
					header('Location: dashboard.php?exe=sis/403');		
				endif;
			endif;
		}else{
			/*se a sessão não existir redirecionar e adiciona depois de /admin/ index.php*/
			header('Location: index.php?restrito=true');
		}		
		
}	

/*DEFINE O TEMA A SER USADO*/
$config_readTheme = read('config_theme',"WHERE inuse = '1'");
/*se fizer a leitura recebe o foreach*/
if($config_readTheme):
	foreach($config_readTheme as $config_theme);
	define('THEME',$config_theme['pasta']);		
else:	
	define('THEME','default');	
endif;



/*DEFINE O SERVIDOR DE E-MAIL*/
$config_readMailServer = read('config_mailserver');
/*se fizer a leitura recebe o foreach*/
if($config_readMailServer):
	foreach($config_readMailServer as $config_mailserver);
	//DEFINE O SERVIDOR DE E-MAIL
	define('MAILUSER',$config_mailserver['email']);
	define('MAILPASS',$config_mailserver['senha']);
	define('MAILPORT',$config_mailserver['porta']);
	define('MAILHOST',$config_mailserver['server']);	
else:
	//DEFINE O SERVIDOR DE E-MAIL
	define('MAILUSER','null');
	define('MAILPASS','null');
	define('MAILPORT','null');
	define('MAILHOST','null');
endif;


/*DEFINE O SEO SOCIAL*/
$config_readSeoSocial = read('config_seosocial');
/*se fizer a leitura recebe o foreach*/
if($config_readSeoSocial):
	foreach($config_readSeoSocial as $config_seosocial);
	
	define('SITENAME',$config_seosocial['titulo']);
	define('SITEDESC',$config_seosocial['descricao']);
	define('FACEBOOK',$config_seosocial['facebook']);
	define('TWITTER',$config_seosocial['twitter']);	
else:
	define('SITENAME','null');
	define('SITEDESC','null');
	define('FACEBOOK','null');
	define('TWITTER','null');
endif;


/*ENDEREÇO E TELEFONE*/
$config_readEndTel = read('config_endtel');
/*se fizer a leitura recebe o foreach*/
if($config_readEndTel):
	foreach($config_readEndTel as $config_endtel);	
	define('ENDERECO',$config_endtel['endereco']);
	define('TELEFONE',$config_endtel['telefone']);
else:
	define('ENDERECO','null');
	define('TELEFONE','null');
endif;


