<?php
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
 	ob_start();
	session_start();	
	
	require_once('../../dts/configs.php');	
	/*verificando se a função existe*/
	if(function_exists(myAut)): myAut('1'); else: header('Location: ../dashboard.php'); die; endif;
	
	$acao = mysql_real_escape_string($_POST['acao']);
	
	switch ($acao){	
	
		/*INICIA HOME*/
		/*USUÁRIOS ONLINE*/
		
		case 'home_usuariosonline':
			echo '<div class="user_title">';
				echo '<span>Usuários:</span>';
				echo '<span>StartView:</span>';
				echo '<span>EndView:</span>';
				echo '<span>IP:</span>';
				echo '<span>Local:</span>';
			echo '</div>';
			
			echo '<ul class="useronlinelist">';	
			$timethis = time();
			$readModalUserOnline = read('useronline', "WHERE endview > '$timethis'");/*se for maior que o tempo atual não mostrar caso contrario não expirou e mostre(esta online)*/
			if(!$readModalUserOnline):	
				echo '<li class="notfound">Não existem usuários online agora!</li>';
			
			else:		
				foreach($readModalUserOnline as $modalUserOnline):
				$i++;
					echo '<li'; if($i%2==0) echo ' class="line"'; echo '>';
						echo '<span>#'.$i.'</span>';
						echo '<span>'.date('H:i',$modalUserOnline['startview']).'hs</span>';
						echo '<span>'.date('H:i',$modalUserOnline['endview']).'hs</span>';
						echo '<span>'.$modalUserOnline['userip'].'</span>';
						echo '<span><a href="'.$modalUserOnline['userurl'].'" target="_blank">'.$modalUserOnline['userurl'].'</a></span>';
					echo '</li>';	
				endforeach;			
			endif;
			echo '</ul>';			
			echo '<div class="user_footer"><strong>'.count($modalUserOnline).'</strong> USUÁRIOS ONLINE AGORA!</div>';
		break;
		
		/*USUARIOS ONLINE REALTIME*/
		case 'home_userreal':
			$realTime = time();
			delete('useronline',"endview < '$realTime'"); /*deleta caso haja inativos*/			
			$readUserRealTime = read('useronline',"WHERE endview > '$realTime'");
			echo count($readUserRealTime);
		break;
		
		/*GERA ESTATÍSTICAS*/
		case 'home_estatiscas':
			$c['inicio'] = mysql_real_escape_string($_POST['inicio']);
			$c['final']  = mysql_real_escape_string($_POST['final']);
			if(in_array('',$c)):
				echo 'errempty';
			else:
				$inicio = explode('/',$c['inicio']);/*dar explode separando dia/mes/ano*/
				$inicio = $inicio['2'].'-'.$inicio['1'].'-'.$inicio['0'];
				
				$final = explode('/',$c['final']);/*dar explode separando dia/mes/ano*/
				$final = $final['2'].'-'.$final['1'].'-'.$final['0'];
				
				$readTrafic = read('siteviews',"WHERE data >= '$inicio' AND data <= '$final' ORDER BY id DESC");/*validar para que data de inicio seja sempre menor que a data final*/
					if(!$readTrafic):
						echo 'notfound';
					else:
						echo '<li class="title">';
							echo '<span class="date">Dia</span>';
							echo '<span class="users">Usuários</span>';
							echo '<span class="views">Visitas</span>';
							echo '<span class="pages">PageViews</span>';
						echo '</li>';				
						foreach($readTrafic as $re):				
							$i++;
							$pageviews = substr($re['pageviews']/$re['usuarios'],0,4);							
							
							echo '<li';  if($i%2==0) echo ' class="color"';	echo '>';
								echo '<span class="date"><strong>'.date('d/m/Y',strtotime($re['data'])).'</strong></span>';
								echo '<span class="users">'.$re['usuarios'].'</span>';
								echo '<span class="views">'.$re['visitas'].'</span>';
								echo '<span class="pages">'.$pageviews.'</span>';
							echo '</li>';
							
							$totalusuarios 	+=	$re['usuarios'];
							$totalvisitas 	+=	$re['visitas'];							
							$totalpageviews +=	$re['pageviews'];					
						endforeach;
		
						$totalpageviews = substr($totalpageviews/$totalusuarios,0,4);
						
						echo '<li class="title">';
							echo '<span class="date">TOTAL</span>';
							echo '<span class="users">Usuários</span>';
							echo '<span class="views">Visitas</span>';
							echo '<span class="pages">PageViews</span>';
						echo '</li>';
	
						echo '<li>';
							echo '<span class="date"><strong>'.count($readTrafic).' DIAS</strong></span>';
							echo '<span class="users">'.$totalusuarios.'</span>';
							echo '<span class="views">'.$totalvisitas.'</span>';
							echo '<span class="pages">'.$totalpageviews.'</span>';
						echo '</li>';	
						
					endif;					
				endif;		
		break;	
	
	
	
	
		/*INICIA POSTS*/
		/*read categorias*/
		case 'post_categoria_read':
			echo '<option value=""></option>';
			$readSessoes = read('categorias', 'WHERE sessao IS NULL ORDER BY categoria ASC');
			if($readSessoes):
				foreach($readSessoes as $sessao):
					echo '<option disabled="disabled" value="'.$sessao['id'].'">'.$sessao['categoria'].'</option>';
					$readCat = read('categorias', "WHERE sessao = '$sessao[id]' ORDER BY categoria ASC");
					if($readCat):
						foreach($readCat as $cat):
							echo '<option value="'.$cat['id'].'">&raquo; '.$cat['categoria'].'</option>';
						endforeach;
					endif;
				endforeach;
			endif;	
		break;					
		
		
		/*atualiza posts*/
		case 'posts_update':
			sleep(1); /*da uma parada e analisa os dados para passar ao jQuery*/
			$postid = mysql_real_escape_string($_POST['postid']);
			
			/*Dados em texto*/
			$f['titulo'] 	= mysql_real_escape_string($_POST['titulo']);
			$f['categoria'] = mysql_real_escape_string($_POST['categoria']);
			
			/*LE E RECUPERA SESSÃO*/
			$readSes	= read('categorias',"WHERE id = '$f[categoria]'");
			if($readSes): foreach($readSes as $ses); endif;
			$f['sessao']= $ses['sessao'];			
			
			$f['video'] 	= mysql_real_escape_string($_POST['video']);			
			
			/*REMOVE GAS*/
			/*evitando a execução de plugins como o gas usado em internet bank*/
			/* %(pesquisar por like) .(qualquer conteudo) | +(qualquer ocorrência) | ?(para continuar com mais conteudo)*/
			$string = '%<p><object id=".+?" width="0" height="0" type="application/gas.+?"></object></p>%';
			/*pegar o conteudo da $string e substituir por nada*/
			$f['conteudo']	= preg_replace($string,'',$_POST['conteudo']);	
			
					
			$f['conteudo']	= mysql_real_escape_string(trim($f['conteudo']));/*htmlspecialchars permite adicionar comandos php com segurança*/
			$f['cadastro'] 	= mysql_real_escape_string($_POST['cadastro']);
			$f['status'] 	= mysql_real_escape_string($_POST['status']);
			
			$f['url'] = setUri($f['titulo']);
			$f['cadastro'] = formDate($f['cadastro']);
			
			$verificaURL = read('posts',"WHERE id != '$postid' AND url = '$f[url]'");
			if($verificaURL):
				$f['url'] = $f['url'].'-'.$postid; /* verifica e valida a URL */
			endif;
			
			
			/*Validação da capa*/
			if($_FILES['capa']['tmp_name']):
			
				$readImg = read('posts', "WHERE id = '$postid'"); /*só vai ser disparado se realmente for enviar uma capa*/
				foreach($readImg as $img); /*faz o foreach lendo a capa*/
			
			
				/*CADASTRO*/
				$capa = $_FILES['capa']; /*pegando o FILES capa que esta certo já*/
				$pasta = '../../uploads/'; /*o pasta ja ta certo*/
										
				/*fazendo o if da imagem e excluido a antiga caso já exista*/
				if(file_exists($pasta.$img['capa']) && !is_dir($pasta.$img['capa'])): unlink($pasta.$img['capa']); endif;/*dentro da pasta o $img com indice capa // se não for um diretorio sera removida */
				
				/*gerador de pastas // pegando o mês e o ano para fazer a gestão das capas*/
				$m = date('m');
				$y = date('Y');
				if(!file_exists($pasta.'artigos')): mkdir($pasta.'artigos',0755); endif;/*se não existir ele criar*/
				if(!file_exists($pasta.'artigos/'.$y)): mkdir($pasta.'artigos/'.$y,0755); endif; /*se não existir a pasta ano então criar pasta ano*/
				if(!file_exists($pasta.'artigos/'.$y.'/'.$m)): mkdir($pasta.'artigos/'.$y.'/'.$m,0755); endif; /*se não existir a pasta mês então criar pasta mês*/
				
				$ext = strrchr($capa['name'],'.'); /*começa à direita do ponto da string*/  
				$ext = strtolower($ext); /*deixando minusculo e armazenando na variável extensão*/
				$baseDir = 'artigos/'.$y.'/'.$m.'/';
				$capaName = $baseDir.$postid.'-'.time().$ext;/*nome da imagem caminho + nome + extensão*/
				
				$extePerm = array('image/jpeg', 'image/pjpeg','image/png','image/gif'); /*extensões permitidas*/
				
				if(!in_array($capa['type'],$extePerm)):
					echo 'errext';
				else:
					$f['capa'] = $capaName;
					uploadImage($capa['tmp_name'], $capaName, 800, $pasta); /*função do function.php*/				
					echo ' sendcapa';/*recebe um espaço antes do sendcapa para não retornar 0 no indice*/
				endif;						
			endif;
			
			
			/*Validação da galeria*/		
			if($_FILES['gb']['tmp_name']){
				
				$count = count($_FILES['gb']['tmp_name']);
				
				$gb = $_FILES['gb'];
				$pasta = '../../uploads/';
				
				/*gerador de pastas // pegando o mês e o ano para fazer a gestão das capas*/
				$m = date('m');
				$y = date('Y');
				if(!file_exists($pasta.'gallery')): mkdir($pasta.'gallery',0755); endif;/*se não existir ele criar*/
				if(!file_exists($pasta.'gallery/'.$y)): mkdir($pasta.'gallery/'.$y,0755); endif; /*se não existir a pasta ano então criar pasta ano*/
				if(!file_exists($pasta.'gallery/'.$y.'/'.$m)): mkdir($pasta.'gallery/'.$y.'/'.$m,0755); endif; /*se não existir a pasta mês então criar pasta mês*/				

				for($i=0;$i<=$count;$i++):
					$ext = strrchr($gb['name'][$i],'.'); /*começa à direita do ponto da string*/  
					$ext = strtolower($ext); /*deixando minusculo e armazenando na variável extensão*/
					$baseDir = 'gallery/'.$y.'/'.$m.'/';
					$capaName = $baseDir.$postid.'-'.$i.time().$ext;
					
					$extePerm = array('image/jpeg', 'image/pjpeg','image/png','image/gif'); /*extensões permitidas*/
					
					if(in_array($gb['type'][$i],$extePerm)):/*se validar corretamente entre para fazer a gestão*/
						$cadastra = array('post_id' => $postid, 'imagem' => $capaName, 'uploaded' => date('Y-m-d H:i:s')); /*preencher tabela posts_gallery*/											
						create('posts_gallery',$cadastra);
						uploadImage($gb['tmp_name'][$i], $capaName, 800, $pasta); /*função do function.php*/				
						echo ' sendgb';/*recebe um espaço antes do sendgb para não retornar 0 no indice*/
					endif;								
				endfor;				
			}											
			update('posts',$f,"id = '$postid'");				
		break;
		
		
		/*cadastra post*/
		case 'posts_cadastro':
			$p['categoria'] = mysql_real_escape_string($_POST['categoria']);
			$p['titulo'] = mysql_real_escape_string($_POST['titulo']);
			if(in_array('',$p)): /*verificando se um dos campos acima não foi informado*/
				echo 'errempty';
			else:
				$p['url'] = setUri($p['titulo']);/*pegar o titulo que vai criar a url com a função setUri // cria a url*/ 
				$p['cadastro'] = date('Y-m-d H:i:s');	/*cria o cadastro*/
				
				$readSessao = read('categorias', "WHERE id = '$p[categoria]'"); foreach($readSessao as $ses);/* vai ler a sessão dessa categoria na tabela*/
				$p['sessao'] = $ses['sessao'];	/*define a variavel para alimentar*/			
				
				$readPost = read('posts',"WHERE url = '$p[url]'"); /*vai verificar se há posts com a mesma url*/				
				create('posts',$p);/*cadastra o artigo*/
				$postid = mysql_insert_id();/*obtêm o id do artigo*/				
				if($readPost):
					$u['url'] = $p['url'].'-'.$postid;/*criando uma url unica com o nome digitado mais o id (nomeid)*/
					update('posts',$u,"id = '$postid'");
				endif;			
				echo $postid;
			endif;			
		break;
		
		/*retorna capa do post*/
		case 'posts_getcapa':
			$postid = $_POST['thispost'];
			$readCapa = read('posts',"WHERE id = '$postid'");
			foreach($readCapa as $capa);
			echo $capa['capa']; /*retorna o nome da imagem*/
		break;
		
		/*retorna galeria do post*/
		case 'posts_getgallery':
			$postid = $_POST['thisgb'];
			$readGB = read('posts_gallery',"WHERE post_id = '$postid'");
			if($readGB):
				foreach($readGB as $gb):
					echo '<li id="'.$gb['id'].'">'; /*passar tambem com id para manipular o que vem do DOM*/
						echo '<a href="../uploads/'.$gb['imagem'].'" class="gb_open" title="Ver Imagem">';
							echo '<img src="tim.php?src=../uploads/'.$gb['imagem'].'&w=148&h=90" />';
						echo '</a>';
			  			echo '<a href="#" id="'.$gb['id'].'" class="galerrydel" title="Excluir">X</a>';							
					echo '</li>';					
				endforeach;
			endif;
		break;		
		
		/*deleta imagens da galeria do post*/
		case 'posts_gbdel':
			$imagemId = $_POST['imagem'];
			$readImagem = read('posts_gallery',"WHERE id = '$imagemId'");
			if($readImagem):
				foreach($readImagem as $img);					
					$pasta = '../../uploads/';
					if(file_exists($pasta.$img['imagem']) && !is_dir($pasta.$img['imagem'])): unlink($pasta.$img['imagem']); endif;/*deleta o arquivo da pasta*/				
					delete('posts_gallery', "id = '$imagemId'");/*deleta do banco*/				
			endif;
		break;
		
		/*deleta o post*/
		case 'posts_deleta':
			$postid = mysql_real_escape_string($_POST['id']);
			$pasta = '../../uploads/'; /*pasta que serão deletadas as imagens*/
			
			/*remove capa*/
			$readAt = read('posts',"WHERE id = '$postid'");
			if($readAt):
				foreach($readAt as $at);
				/*				se existir pasta 			não for um diretorio		então vou remover ele da pasta*/
				if(file_exists($pasta.$at['capa']) && !is_dir($pasta.$at['capa'])): unlink($pasta.$at['capa']); endif;
			endif;
			
			/*remover comments com o postid referente*/
			$readCo = read('comments',"WHERE post_id = '$postid'");
			if($readCo):
				delete('comments',"post_id = '$postid'");
			endif;			
			
			/*remove galeria*/
			$readGB = read('posts_gallery', "WHERE post_id = '$postid'");
			if($readGB):
				foreach($readGB as $gb):
					echo $gb['imagem'];
					/*				se existir pasta 			não for um diretorio		então vou remover ele da pasta*/
					if(file_exists($pasta.$gb['imagem']) && !is_dir($pasta.$gb['imagem'])): unlink($pasta.$gb['imagem']); endif;
					delete('posts_gallery',"id = '$gb[id]'");/*deletar a imagens do banco*/
				endforeach;			
			endif;	
			
			/*remove o artigo*/
			delete('posts',"id = '$postid'");				
		break;
		
		
		
	
	
		/*INICIA CATEGORIAS*/
		/*cadastra categoria*/
		case 'categoria_cadastro':
			$c['categoria'] = mysql_real_escape_string($_POST['categoria']);
			if(!$c['categoria']): /*se não alimentar entre aqui*/
				echo 'errempty';
			else:
				$readIsset = read('categorias',"WHERE categoria = '$c[categoria]' ");
				if($readIsset):
					echo 'errisset';
				else:
					$sessao = mysql_real_escape_string($_POST['sessao']);
					if($sessao) : $c['sessao'] = $sessao; endif;
					$c['url'] = setUri($c['categoria']);/*usando o nome da categoria para criar url*/
					$c['cadastro'] = date('Y-m-d H:i:s');
					create('categorias',$c);
					$idretorno = mysql_insert_id();/*vai sempre pegar o ultimo id ou seja o id acima*/
					echo $idretorno;
				endif;
			endif;
		break;	
		
		/*update categoria*/
		case 'categoria_update':
			$catid 				= mysql_real_escape_string($_POST['catid']);
			$c['categoria']		= mysql_real_escape_string($_POST['categoria']);
			$c['url']			= setUri($c['categoria'] );
			$c['descricao']		= mysql_real_escape_string($_POST['descricao']);
			$c['cadastro']		= mysql_real_escape_string($_POST['cadastro']);			
			$c['cadastro']		= formDate($c['cadastro']);
			
			if(in_array('',$c)):
				echo 'errempty';
			else:
				$readCat = read('categorias',"WHERE id != '$catid' AND (categoria = '$c[categoria]' OR url = '$c[url]' )");
				if($readCat):
					echo 'errisset';
				else:
					/*fazendo a leitura da propria categoria que esta se fazendo a gestão*/
					$readImg = read('categorias', "WHERE id = '$catid'");
					foreach($readImg as $img);
				
				
					if($_FILES['capa']['tmp_name']):
						/*CADASTRO*/
						$capa = $_FILES['capa'];
						$pasta = '../../uploads/';
												
						
						if(file_exists($pasta.$img['capa']) && !is_dir($pasta.$img['capa'])): unlink($pasta.$img['capa']); endif;/*dentro da pasta o $img com indice capa // se não for um diretorio sera removida */
						
						/*gerador de pastas*/
						$m = date('m');
						$y = date('Y');
						if(!file_exists($pasta.'categorias')): mkdir($pasta.'categorias',0755); endif;/*se não existir ele criar*/
						if(!file_exists($pasta.'categorias/'.$y)): mkdir($pasta.'categorias/'.$y,0755); endif; /*se não existir a pasta ano então criar pasta ano*/
						if(!file_exists($pasta.'categorias/'.$y.'/'.$m)): mkdir($pasta.'categorias/'.$y.'/'.$m,0755); endif; /*se não existir a pasta mês então criar pasta mês*/
						
						$ext = strrchr($capa['name'],'.'); /*começa à direita do ponto da string*/  
						$ext = strtolower($ext); /*deixando minusculo e armazenando na variável extensão*/
						$baseDir = 'categorias/'.$y.'/'.$m.'/';
						$capaName = $baseDir.'-'.time().$ext;/*nome da imagem caminho + nome + extensão*/
						
						$extePerm = array('image/jpeg', 'image/pjpeg','image/png','image/gif'); /*extensões permitidas*/
						
						if(!in_array($capa['type'],$extePerm)):
							echo 'errext';
						else:
							$c['capa'] = $capaName;
							uploadImage($capa['tmp_name'], $capaName, 500, $pasta); /*função do function.php*/
							update('categorias',$c,"id = '$catid'");
							echo $c['capa'];/*validando o envio de uma nova imagem*/
						endif;						
					else:
						update('categorias',$c,"id = '$catid'");
						echo $img['capa'];
					endif;				
				endif;
			endif;
			
			//print_r($c);
		break;
		
		
		
		/*deleta categoria*/		
		case 'categoria_deleta':		
			$catid = mysql_real_escape_string($_POST['catid']);
			$vCat = read('categorias', "WHERE sessao = '$catid'");
			$vPos = read('posts', "WHERE sessao = '$catid' OR categoria = '$catid'");
			if($vCat || $vPos):
				echo 'errisset';
			else:
				$pasta = '../../uploads/';				
				/*fazendo a leitura da propria categoria que esta se fazendo a gestão*/
				$readImg = read('categorias', "WHERE id = '$catid'");
				foreach($readImg as $img);
				if(file_exists($pasta.$img['capa']) && !is_dir($pasta.$img['capa'])): unlink($pasta.$img['capa']); endif;/*dentro da pasta o $img com indice capa // se não for um diretorio sera removida */			
				delete('categorias', "id = '$catid'");
			endif;						
		break;
		
		/*read categorias*/
		case 'categoria_read':
			echo '<option value=""></option>';
			$readSessoes = read('categorias', 'WHERE sessao IS NULL');
			if($readSessoes):
				foreach($readSessoes as $sessao):
					echo '<option value="'.$sessao['id'].'">'.$sessao['categoria'].'</option>';
					$readCat = read('categorias', "WHERE sessao = '$sessao[id]'");
					if($readCat):
						foreach($readCat as $cat):
							echo '<option disabled="disabled" value="'.$cat['id'].'">&raquo; '.$cat['categoria'].'</option>';
						endforeach;
					endif;
				endforeach;
			endif;	
		break;
		
		
		
		
		
		
		
		
		/*INICIA USUARIOS*/
		/*Cadastra  Edita usuarios*/
		case 'usuarios_manage':
			$u['nivel'] = mysql_real_escape_string($_POST['nivel']);
			$u['nome'] = mysql_real_escape_string($_POST['nome']);
			$u['email'] = mysql_real_escape_string($_POST['email']);
			$u['login'] = mysql_real_escape_string($_POST['login']);		
			$u['code'] = mysql_real_escape_string($_POST['pass']);
			$u['senha'] = md5($u['code']);
			$u['cadastro'] = date('Y-m-d H:i:s');
			if(in_array('',$u)): /* em array pesquisa por campos em branco na variavel $u */
				echo 'errempty';
			elseif(!isMail($u['email'])): /*verificar se o email esta no formato correto*/
				echo 'errmail';
			else:
				$exe = $_POST['exe'];
				if($exe == 'cadastro'):/*se for cadastro entre aqui se não entre no proximo*/
					$readUserIsset = read('usuarios',"WHERE email = '$u[email]' OR login = '$u[login]'");
					if($readUserIsset):
						echo 'errisset';
					else:
						create('usuarios',$u);
						echo $u['login'];
					endif;
				else:
					$userId = mysql_real_escape_string($_POST['id']);
					$readUserIsset = read('usuarios',"WHERE id != '$userId' AND (email = '$u[email]' OR login = '$u[login]')");
					if($readUserIsset):
						echo 'errisset';
					else:
						unset($u['cadastro']);/*não atualizar a data*/
						update('usuarios', $u, "id = '$userId'");
						echo $u['login'];
					endif;				
				endif;
			endif;		
		break;
		
		/*consulta e retorna formulário de edição de usuário*/
		case 'usuarios_consulta':
			$userid = mysql_real_escape_string($_POST['userid']);/*pegar o id do selecionado*/
			$readUserEdit = read('usuarios',"WHERE id = '$userid'");
			foreach($readUserEdit as $userEdit); /*finaliza com ; pq é só um usuario*/			
			echo '<h2>EDITAR USUÁRIO:</h2>';
			echo '<div class="content">';
				echo '<form name="edituser" action="" method="post">';
					echo '<label>';
						echo '<span>Nível:</span>';
						echo '<select name="nivel">';
							echo '<option'; 
								if($userEdit['nivel'] == '2') echo ' selected="selected" ';
								echo ' value="2">Admin</option>';
							echo '<option';
								if($userEdit['nivel'] == '1') echo ' selected="selected" ';
								echo ' value="1">Super Admin</option>';
						echo '</select>';
					echo '</label>';
					echo '<label>';
						echo '<span>Nome:</span>';
						echo '<input type="text" name="nome" value="'.$userEdit['nome'].'" />';
					echo '</label>';
					echo '<label>';
						echo '<span>E-mail:</span>';
						echo '<input type="text" name="email" value="'.$userEdit['email'].'" />';
					echo '</label>';
					echo '<label>';
						echo '<span>Login:</span>';
						echo '<input type="text" name="login" value="'.$userEdit['login'].'" />';
					echo '</label>';
					echo '<label>';
						echo '<span>Senha:</span>';
						echo '<input type="password" name="pass" value="'.$userEdit['code'].'" />';
					echo '</label>';
					
					
					echo '<input type="hidden" name="id" value="'.$userEdit['id'].'" />';
					echo '<input type="submit" value="Atualizar Usuario" class="btn" />';
					echo '<img src="img/loader.gif" class="load" alt="Carregando..." title="Carregando..." />';
				echo '</form>';
			echo '</div><!--/content-->';
			echo '<a href="#" class="closemodal j_formclose" id="edituser">X FECHAR</a>';		
		break;
		
		/*Deleta usuários*/
		case 'usuarios_deleta':
			$userId = mysql_real_escape_string($_POST['userid']);
			$readDelUser = read('usuarios', "WHERE id = '$userId'");
			foreach($readDelUser as $delUser);
			if($delUser['nivel'] != '1'):
				delete('usuarios',"id = '$userId'");
			else:
				$readSuper = read('usuarios', "WHERE nivel = '1'");
				$conta =  count($readSuper);				
				if($conta <= 1):
					echo 'errsuper';
				else:
					delete('usuarios',"id = '$userId'");
				endif;
			endif;
		break;		
		
		
		/*INICIA CONFIGURAÇÕES*/
		/*desativa manutencao*/
		case 'manutencao_desativa':	
			$dados = array("manutencao" => '0');
			update('config_manutencao',$dados,"manutencao = '1'");
		break;
		/*ativa manutencao*/
		case 'manutencao_ativa':	
			$dados = array("manutencao" => '1');
			update('config_manutencao',$dados,"manutencao = '0'");
		break;
		
		/*atualiza mailserver*/
		case 'mailserver_atualiza':
			$f['email'] = mysql_real_escape_string($_POST['email']);
			$f['senha'] = mysql_real_escape_string($_POST['senha']);
			$f['porta'] = mysql_real_escape_string($_POST['porta']);
			$f['server'] = mysql_real_escape_string($_POST['server']);
			if(in_array('',$f)):
				echo 'errempty';
			elseif(!isMail($f['email'])):
				echo 'errmail';
			else:
				update('config_mailserver',$f,'id = 1');			
			endif;
			
		break;
		
		/*testa envio de e-mail*/
		case 'mailserver_teste':
			$readMailServer = read('config_mailserver');
			foreach($readMailServer as $mail);	
			$assunto 		= 'Teste de MailServer';
			$mensagem 		= 'Seu servidor de e-mails foi configurado com sucesso. Parabéns. <br /><br /> Enviado em: '.date('d/m/Y H:i:s');/*enviando com a data evita o email receber como spam*/	
				
			$sendmail 		= sendMail($assunto,$mensagem,MAILUSER,SITENAME,MAILUSER,SITENAME);
			if($sendmail):
				echo $mail['email'];
			else:
				echo 'error';
			endif;
			
		break;
		
		/*atualiza seo e social do site*/
		case 'seosocial_atualiza':
			$s['titulo'] = mysql_real_escape_string($_POST['titulo']);
			$s['descricao'] = mysql_real_escape_string($_POST['descricao']);
			$s['facebook'] = mysql_real_escape_string($_POST['facebook']);
			$s['twitter'] = mysql_real_escape_string($_POST['twitter']);
			if(in_array('',$s)):
				echo 'errempty';			
			else:
				update('config_seosocial',$s,'id = 1');			
			endif;

		break;
		
		/*atualiza endereço e telefone*/
		case 'endtel_atualiza':
			$e['endereco'] = mysql_real_escape_string($_POST['endereco']);
			$e['telefone'] = mysql_real_escape_string($_POST['telefone']);		
			if(in_array('',$e)):
				echo 'errempty';			
			else:
				update('config_endtel',$e,'id = 1');			
			endif;
		break;
		
		/*cadastra novo tema*/
		case 'theme_cadastra':
			$t['nome'] = mysql_real_escape_string($_POST['nome']);
			$t['pasta'] = mysql_real_escape_string($_POST['pasta']);		
			if(in_array('',$t)):				
				echo 'errempty';			
			else:
				$t['cadastro'] = date('Y-m-d H:i:s');
				create('config_theme',$t);	
				echo $t['nome'];
			endif;
		break;
		
		/*theme read*/
		case 'theme_read':
			$readConfigTheme = read('config_theme',"ORDER BY inuse DESC, cadastro DESC");
			if($readConfigTheme):				
				echo '<li class="title">';
					echo '<span>Tema:</span>';
					echo '<span>Data:</span>';
					echo '<span>Criado em:</span>';
					echo '<span>-</span>';						
				echo'</li>';
				foreach($readConfigTheme as $configTheme):
				
				$pasta 		= '../../themes/'.$configTheme['pasta'];
				$valid		= (file_exists($pasta) && is_dir($pasta) ? '1' : '0');
				$stdir		= ($valid ? '<strong style="color:green">&radic;</strong>' : '<strong style="color:#900">&Chi;</strong>'); /*&radic simbolo de ok*/
				
					echo '<li id="'.$configTheme['id'].'"'; if($configTheme['inuse']) echo ' class="active"'; echo '>';
						echo '<span>'.$configTheme['nome'].'</span>';
						echo '<span>'.$stdir.' - '.$configTheme['pasta'].'</span>';
						echo '<span>'.date('d/m/Y', strtotime($configTheme['cadastro'])).'</span>';
						if(!$configTheme['inuse'] && $valid):
							echo '<span><a href="#" title="Ativar Tema: '.$configTheme['nome'].'" id="'.$configTheme['id'].'" class="j_themeactive">ATIVAR TEMA</a></span>';
						elseif(!$valid):
							echo '<span><a href="#" title="Deletar Tema: '.$configTheme['nome'].'" id="'.$configTheme['id'].'" class="j_themedelete">DELETAR TEMA</a></span>';
						else:								
							echo '<span>ATIVO!</span>';
						endif;													
					echo'</li>';
				endforeach;						
				endif;
		break;
		
		/*ativa o tema*/
		case 'theme_ativa':
			$resete = array('inuse' => 0);
			update('config_theme',$resete,"inuse = '1'");
			 
			$themeid 	= mysql_real_escape_string($_POST['id']);
			$ativa 		= array('inuse' => 1);			
			update('config_theme',$ativa,"id = '$themeid'");
		break;
		
		/*deleta o tema*/
		case 'theme_deleta':			 
			$themeid 	= mysql_real_escape_string($_POST['id']);
			$readTheme = read('config_theme', "WHERE id = '$themeid'");		
			if($readTheme): 
				foreach($readTheme as $theme);
				if($theme['inuse']): /*se estiver selecionado (inuse) então não pode apagar*/			
					echo 'erractive';
				else:
					delete('config_theme',"id = '$themeid'");
				endif;
			endif;
		break;
		
		
		/*PROXIMO*/
					
		default:	echo 'Error';	
	}		
	ob_end_flush();