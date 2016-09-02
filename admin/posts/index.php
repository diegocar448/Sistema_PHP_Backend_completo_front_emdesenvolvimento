  <div class="content home">
    <h1 class="location">Gerenciar Posts <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->
    

	<?php if($_GET['notfound']) echo '<div class="postsnotfound">Oppss, você tentou editar um post que não existe!</div>'; /*aparece caso não exista o id*/	?>
	
	<div class="posts">
    			
		<?php 
		
		if($_POST['search']):
			$pesquisar = mysql_real_escape_string($_POST['sposts']);
			$pesquisar = urlencode($pesquisar);
			header('Location: dashboard.php?exe=posts/pesquisa&search='.$pesquisar); /*pesquisa pelo campo e adiciona na url*/
		endif;
			
			
			
			$page = mysql_real_escape_string($_GET['page']);
			$page = ($page == '' ? '1' : $page); /*vazio é primeira pagina senão será a própria pagina*/			
			$maximo = 10;
			$inicio = ($page * $maximo) - $maximo;/*ex:((1 * 1) - 1 = 1) logo LIMIT 0, $maximo*/
		
		
			$readPosts = read('posts', "ORDER BY status ASC, cadastro DESC LIMIT $inicio,$maximo"); /*vai pegar os inativos e depois vai pegar pela ordem do cadastro*/		
			
			echo '<div class="paginator">';
				echo '<div class="paginator_form">';					
					echo '<form name="searchpost" action="" method="post">';
						echo '<input type="text" name="sposts" title="Pesquisar Posts:" class="j_placeholder" />';
						echo '<input type="submit" name="search" value="Buscar" class="btn" />';			
					echo '</form>';
				echo '</div>';
				
				/*PAGINAÇÃO*/
				$link = 'dashboard.php?exe=posts/index&page=';
				readPaginator('posts', 'ORDER BY status ASC, cadastro DESC' , $maximo, $link, $page, '', '5','n');
				
			echo '</div><!-- /paginator -->';					
			
			
			
			if(!$readPosts):
				if($page > '1'):
					$back = $page-1;
					header('Location: dashboard.php?exe=posts/index&page='.$back);					
				else:			
					echo '<div class="postsnotfound" style="float:left; width:950px; margin-top:10px;">Ainda não existem posts cadastrados!</div> <!-- aparece caso não exista nada no banco -->';
				endif;					
			else:					
						
				echo '<ul class="content j_hover">';
					foreach($readPosts as $p):
					
					$readCategoria = read('categorias',"WHERE id = '$p[categoria]'");
					$readComments = read('comments',"WHERE post_id = '$p[id]'");
					
					/*se houver, mostre senão, 0*/
					$visitas = 	($p['views'] ? $p['views'] : '0');		
				
					foreach($readCategoria as $cat);
					
										
						echo '<li class="li'; if(!$p['status']) echo ' off'; echo '" id="'.$p['id'].'">'; /*apresenta of quando estiver inativo*/
							if($p['capa'] && is_file('../uploads/'.$p['capa'])): /*se não tiver imagem e se não existir um arquivo não exiba nada*/
								echo '<img src="../tim.php?src=../uploads/'.$p['capa'].'&w=200&h=120" />';
							else:
								echo '<img src="../tim.php?src=../themes/'.THEME.'/images/notfound.png&w=120&h=120" />'; /*exibir essa imagem se não houver capa*/
							endif;
							
							
							echo '<div class="info">';
								echo '<p class="title">'.lmWord($p['titulo'],60).'</p>'; /*função lmword para limitar o número de caracteres 60 no caso*/
								echo '<p class="resumo">'.lmWord($p['conteudo'],160).'...</p>';
																	/*coloca a base(url) mais a variavel url + target=blank para abrir nova aba*/
								echo '<p class="categoria"><a href="'.BASE.'/categoria/'.$cat['url'].'" target="_blank" style="text-transform:uppercase; font-size:12px">'.$cat['categoria'].'</a> &nbsp;&nbsp;&nbsp;&nbsp; '.date('d/m/y H:i',strtotime($p['cadastro'])).'</p>';
								echo '<span style="display:none">';
									echo '<a title="Excluir" class="delete j_postsdel" href="#" id="'.$p['id'].'">Excluir</a>';
									echo '<a title="Compartilhar" class="share j_postshare" href="'.BASE.'/ver/'.$p['url'].'">Compartilhar</a>';
									echo '<a title="Editar" class="edit" href="dashboard.php?exe=posts/edit&id='.$p['id'].'">Editar</a>';
									echo '<a title="Ver" target="_blank" class="ver" href="'.BASE.'/ver/'.$p['url'].'">Ver</a>';
								echo '</span>';  
							echo '</div><!--/info-->';
							echo '<ul class="sub">';
								echo '<li><strong>'.$visitas.'</strong> visitas</li>';
								echo '<li><strong>'.count($readComments).'</strong> comentários</li>';
							echo '</ul>';
						echo '</li>';	
					endforeach;				
				echo '</ul><!--/content-->';				
			endif;	       
		
		
			echo '<div class="paginator">';
				echo '<div class="paginator_form">';					
					echo '<form name="searchpost" action="" method="post">';
						echo '<input type="text" name="sposts" title="Pesquisar Posts:" class="j_placeholder" />';
						echo '<input type="submit" name="search" value="Buscar" class="btn" />';			
					echo '</form>';
				echo '</div>';
				
				/*PAGINAÇÃO*/
				$link = 'dashboard.php?exe=posts/index&page=';
				readPaginator('posts', 'ORDER BY status ASC, cadastro DESC' , $maximo, $link, $page, '', '5','n');
				
			echo '</div><!-- /paginator -->';
		?>       	
        
	</div><!--/posts -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->