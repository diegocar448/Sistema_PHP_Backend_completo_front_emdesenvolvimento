<?php
/*****************************
FUNÇÃO DO PRO PHP
FAZ A NAVEGAÇÃO AMIGÁVEL
*****************************/
function getHome(){
	$url = $_GET['url'];
	$url = explode('/', $url);
	$url[0] = ($url[0] == NULL ? 'index' : $url[0]);
	
		if(file_exists('themes/'.THEME.'/'.$url[0].'.php')){
			 require_once('themes/'.THEME.'/'.$url[0].'.php');
		}elseif(file_exists('themes/'.THEME.'/'.$url[0].'/'.$url[1].'.php')){
			 require_once('themes/'.THEME.'/'.$url[0].'/'.$url[1].'.php');
		}else{
			 require_once('themes/'.THEME.'/404.php');
		}
}
/*****************************
FUNÇÃO DO PRO PHP
SETA URL DA HOME
*****************************/
function setHome(){
	echo BASE;	
}
/*****************************
FUNÇÃO DO PRO PHP
INCLUE ARQUIVOS
*****************************/
function setArq($nomeArquivo){
	if(file_exists($nomeArquivo.'.php')){
		include($nomeArquivo.'.php');
	}else{
		echo 'Erro ao incluir <strong>'.$nomeArquivo.'.php</strong>, arquivo ou caminho não conferem!';	
	}
}
/*****************************
FUNÇÃO DO PRO PHP
GERA RESUMOS
*****************************/
function lmWord($string, $words = '100'){
	$string 	= strip_tags($string);
	$count		= strlen($string);
	
	if($count <= $words){
		return $string;	
	}else{
		$strpos = strrpos(substr($string,0,$words),' ');
		return substr($string,0,$strpos);
	}
	
}
/*****************************
FUNÇÃO DO PRO PHP
TRANFORMA STRING EM URL
*****************************/
function setUri($string){
	$a = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
	$b = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';	
	$string = utf8_decode($string);
	$string = strtr($string, utf8_decode($a), $b);
	$string = strip_tags(trim($string));
	$string = str_replace(" ","-",$string);
	$string = str_replace(array("-----","----","---","--"),"-",$string);
	return strtolower(utf8_encode($string));
}
/*****************************
FUNÇÃO DO PRO PHP
SOMA VISITAS
*****************************/	
function setViews($topicoId){
	$topicoId = mysql_real_escape_string($topicoId);
	$readArtigo = read('up_posts',"WHERE id = '$topicoId'");
	
	foreach($readArtigo as $artigo);
		$views = $artigo['visitas'];
		$views = $views +1;
		$dataViews = array(
			'visitas' => $views
		);
		update('up_posts',$dataViews,"id = '$topicoId'");
}
/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE CADASTRO NO BANCO
*****************************/
function create($tabela, array $datas){
	$fields = implode(", ",array_keys($datas));
	$values = "'".implode("', '",array_values($datas))."'";			
	$qrCreate = "INSERT INTO {$tabela} ($fields) VALUES ($values)";
	$stCreate = mysql_query($qrCreate) or die ('Erro ao cadastrar em '.$tabela.' '.mysql_error());
	
	if($stCreate){
		return true;
	}
}	
/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE LEITURA NO BANCO
*****************************/
function read($tabela, $cond = NULL){		
	$qrRead = "SELECT * FROM {$tabela} {$cond}";
	$stRead = mysql_query($qrRead) or die ('Erro ao ler em '.$tabela.' '.mysql_error());
	$cField = mysql_num_fields($stRead);
	for($y = 0; $y < $cField; $y++){
		$names[$y] = mysql_field_name($stRead,$y);
	}
	for($x = 0; $res = mysql_fetch_assoc($stRead); $x++){
		for($i = 0; $i < $cField; $i++){
			$resultado[$x][$names[$i]] = $res[$names[$i]];
		}
	}
	return $resultado;
}	
/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE EDIÇÃO NO BANCO
*****************************/	
function update($tabela, array $datas, $where){
	foreach($datas as $fields => $values){
		$campos[] = "$fields = '$values'";
	}
	
	$campos = implode(", ",$campos);
	$qrUpdate = "UPDATE {$tabela} SET $campos WHERE {$where}";
	$stUpdate = mysql_query($qrUpdate) or die ('Erro ao atualizar em '.$tabela.' '.mysql_error());

	if($stUpdate){
		return true;	
	}
	
}	
/*****************************
FUNÇÃO DO PRO PHP
FUNÇÃO DE DELETAR NO BANCO
*****************************/
function delete($tabela, $where){
	$qrDelete = "DELETE FROM {$tabela} WHERE {$where}";
	$stDelete = mysql_query($qrDelete) or die ('Erro ao deletar em '.$tabela.' '.mysql_error());
}
/*****************************
FUNÇÃO DO PRO PHP
ENVIA O EMAIL
*****************************/	
function sendMail($assunto,$mensagem,$remetente,$nomeRemetente,$destino,$nomeDestino, $reply = NULL, $replyNome = NULL){
	
	require_once('mail/class.phpmailer.php'); //Include pasta/classe do PHPMailer
	require_once('mail/class.smtp.php'); /*adiciona quando usar para o hotmail*/
	
	$mail = new PHPMailer(); //INICIA A CLASSE
	$mail->IsSMTP(); //Habilita envio SMPT
	$mail->SMTPAuth = true; //Ativa email autenticado
	$mail->IsHTML(true);
	
	$mail->Host = MAILHOST; //Servidor de envio
	$mail->Port = MAILPORT; //Porta de envio
	$mail->Username = MAILUSER; //email para smtp autenticado
	$mail->Password = MAILPASS; //seleciona a porta de envio
	
	$mail->From = utf8_decode($remetente); //remtente
	$mail->FromName = utf8_decode($nomeRemetente); //remtetene nome
	
	if($reply != NULL){
		$mail->AddReplyTo(utf8_decode($reply),utf8_decode($replyNome));	
	}
	
	$mail->Subject = utf8_decode($assunto); //assunto
	$mail->Body = utf8_decode($mensagem); //mensagem
	$mail->AddAddress(utf8_decode($destino),utf8_decode($nomeDestino)); //email e nome do destino
	
	if($mail->Send()){
		return true;
	}else{
		return false;
	}
}
/*****************************
FUNÇÃO DO PRO PHP
Paginação de resultados
*****************************/
function readPaginator($tabela, $cond, $maximos, $link, $pag, $width = NULL, $maxlinks = 4, $type = NULL, $div = NULL){
	$readPaginator = read("$tabela","$cond");
	$total = count($readPaginator);
	if($total > $maximos){
		$paginas = ceil($total/$maximos);
		if($width && $div){
			echo '<div class="paginator" style="width:'.$width.'">';
		}elseif($div){
			echo '<div class="paginator">';
		}
		if($type != 'n') : echo '<a href="'.$link.'1">Primeira Página</a>'; endif;
		for($i = $pag - $maxlinks; $i <= $pag - 1; $i++){
			if($i >= 1){
				echo '<a href="'.$link.$i.'">'.$i.'</a>';
			}
		}
		echo '<span class="atv">'.$pag.'</span>';
		for($i = $pag + 1; $i <= $pag + $maxlinks; $i++){
			if($i <= $paginas){
				echo '<a href="'.$link.$i.'">'.$i.'</a>';
			}
		}
		if($type != 'n') : echo '<a href="'.$link.$paginas.'">Última Página</a>'; endif;
		if($div) : echo '</div><!-- /paginator -->'; endif;
	}
}
/*****************************
FUNÇÃO DO PRO PHP
IMAGE UPLOAD
*****************************/
function uploadImage($tmp, $nome, $width, $pasta){
	$ext = substr($nome,-3);
	
	switch($ext){
		case 'jpg': $img = imagecreatefromjpeg($tmp); break;
		case 'png': $img = imagecreatefrompng($tmp); break;
		case 'gif': $img = imagecreatefromgif($tmp); break;	
	}		
	$x = imagesx($img);
	$y = imagesy($img);
	$height = ($width*$y) / $x;
	$nova   = imagecreatetruecolor($width, $height);
	
	imagealphablending($nova,false);
	imagesavealpha($nova,true);
	imagecopyresampled($nova, $img, 0, 0, 0, 0, $width, $height, $x, $y);

	switch($ext){
		case 'jpg': imagejpeg($nova, $pasta.$nome, 100); break;
		case 'png': imagepng($nova, $pasta.$nome); break;
		case 'gif': imagegif($nova, $pasta.$nome); break;	
	}
	imagedestroy($img);
	imagedestroy($nova);
}
/*****************************
FUNÇÃO DO PRO PHP
FORMATA DATA EM TIMESTAMP
*****************************/	
function formDate($data){
	$timestamp = explode(" ",$data);
	$getData = $timestamp[0];
	$getTime = $timestamp[1];
	
		$setData = explode('/',$getData);
		$dia = $setData[0];
		$mes = $setData[1];
		$ano = $setData[2];
		
	if(!$getTime):
		$getTime = date('H:i:s');
	endif;
		
	$resultado = $ano.'-'.$mes.'-'.$dia.' '.$getTime;
	
	return $resultado;
	
}
/*****************************
FUNÇÃO DO PRO PHP
VALIDA O EMAIL
*****************************/	
function isMail($email){
	if(preg_match('/[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\.\-]+\.[a-z]{2,4}$/',$email)){
		return true;
	}else{
		return false;
	}
}

/*****************************
FUNÇÃO DO GRAVATAR
PEGA AVATAR DE USUÁRIOS
*****************************/	
function gravatar( $email, $s = 180, $d = 'mm', $r = 'g', $img = false, $atts = array() ) {
    $url = 'https://www.gravatar.com/avatar/';
    $url .= md5( strtolower( trim( $email ) ) );
    $url .= "?s=$s&d=$d&r=$r";
    if ( $img ) {
        $url = '<img src="' . $url . '"';
        foreach ( $atts as $key => $val )
            $url .= ' ' . $key . '="' . $val . '"';
        $url .= ' />';
    }
    return $url;
}


/*****************************
FUNÇÃO CONTROLE DE ACESSO E GESTÃO DE ESTATÍSTICAS
*****************************/
function viewManager(){
	if(!$_SESSION['userlogin']):
		
		/*LER E CRIAR ESTATÍSTICAS DO DIA*/
		$dayOfManager		= date('Y-m-d');
		$readSiteManager	= read('siteviews',"WHERE data = '$dayOfManager'");	/*o campo data tem que ser igual a data atual do servidor*/
		if(!$readSiteManager):	/*se não retorna resultados criar registro na tabela*/	
			$createDay = array('data' => $dayOfManager, 'usuarios' => '1', 'visitas' => '1', 'pageviews' => '0');
			create('siteviews', $createDay);
			header('Location: '.$_SERVER['REQUEST_URI']);/* PHP_SELF ou REQUEST_URI*/
		else:
			foreach($readSiteManager as $sm);
			
			/*CONFERE E ATUALIZA PAGEVIEWS*/
			$pageViewsMais		= $sm['pageviews'] + 1;
			$updatePageViews	= array('pageviews' => $pageViewsMais);
			update('siteviews', $updatePageViews, "data = '$dayOfManager'");
			
			/*CONFERE E ATUALIZA VISITAS*/
			if(!$_SESSION['useracess']['sesid']):
				$_SESSION['useracess']['sesid']   	 = session_id();/*cria um ID unico para sessão criada*/
				$_SESSION['useracess']['startview']	 = time();
				$_SESSION['useracess']['endview']    = time() + 1200; /* 60*20(minutos) quando foi criado e em quanto tempo vai matar a sessão 1200=20minutos*/
				$_SESSION['useracess']['userip'] 	 = $_SERVER['REMOTE_ADDR']; /*pegando o ip do usuario REMOTE_ADDR*/
				$_SESSION['useracess']['userurl']    = $_SERVER['REQUEST_URI'];
				
				$cadUserOn = array( /*criar o cadastro a partir dessa variável*/
					"sesid"			    => $_SESSION['useracess']['sesid'],
					"startview"			=> $_SESSION['useracess']['startview'],
					"endview"			=> $_SESSION['useracess']['endview'],
					"userip"			=> $_SESSION['useracess']['userip'],
					"userurl"			=> $_SESSION['useracess']['userurl']				
				);
				
				create('useronline', $cadUserOn);
				
				$visitasMais = $sm['visitas'] + 1;				
				$updateVisitas = array('visitas' => $visitasMais);				
				update('siteviews', $updateVisitas, "data = '$dayOfManager'");			
				
			elseif($_SESSION['useracess']['endview'] < time()):
				unset($_SESSION['useracess']); /*matar a sessão   ||   useracess têm  a função de armazenar a função*/
			else:
				$_SESSION['useracess']['userurl'] = $_SERVER['REQUEST_URI'];
				$_SESSION['useracess']['endview'] = time() + 1200; /*caso a navegação continue ativa a sessão continuará*/ 
				
				$updateUserOn = array(
					"userurl"	=> $_SESSION['useracess']['userurl'],
					"endview"   => $_SESSION['useracess']['endview']			
				);
				
				$sesid = $_SESSION['useracess']['sesid'];
				update('useronline', $updateUserOn,"sesid = '$sesid'");
				
			endif;	
			
			/*REMOVE USUÁRIOS EXPIRADOS*/
			$timeRemove = time();
			delete('useronline',"endview < '$timeRemove'");/*startview for igual a endview então expirar (24h)*/
			
			
			/*CONFERE E ATUALIZA USUARIOS*/	
			if(!$_COOKIE['MyContentUserAcess']):
				setcookie('MyContentUserAcess',time(),time()+86400); /*60*60*24 (24 horas)*/
				
				$usuariosMais		= $sm['usuarios'] + 1;
				$updateUsuarios     = array('usuarios' => $usuariosMais);				
				update('siteviews', $updateUsuarios, "data = '$dayOfManager'");					
			endif;
			
			
		
		endif;/*Leu estástisticas*/

	endif;/*Verifica login*/
}




/*****************************
GERA SEO E SOCIAL
*****************************/
function getSeo($title, $content, $url = NULL, $image = NULL){ /*NULL têm que ser maiuscula*/
	$title 		= lmWord($title,'70');
	$content 	= lmWord($content,'160'); /*limitar em 160 caracteres*/
	$content	= utf8_encode(html_entity_decode($content)); /*renderizar para utf8 pq o tinymce não faz isso então forçamos com a função*/		
	$url 		= BASE.'/'.$url;
	
	$pasta		= 'uploads/'; 
	$default	= BASE.'/themes/'.THEME.'/images/siteavatar.png';
	$image		= ($image && file_exists($pasta.$image) && !is_dir($pasta.$image) ? BASE.'/'.$pasta.$image : $default);
	
	//NORMAL PAGE
	$result  = '<title>'.$title.'</title> '."\n";
	$result .= '<meta name="description" content="'.$content.'"/>'."\n"; 
	$result .= '<meta name="robots" content="index, follow" />'."\n";  /*define que "index" é um pagina inicial e o "follow" deve ser seguido pelo mecanismos de pesquisas*/
	$result .= '<link rel="canonical" href="'.$url.'">'."\n";  /*define de como queremos que os mecanismos de pesquisa exibam nossa pagina*/
	$result .= "\n"; /*para renderizar é obrigatório usar "" aspas duplas*/
	
	//FACEBOOK
	$result .= '<meta property="og:site_name" content="'.SITENAME.'" />'."\n";
	$result .= '<meta property="og:locale" content="pt_BR" />'."\n";
	$result .= '<meta property="og:title" content="'.$title.'" />'."\n";
	$result .= '<meta property="og:description" content="'.$content.'" />'."\n";
	$result .= '<meta property="og:image" content="'.$image.'" />'."\n";	
	$result .= '<meta property="og:url" content="'.$url.'" />'."\n";
	$result .= '<meta property="og:type" content="article" />'."\n"; /*para Open Graph identificar que isso é uma postagem*/
	$result .= "\n"; /*para renderizar é obrigatório usar "" aspas duplas*/
	
	//ITEM GROUP (TWITTER)
	$result .= '<meta itemprop="name" content="'.$title.'">'."\n";
	$result .= '<meta itemprop="description" content="'.$content.'">'."\n";
	$result .= '<meta itemprop="url" content="'.$url.'">'."\n";
	return $result;

} 
