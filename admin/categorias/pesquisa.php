<div class="content home">
	<?php
		$search = mysql_real_escape_string($_GET['search']);
		$search = urldecode($search);/*resultado retornando do que digitado em $search*/	
	?>


  

    <h1 class="location">Pesquisar <strong><?php echo $search; ?></strong> em categorias <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location--> 
	   
	<div class="posts">  			     
			<?php
			if($_POST['search']):
				$pesquisar = mysql_real_escape_string($_POST['sposts']);
				$pesquisar = urlencode($pesquisar);
				header('Location: dashboard.php?exe=categorias/pesquisa&search='.$pesquisar); /*pesquisa pelo campo e adiciona na url*/
			endif;	
			
			
			$page = mysql_real_escape_string($_GET['page']);
			$page = ($page == '' ? '1' : $page); /*vazio é primeira pagina senão será a própria pagina*/			
			$maximo = 10;
			$inicio = ($page * $maximo) - $maximo;/*ex:((1 * 1) - 1 = 1) logo LIMIT 0, $maximo*/
			
			/*variáveis para caseup e o casedown*/
			
			$searchL = mb_strtolower($search, 'UTF-8');/*com codificação UTF-8*/
			$searchU = mb_strtoupper($search, 'UTF-8');						
			$readPesquisa = read('categorias',"WHERE categoria LIKE '%$searchL%' OR categoria LIKE '%$searchU%' OR descricao LIKE '%$searchL%' OR descricao LIKE '%$searchU%' LIMIT $inicio, $maximo"); /*Primeiro a leitura depois a paginação*/
			
			
			echo '<div class="paginator">';
				echo '<div class="paginator_form">';
					echo '<img src="img/loader.gif" alt="Carregando..." class="load" title="Carregando..." />';
					echo '<form name="searchpost" action="" method="post">';
						echo '<input type="text" name="sposts" title="Pesquisar Categorias:" class="j_placeholder" />';
						echo '<input type="submit" name="search" value="Buscar" class="btn" />';			
					echo '</form>';
				echo '</div>';
				
				/*PAGINAÇÃO*/
				$link = 'dashboard.php?exe=categorias/pesquisa&search='.urlencode($search).'&page=';
				readPaginator('categorias', "WHERE categoria LIKE '%$searchL%' OR categoria LIKE '%$searchU%' OR descricao LIKE '%$searchL%' OR descricao LIKE '%$searchU%'", $maximo, $link, $page, '', '5','n');
				
			echo '</div><!-- /paginator -->';
			
			/*PAGINAÇÃO*/
			
			
			
			if(!$readPesquisa):
				/*NÃO EXISTE*/
				echo '<div class="notfound"><h2>Desculpe, não encontramos sessões ou categorias para sua pesquisa. Favor tente outros termos! Obrigado!</h2></div><!--notfound-->';
						
			else: /*ainda não categorias cadastradas*/
			
				echo '<ul class="content catli">';
				foreach($readPesquisa as $pesquisa):
				
				$contaCategorias = read('categorias', "WHERE sessao = '$pesquisa[id]'");/*conta quantas sessoes tem*/
				$subcats = count($contaCategorias);
				
				$readPosts = read('posts', "WHERE sessao = '$pesquisa[id]' OR categoria = '$pesquisa[id]'");
				$posts = count($readPosts);/*contando os posts*/
				
				
					/*se for sessão em branco então é categoria*/
					$tipo = ($pesquisa['sessao'] != '' ? 'Categoria' : 'Sessão');											
								
					echo '<li class="li"  id="'.$pesquisa['id'].'">';
					
					if($pesquisa['capa'] && is_file('../uploads/'.$pesquisa['capa'])):
						echo '<img src="../tim.php?src=tpl/_gbt/1.jpg&w=120&h=120" />';
					else:
						echo '<img src="../tim.php?src=../themes/'.THEME.'/images/notfound.png&w=120&h=120" />'
					endif;						
						
						echo '<div class="info" style="width:636px;">';
							echo '<p class="title">'.$pesquisa['categoria'].'</p>'; /*titulo da categoria*/
							echo '<p class="resumo">'.lmWord($pesquisa['descricao'], 120).'</p>';/*lmword limita caracteres*/
							echo '<p class="categoria">'.date('d/m/Y H:i:s',strtotime($pesquisa['cadastro'])).'</p>';
							echo '<span>';
								if($subcats == '0' && $posts == '0'):/*sem posts ou sem categoria entre*/
									echo '<a title="Excluir" class="delete j_catdelete" id="'.$pesquisa['id'].'" href="#">Excluir</a>';								
								endif;
								echo '<a title="Editar" class="edit" href="dashboard.php?exe=categorias/edit&catid='.$pesquisa['id'].'">Editar</a> ';/*redireciona na url+id de sessões*/																		
								echo '<a title="Ver" class="ver" href="../categoria/'.$pesquisa['url'].'" target="_blank">Ver</a> ';/*adicionado o nome da categoria na url*/
							echo '</span>';
						echo '</div><!--/info-->';
						echo '<ul class="sub">';
							echo '<li>TIPO: <strong>'.$tipo.'</strong></li>';/*identificar se é uma categoria ou uma sessão*/				
						echo '</ul>';
					echo '</li>';	
					
						
				endforeach;	
				echo ' </ul><!--/content-->';	
			
			/*PAGINAÇÃO*/	
			echo '<div class="paginator">';
				echo '<div class="paginator_form">';
					echo '<img src="img/loader.gif" alt="Carregando..." class="load" title="Carregando..." />';
					echo '<form name="searchpost" action="" method="post">';
						echo '<input type="text" name="sposts" title="Pesquisar Categorias:" class="j_placeholder" />';
						echo '<input type="submit" name="search" value="Buscar" class="btn" />';			
					echo '</form>';
				echo '</div>';
				
				/*PAGINAÇÃO*/
				$link = 'dashboard.php?exe=categorias/pesquisa&search='.urlencode($search).'&page=';
				readPaginator('categorias', "WHERE categoria LIKE '%$searchL%' OR categoria LIKE '%$searchU%' OR descricao LIKE '%$searchL%' OR descricao LIKE '%$searchU%'", $maximo, $link, $page, '', '5','n');
				
			echo '</div><!-- /paginator -->';	
			endif;								
			?>       
        
	</div><!--/posts -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->