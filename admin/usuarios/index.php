<!-- verificando se a função existe -->
<?php if(function_exists(myAut)): myAut('1'); else: header('Location: ../dashboard.php'); die; endif; ?>
<div class="content home">
    <h1 class="location">Gerenciar Usuários<span><a href="#" class="j_adduser">Novo usuário</a></span></h1><!--/location-->
    
    <div class="usuarios">
	    
    	<ul class="users">
			<?php 
			 	$readUser = read('usuarios','ORDER BY nome ASC');
				foreach($readUser as $user):
					$nivel = ($user['nivel'] == '1' ? 'Super Admin' : 'Admin'); /*se for um 1 superadmin senão*/
					echo '<li id="'.$user['id'].'">';
						$atts = array('class' => 'avatar','title' => $user['nome'], 'alt' => $user['nome']);								
						$avatar = gravatar( $user['email'], $s = 180, $d = 'mm', $r = 'g', $img = true, $atts ); 
						echo $avatar;
						echo '<span class="nome">'.$user['nome'].'</span>';
						echo '<span class="nivel">'.$nivel.'</span>';
						echo '<span class="data">'.date('d/m/Y',strtotime($user['cadastro'])).'</span>';					
						echo '<div class="manage">';
							echo '<a class="edit j_useredit" id="'.$user['id'].'" href="#">Editar</a>'; /*pegar pelo id*/
							echo '<a class="dell j_userdelete" id="'.$user['id'].'" href="#excluir">Excluir</a>';
						echo '</div><!--/manage-->';
					echo '</li>';					
				endforeach;
			?>
        </ul><!--/users-->
        
    </div><!--/usuarios -->

<div class="clear"></div><!-- /clear -->
</div><!-- /content -->