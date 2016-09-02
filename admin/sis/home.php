<div class="content home">
    <h1 class="location">Painel Home <span><?php echo date('d/m/Y H:i');?></span></h1><!--/location-->
    
    <div class="left">
	<?php 
		function countViews($coluna, $tabela){	
			$qr 		= "SELECT SUM({$coluna}) as views FROM {$tabela}"; /*soma dados de qualquer tabela que você instanciar*/
			$st 		= mysql_query($qr) or die('Erro ao contar dados '.mysql_error());
			$views		= 0;		 
			$visitas 	= mysql_result($st, $views, "views"); /*$st começa com 0 e o $views vai contando*/
			
			if($visitas >= 1):
				$visitas = $visitas;
			else:
				$visitas = '0';		
			endif;
				return $visitas;
		}
		
		$visitas 	= countViews('visitas','siteviews');
		$usuarios 	= countViews('usuarios','siteviews');
		$pageviews 	= countViews('pageviews','siteviews'); /*média de paginas vistas por usuário*/
		$pageviews  = substr($pageviews/$usuarios,0,4);
		
		$posts 		= read('posts');
		$comments 	= read('comments');
		$categorias = read('categorias');		
	?>	
	
    
        <div class="boxleft estatisticas">
            <h3>Estatísticas totais:</h3>
            <div class="content">
            
                <ul class="views">
                    <li class="visitas"><?php echo $visitas; ?><small>visitas</small></li><!--/visitas-->
                    <li class="users"><?php echo $usuarios; ?><small>usuarios</small></li><!--/visitantes-->
                    <li class="media right"><?php echo $pageviews; ?><small>pageviews</small></li><!--/pageviews-->
                </ul><!--/views-->
                
                <ul class="conteudo">
                    <li class="topic"><?php echo count($posts); ?><small>posts</small></li><!--/artigos-->
                    <li class="comment"><?php echo count($comments); ?><small>comentários</small></li><!--/comentários-->
                    <li class="cats"><?php echo count($categorias); ?><small>categorias</small></li><!--/categorias-->
                </ul><!--/views-->
				
				<?php					
					$timeHome = time();
					delete('useronline',"endview < '$timeHome'"); /*deleta caso haja inativos*/
					$readHomeUserOnline = read('useronline', "WHERE endview > '$timeHome'"); /*se tiver alguem online faça o select e adicione na variável*/
				?>
				
                <a href="#" title="Ver usuários online agora!" class="useronline j_useronline"><strong class="j_useronlinerealtime"><?php echo count($readHomeUserOnline); ?></strong> Usuários Online Agora</a>
				
            </div><!--/content-->
        </div><!--/estatisticas-->
        
        
        <div class="boxleft trafego">
            <h3>Tráfego diário: <a href="#" title="Gerar Relatórios" class="j_geraestas">Tráfego</a></h3>
            <div class="content">
                <ul class="relatorio">
                    <li class="title">
                        <span class="date">Mês/Ano</span>
                        <span class="users">Usuários</span>
						<span class="views">Visitas</span>                        
                        <span class="pages">PageViews</span>
                    </li>
                    <?php 
					$readRelatorioH = read('siteviews',"ORDER BY id DESC LIMIT 7"); /*pega os ultimos 7*/
					if($readRelatorioH):
						foreach($readRelatorioH as $reH):
							$i++;
							$pageviewsH = substr($reH['pageviews']/$reH['usuarios'],0,4);							
							
							echo '<li';  if($i%2==0) echo ' class="color"';	echo '>';
								echo '<span class="date"><strong>'.date('d/m/Y',strtotime($reH['data'])).'</strong></span>';								
								echo '<span class="users">'.$reH['usuarios'].'</span>';
								echo '<span class="views">'.$reH['visitas'].'</span>';
								echo '<span class="pages">'.$pageviewsH.'</span>';
							echo '</li>';	
														
							$totalusuariosH	 +=	$reH['usuarios'];
							$totalvisitasH	 +=	$reH['visitas'];
							$totalpageviewsH +=	$reH['pageviews'];					
						
						endforeach;

						$totalpageviewsH = substr($totalpageviewsH/$totalusuariosH,0,4);			
					endif;
				?>
				
				<li style="background:#7DBEFF">
					<span class="date"><strong>7 DIAS</strong></span>
					<span class="users"><?php echo $totalusuariosH; ?></span>
					<span class="views"><?php echo $totalvisitasH; ?></span>					
					<span class="pages"><?php echo $totalpageviewsH; ?></span>
				</li>
				
				
				
                </ul><!--/relatorio-->
            </div><!--/content-->
        </div><!--/estatisticas-->
    
    </div><!--/left-->
    
    
    <div class="comments boxleft">
        <h3>Comentários: <a title="Comentários" href="dashboard.php?exe=comentarios/home">MODERAR</a></h3>
        <div class="content">
            <ul class="comentarios">
                <?php for($i=1;$i<=3;$i++):?>
                    <li class="pendente">
                        <img src="http://0.gravatar.com/avatar/4161e253b6b48b7bc34e7fbd5cdc232f?s=60&d=monsterid&r=G" />
                        <div class="commentitem">
                            <span>De <strong>Robson V. Leite</strong> sobre <strong>Shael Sonnen e Anderson Silva Spider...</strong>.</span>	
                            <p>In quis odio sit amet lectus porta blandit non at ante. Nam lobortis tempus odio, faucibus venenatis lorem consequat eget.</p>
                        </div><!--/commentitem-->
                    </li>
                <?php endfor;?>
                <?php for($i=1;$i<=4;$i++):?>
                    <li>
                        <img src="http://0.gravatar.com/avatar/4161e253b6b48b7bc34e7fbd5cdc232f?s=60&d=monsterid&r=G" />
                        <div class="commentitem">
                            <span>De <strong>Robson V. Leite</strong> sobre <strong>Shael Sonnen e Anderson Silva Spider...</strong>.</span>	
                            <p>In quis odio sit amet lectus porta blandit non at ante. Nam lobortis tempus odio, faucibus venenatis lorem consequat eget.</p>
                        </div><!--/commentitem-->
                    </li>
                <?php endfor;?>
            </ul><!--/comentários-->
        </div><!--/content-->
    </div><!--/ comments -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->