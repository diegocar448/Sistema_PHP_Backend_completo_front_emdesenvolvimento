<div class="content home">
    <h1 class="location">Gerenciar Categorias <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location--> 
	   
	<div class="posts">  			     
			<?php
			if($_POST['search']):
				$pesquisar = mysql_real_escape_string($_POST['sposts']);
				$pesquisar = urlencode($pesquisar);
				header('Location: dashboard.php?exe=categorias/pesquisa&search='.$pesquisar); /*pesquisa pelo campo e adiciona na url*/
			endif;
			
			
			
			$page = mysql_real_escape_string($_GET['page']);
			$page = ($page == '' ? '1' : $page); /*vazio é primeira pagina senão será a própria pagina*/			
			$maximo = 5;
			$inicio = ($page * $maximo) - $maximo;/*ex:((1 * 1) - 1 = 1) logo LIMIT 0, $maximo*/
						
			$readSessoes = read('categorias',"WHERE sessao IS NULL LIMIT $inicio, $maximo"); /*Primeiro a leitura depois a paginação*/
			
			echo '<div class="paginator">';
				echo '<div class="paginator_form">';					
					echo '<form name="searchpost" action="" method="post">';
						echo '<input type="text" name="sposts" title="Pesquisar Categorias:" class="j_placeholder" />';
						echo '<input type="submit" name="search" value="Buscar" class="btn" />';			
					echo '</form>';
				echo '</div>';
				
				/*PAGINAÇÃO*/
				$link = 'dashboard.php?exe=categorias/index&page=';
				readPaginator('categorias', 'WHERE sessao IS NULL' , $maximo, $link, $page, '', '5','n');
				
			echo '</div><!-- /paginator -->';
			
			/*PAGINAÇÃO*/
			if($_GET['notfound']):
				/*NÃO EXISTE CATEGORIA SELECIONADA*/
				echo '<div class="notfound" style="margin-bottom:20px;"><h2>Desculpe, você tentou atualizar uma categoria que não existe. Tente usar as ações nas categorias abaixo!</h2></div><!--notfound-->';
			endif;					
			
			if(!$readSessoes):
				/*NÃO EXISTE*/
				if($page > '1'):
					$back = $page-1;
					header('Location: dashboard.php?exe=categorias/index&page='.$back);					
				else:
					echo '<div class="notfound"><h2>Desculpe, ainda não existem sessões ou categorias em seu site. Você pode começar agora criando suas sessões!</h2></div><!--notfound-->';
				endif;		
			else: /*ainda não categorias cadastradas*/
			
				echo '<ul class="content catli j_hover">';
				foreach($readSessoes as $sessoes):
				
				$contaCategorias = read('categorias', "WHERE sessao = '$sessoes[id]'");/*conta quantas sessoes tem*/
				$subcats = count($contaCategorias);
				
				$readPosts = read('posts', "WHERE sessao = '$sessoes[id]'");
				$posts = count($readPosts);/*contando os posts*/
				
					echo '<li class="li" id="'.$sessoes['id'].'">';
						if(file_exists('../uploads/'.$sessoes['capa']) && !is_dir('../uploads/'.$sessoes['capa'])):
							echo '<img src="../tim.php?src=../uploads/'.$sessoes['capa'].'&w=120&h=120" />';
						else:
							echo '<img src="../tim.php?src=../themes/'.THEME.'/images/notfound.png&w=120&h=120" />';
						endif;
						
						
						echo '<div class="info" style="width:636px;">';
							echo '<p class="title">'.$sessoes['categoria'].'</p>'; /*titulo da categoria*/
							echo '<p class="resumo">'.lmWord($sessoes['descricao'], 120).'</p>';/*lmword limita caracteres*/
							echo '<p class="categoria">'.date('d/m/Y H:i:s',strtotime($sessoes['cadastro'])).'</p>';
							echo '<span style="display:none;">';
							if($subcats == '0'):/*só aparece se o $subcats for igual a 0*/
								echo '<a title="Excluir" class="delete j_catdelete" id="'.$sessoes['id'].'" href="#">Excluir</a>';								
							endif;
								echo '<a title="Editar" class="edit" href="dashboard.php?exe=categorias/edit&catid='.$sessoes['id'].'">Editar</a> ';/*redireciona na url+id de sessões*/								
								echo '<a title="Ver" class="ver" href="../categoria/'.$sessoes['url'].'" target="_blank">Ver</a> ';/*adicionado o nome da categoria na url*/
							echo '</span>';
						echo '</div><!--/info-->';
						echo '<ul class="sub"> ';
							echo '<li><strong>'.$subcats.'</strong> subcategorias</li>';
							echo '<li><strong>'.$posts.'</strong> artigos</li>';
						echo '</ul>';
					echo '</li>';	
					
					/*CATEGORIAS DA SESSAO*/
					$readCategorias = read('categorias', "WHERE sessao = '$sessoes[id]' ORDER BY categoria ASC");/*entre em sessões e busque o indice id*/
					if($readCategorias):
						foreach($readCategorias as $cat):
						
							/*vai buscar no BD a sessao referente a $cat[sessao]*/
							$readSession = read('categorias', "WHERE id = '$cat[sessao]'");
							if($readSession): foreach($readSession as $session); endif; /*se não realizar leitura ficará em branco*/
							
							$readPosts = read('posts', "WHERE categoria = '$cat[id]'");
							$posts = count($readPosts);/*contando os posts*/							
							
							echo '<li class="li subli" id="'.$cat['id'].'">';
							
								if(file_exists('../uploads/'.$cat['capa']) && !is_dir('../uploads/'.$cat['capa'])):
									echo '<img src="../tim.php?src=../uploads/'.$cat['capa'].'&w=120&h=120" />';
								else:
									echo '<img src="../tim.php?src=../themes/'.THEME.'/images/notfound.png&w=120&h=120" />';										
								endif;
								echo '<div class="info" style="width:636px;">';
									echo '<p class="title">'.$session['categoria'].' &raquo;'.$cat['categoria'].'</p>'; /*titulo da categoria*/
									echo '<p class="resumo">'.lmWord($cat['descricao'], 120).'</p>';/*lmword limita caracteres*/
									echo '<p class="categoria">'.date('d/m/Y H:i:s',strtotime($cat['cadastro'])).'</p>';
									echo '<span style="display:none;">';
										if($posts == '0'):/*se não contar $posts aparece o excluir*/
											echo '<a title="Excluir" class="delete j_catdelete" id="'.$cat['id'].'" href="#">Excluir</a>';								
										endif;
										echo '<a title="Editar" class="edit" href="dashboard.php?exe=categorias/edit&catid='.$cat['id'].'">Editar</a> ';/*redireciona na url+id de sessões*/										
										echo '<a title="Ver" class="ver" href="../categoria/'.$cat['url'].'" target="_blank">Ver</a> ';/*adicionado o nome da categoria na url*/
									echo '</span>';
								echo '</div><!--/info-->';
								echo '<ul class="sub">';
									echo '<li><strong>'.$posts.'</strong> artigos</li>';
								echo ' </ul>';
							echo '</li>';
						endforeach;
					endif;			
				endforeach;	
				echo ' </ul><!--/content-->';	
				
			echo '<div class="paginator">';
				echo '<div class="paginator_form">';					
					echo '<form name="searchpost" action="" method="post">';
						echo '<input type="text" name="sposts" title="Pesquisar Categorias:" class="j_placeholder" />';
						echo '<input type="submit" name="search" value="Buscar" class="btn" />';			
					echo '</form>';
				echo '</div>';
				
				/*PAGINAÇÃO*/
				$link = 'dashboard.php?exe=categorias/index&page=';
				readPaginator('categorias', 'WHERE sessao IS NULL' , $maximo, $link, $page, '', '5','n');			
			echo '</div><!-- /paginator -->';		
			endif;								
			?>       
        
	</div><!--/posts -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->