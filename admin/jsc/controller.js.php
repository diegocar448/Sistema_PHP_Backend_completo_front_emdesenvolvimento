<?php /*converter esse arquivo e proteger para não ter acesso*/
	session_start();
	require_once('../../dts/configs.php');
	if(!function_exists(myAut)):
		header('Location: dashboard.php');
		die;	
	endif;
	
	myAut();
	header("Content-Type: text/javascript;charset=utf-8");
?>



$(window).ready(function(){ 
	$('.loadsistem').fadeOut("fast", function(){ /*se carregou tudo tirar fundo preto e a caixa carregando*/
		$('.dialog').fadeOut("fast");
	});
});


$(function(){	
	/*VARIAVEIS GERAIS*/
	var url = 'swith/painel.php';


	/*EFEITOS DO MENU PRINCIPAL*/
	/*controla submenus*/
	$('.controle .li').mouseenter(function(){		
		$(this).find('.submenu').fadeIn("fast");
	}).mouseleave(function(){
		$(this).find('.submenu').slideUp("fast");
	});	
	
	/*GERAIS*/	
	/*Volta o Navegador*/
	$('.j_back').click(function(){
		window.history.back(); /*Voltar a pagina anterior*/		
		return false;
	});
	
	
	
	/*pega capa das categorias e posts*/
	$('.j_send').click(function(){ /*abrir janela para escolher imagem*/
		$('.j_capa').click().change(function(){
			$('.j_false').text($(this).val().replace('C:\\fakepath\\',"")); /*duas barra para evitar o erro de diagramação*/
		}).trigger('change');/*trigger para o jquery forçar o change para o Internet Explorer*/		
		return false;
	});
			
	
	
	/*placeholder do sistema*/
	$('.j_placeholder').each(function(){  /*vai percorrer 'each'-se clicar no campo de pesquisa aparece se clicar dentro desaparece */
		var place = $(this).attr('title');		
		$(this).val(place).css('color', '#CCC').click(function(){
			$(this).css('color','#fff');     /*quando sair do campo que a letra volte a ser branca novamente*/
			if($(this).val() == place){
				$(this).val('');
			}
		}).blur(function(){
			if($(this).val() == ''){
				$(this).val(place).css('color', '#CCC');
			}
		});	
	});
	
	 
	/*fecha modal*/
	/*closemodal fechar todas as modais*/
	$('.closemodal').click(function(){
		var closemodal = $(this).attr('id');	
		$('.' + closemodal).fadeOut("slow", function(){
			$('.dialog').fadeOut("fast");
		});
		return false;
	});
	
	/*fecha ajaxmodal*/
	$('.ajaxmsg').on('click','.j_ajaxclose',function(){
		var ajaxmodal = $(this).attr('id');	
		$('.' + ajaxmodal).fadeOut("slow", function(){
			$('.dialog').fadeOut("fast");
			$(this).attr('class', 'ajaxmsg msg');
		});
		return false;
	});
	
	/*fecha ajaxdial*/
	$('.ajaxmsg').on('click','.j_dialclose',function(){
		var ajaxmodal = $(this).attr('id');	
		$('.' + ajaxmodal).fadeOut("slow", function(){
			$(this).attr('class', 'ajaxmsg msg');
		});
		return false;
	});
	
	/*abre mensagens*/
	function myModal(id, tipo, content){
		var title = (tipo == 'accept' ? 'Sucesso:' : (tipo == 'error' ? 'Opsss' : (tipo == 'alert' ? 'Atenção:' : 'null')));
		if(title == 'null'){
			alert('Tipo deve ser: accept | error | alert');
		}else{
			$('.dialog').fadeIn("fast",function(){
				$('.ajaxmsg').addClass(id).addClass(tipo).html(
					'<strong class="tt">'+ title +'</strong>' +
        			'<p>'+ content +'</p>'+
    				'<a href="#" class="closedial j_ajaxclose"id="'+ id +'">X FECHAR</a>'				
				).fadeIn("slow");			
			});
		}			
	}
	
	/*abre diálogo*/
	function myDial(id, tipo, content){
		var title = (tipo == 'accept' ? 'Sucesso:' : (tipo == 'error' ? 'Opsss' : (tipo == 'alert' ? 'Atenção:' : 'null')));
		if(title == 'null'){
			alert('Tipo deve ser: accept | error | alert');
		}else{
			$('.ajaxmsg').addClass(id).addClass(tipo).html(
				'<strong class="tt">'+ title +'</strong>' +
				'<p>'+ content +'</p>'+
				'<a href="#" class="closedial j_dialclose"id="'+ id +'">X FECHAR</a>'				
			).fadeIn("slow");			
		}			
	}
	
	/*Efeito Hover*/
	$('.j_hover li').mouseover(function(){
		$(this).find('span').fadeIn("fast");	/*passou o mouse aparece botões*/
	}).mouseleave(function(){
		$(this).find('span').fadeOut("fast");	/*tirou o mouse desaparece botões*/
	});
	
	
	
	/*CONTROLA HOME*/
	/*gera usuarios online*/
	$('.j_useronline').click(function(){
		$.post(url,{acao:'home_usuariosonline'},function(resposta){ /*primeiro posta depois fazer abertura*/
						
			$('.dialog').fadeIn('fast',function(){
				$('.modaluseronline .content').html(resposta);
				$('.modaluseronline').queue(function(){
					$('.modaluseronline').fadeIn('slow');
				});
				$('.modaluseronline').dequeue();
			});	
		});			
	
		return false;
	});
	
	/*conta em tempo real*/	
	setInterval(function(){           /*função do javascript(setInterval) para atualizar em tempo real*/
		$.post(url,{acao:'home_userreal'},function(resposta){
			$('.j_useronlinerealtime').text(resposta);		
		});	
	},10000);
	
	
	
	
	/*fecha usuarios online*/
	$('.j_closeuseronline').click(function(){
		$('.modaluseronline').fadeOut("slow",function(){ /*fechar div modal*/
			$('.dialog').fadeOut("fast");
		});
		return false;
	});	
	
	
	/*gera trafego*/
	$('form[name="geradados"]').submit(function(){
		var forma = $(this);
		var dados = $(this).serialize() + '&acao=home_estatiscas'; /*faz o cadastro*/
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				forma.find('.load').fadeIn("fast");
			},
			success: function( datas ){							
				//alert(datas);
				if(datas == 'errempty'){
					myDial('home_errempty','error','Desculpe, para gerar um relatório é preciso informar as duas datas.</p><p><strong>Informe as datas. Obrigado!</strong>');
				}else if(datas == 'notfound'){
					myDial('home_notfound','alert','Não foi possivel encontrar resultados para o intervalo de datas informado.</p><p><strong>Favor tente outro periodo. Obrigado!</strong>');
				}else{
					$('.j_relatorio').fadeTo(500,'0.2',function(){
						$(this).html(datas);
						$(this).queue(function(){
							$(this).fadeTo(500,'1');
						});
						$(this).dequeue();
					});
				}
			},
			complete: function(){
				forma.find('.load').fadeOut("fast");
			}
		});
		
		return false;
	});	
	
	/*abre gerador*/
	$('.j_geraestas').click(function(){
		$('.dialog').fadeIn("fast",function(){
			$('.modaltrafego').fadeIn("slow");
		});
		return false;
	});

		
	/*fecha gerador*/
	$('.j_closetrafic').click(function(){
		$('.modaltrafego').fadeOut("slow",function(){
			$('.dialog').fadeOut("fast");
		});
		return false;
	});
	
	
	/*CONTROLA POSTS*/
	/*abre modal*/
	/*não permitir que ele siga a url clicada*/
	$('.j_addpost').click(function(){
		$.post(url, 'acao=post_categoria_read',function(resposta){
			$('form[name="cadnewpost"]').find('select').html(resposta);/*buscando formulário*/
		});
		$('.dialog').fadeIn("fast", function(){    /*abrir o fundo da box modal 'escurão'*/
			$('.newpost').fadeIn("fast");
		});  
		return false;
	});	
	
	
	
	/*cadastra posts*/
	$('form[name="cadnewpost"]').submit(function(){
		var forma = $(this);
		var dados = $(this).serialize() + '&acao=posts_cadastro'; /*faz o cadastro*/
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				forma.find('.load').fadeIn("fast");
			},
			success: function( datas ){							
				if(datas == 'errempty'){
					myDial('categoria_errempty','alert','Para cria um novo artigo selecione uma categoria e informe um titulo para o mesmo.</p><p><strong>Obrigado!</strong>');
				}else{
					$('.ajaxmsg').addClass('posts_accept accept').html(
						'<strong class="tt">Sucesso</strong>' +
						'<p>Seu artigo foi cadastrado com sucesso. Aguarde, estamos redirecionando para a gestão e alimentação do mesmo!</p><p><strong>Aguarde. Obrigado!</strong>'
					).fadeIn("slow");					
					window.setTimeout( function(){
						forma.find('input[name="titulo"]').val('');/*limpar campo*/
						$(location)	.attr('href','dashboard.php?exe=posts/edit&id=' + datas);
					} , 2000);
				}			
			},
			complete: function(){
				forma.find('.load').fadeOut("fast");
			}
		});
		return false;
	});	
	
	
	/*atualização de posts*/
	$('form[name="editpost"]').submit(function(){
		tinyMCE.triggerSave();	/*função nativa do tinyMCE // elimina o daley*/			
		var form = $(this);
		var bar = $('.j_editposts .progress');
		var per =  $('.j_editposts .progress .bar');
		$(this).ajaxSubmit({   /*ja vai comunicar o php com todos os dados serilizados*/
			url: 				url,
			data: 				{acao: 'posts_update'},
			beforeSubmit:		function(){
				$('.accept').fadeOut("fast"); 
				$('.j_editpostimgload').fadeIn("fast");	
				$('.j_editposts .title img').fadeIn("fast");	
			},
			uploadProgress:		function(evento, posicao, total, completo){
				var capa = form.find('.j_capa');
				var gbs = form.find('.j_gallery');
				
				if(capa.val() || gbs.val()){								
				
					var porcento = completo + '%';
					 $('.dialog').fadeIn("fast",function(){
						$('.j_editposts').fadeIn("slow", function(){						
							bar.fadeIn("fast", function(){
								per.width(porcento).text(porcento);
							});	
						});
					 });				 
				 
				}
			},
			success:			function(resposta){				
				var postid = form.find('input[name="postid"]').val();
				
				if(resposta.search('sendcapa') >= '1'){
					$.post(url, {acao: 'posts_getcapa', thispost: postid}, function(thiscapa){
						/*alert(thiscapa);*/
						$('.viewcapa').fadeTo(500,'0.2',function(){ /*dar o efeito de transparência*/
							$(this).find('img').fadeOut("fast",function(){ /*primeiro vai ocultar esse imagem*/
								$(this).attr('src','tim.php?src=../uploads/'+ thiscapa +'&h=42').fadeIn("fast");
							}); /*aqui já muda a capa que vai ser exibida*/
							$(this).find('a').attr('href','../uploads/'+ thiscapa);
							$(this).queue(function(){
								$(this).fadeTo(500,'1'); /*remover o efeito anterior*/
							});
							$(this).dequeue();
							form.find('.j_false').text('');	
							form.find('.j_capa').val('');		
						});
					});
				}
				
				if(resposta.search('sendgb') >= '1'){ /*gb = sendgallery // sendgb vai retorna a posição do array [0] ou [1] etc*/					
					$.post(url,{acao: 'posts_getgallery', thisgb: postid},function(thisgallery){ /*primeiro faz a postagem só depois se aplica os efeitos da galeria*/	 					
						$('.galerry ul').fadeTo(500,'0.2', function(){						
							$(this).html(thisgallery); /*alimentar a ul depois de clicar em atualizar*/
							$(this).queue(function(){ /*como não pode aplicar mais um function então usamos o queue para os efeitos da gallery voltar ao normal*/ 
								$(this).fadeTo(500,'1');/*fadeTo para tirar a transparência das imagens*/
							});
							$(this).dequeue();/*tira o efeito de transparência*/
						});						
					});					
					
					
					$('.j_gfalse').animate({width:'220px'},500,function(){ /*mudar de tamanho*/
						$(this).html('Imagens enviadas com sucesso!');						
					});							
					form.find('.j_gallery').val(''); /*depois de tudo zerar a val*/	
				}				
							
			},
			complete:			function(){
				$('.j_editpostimgload').fadeOut("fast");
				$('.j_editposts .title img').fadeOut("fast");				
				bar.fadeOut("fast",function(){
					$('.accept').fadeIn("fast");
				});
			}				
		});		
		return false;						
	});
	
	/*Deleta imagem da galeria*/
	$('.galerry ul').on('click', '.galerrydel', function(){
		var delid = $(this).attr('id'); /*pegar o id para fazer o delete*/
		$('.galerry ul li[id="'+ delid +'"]').css('background','red');/*ao clicar a borda fica vermelho*/
		$.post(url,{acao:'posts_gbdel',imagem:delid},function(retorna){ /*fazer post da acao posts_gbdel*/
			alert(retorna);
			window.setTimeout(function(){ /*dar um time antes do efeito de ocultar abaixo*/
			$('.galerry ul li[id="'+ delid +'"]').fadeOut("slow");/*ocultar a imagem*/
			},1000);
		});
		return false;
	});
	
	
	/*Abre imagem da galeria*/
	$('.galerry').on('click','.gb_open', function(){ /*usado para dar efeito modal da função Shadowbox no this*/
		Shadowbox.open(this);			
		return false;
	});	
	
	/*Fecha imagem de update*/
	$('.j_closeloadposts').click(function(){
		$('.j_editposts').fadeOut("fast",function(){
			$('.dialog').fadeOut("fast");
			$('.accept').fadeOut("fast");
			$('.progress').fadeIn("fast",function(){
				$(this).find('.bar').css('width','0%').text('0%');
			});			
		});
		return false;
	});
	
	
	
	
	/*Indica seleção de imagens da galeria*/
	$('.j_gsend').click(function(){
		$('.j_gallery').click().change(function(){
			var numFiles = $(this)[0].files.length;     /*conta esse no indice 0*/
			$('.j_gfalse').animate({width:'410px'},500,function(){ /*mudar de tamanho*/
				$(this).html('Você selecionou <strong>'+ numFiles +'</strong> arquivos. Atualize o post para enviar!');
			});
		});	
	});
	
	/*Marca o checkbox*/
	$('form[name="editpost"] .check').click(function(){ /*condição se esta ou não marcado*/
		if($(this).find('input').is(':checked')){
			$(this).css('background','#0C0'); /*check */
		}else{
			$(this).css('background','#999'); /*no check*/
		}	
	});
	
	if($('form[name="editpost"] .check input').is(':checked')){
			$('form[name="editpost"] .check').css('background', '#0C0'); /*check */
		}else{
			$('form[name="editpost"] .check').css('background','#999'); /*no check*/
		}	
		
	/*compartilha post no face*/
	$('.j_postshare').click(function(){
		var urlshare = $(this).attr('href');
		window.open('http://www.facebook.com/sharer.php?u=' + urlshare,'Pro jQuery',"width=500,height=400,status=yes,toolbar=no,menubar=no,location=no");/*facebook*/
			
		return false;
	});
	
	/*deleta o post*/
	$('.j_postsdel').click(function(){
		var postid = $(this).attr('id');
		var r = confirm('Deseja realmente deletar este artigo?'); /*exibir caixa com mensagem para confirmar*/
		if(r == true){
			$.post(url,{id: postid, acao: 'posts_deleta'}, function(retorna){
				var lidel = $('.content li[id="'+ postid +'"]');
				lidel.css("background",'red'); /*pintar a div*/
				lidel.queue(function(){
					window.setTimeout(function(){
						lidel.fadeOut("slow");
					},1000);
				});	
				lidel.dequeue();	
			});
		}
		return false;	
	});
	
		
		
		
	
	
	
	
	/*CONTROLA CATEGORIAS*/	
	/*abre modal*/	
	$('.j_addcat').click(function(){
		$.post(url, 'acao=categoria_read',function(resposta){
			$('form[name="cadnewcat"]').find('select').html(resposta);/*buscando formulário*/
		});
		$('.dialog').fadeIn("fast", function(){    /*abrir o fundo da box modal 'escurão'*/
			$('.newcat').fadeIn("fast");
		});  
		return false;
	});	
	
	/*cadastra categoria*/
	$('form[name="cadnewcat"]').submit(function(){
		var forma = $(this);
		var dados = $(this).serialize() + '&acao=categoria_cadastro'; /*faz o cadastro*/
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				forma.find('.load').fadeIn("fast");
			},
			success: function( datas ){							
				if(datas == 'errempty'){
					myDial('categoria_errempty','alert','Para cadastrar uma nova sessão ou categoria, preencha o campo com nome da mesma.</p><p><strong>Obrigado!</strong>');
				}else if(datas == 'errisset'){
					myDial('categoria_errisset','error','O nome que você esta tentando cadastrar para essa sessão ou categoria já esta em uso.<p></p><strong>Utilize outro nome de categoria!</strong>');
				}else{
					$('.ajaxmsg').addClass('categoria_accept accept').html(
						'<strong class="tt">Sucesso</strong>' +
						'<p>Categoria cadastrada com sucesso. Aguarde, estamos redirecionando vocÇe para realizar a gestão da mesma!</p><p><strong>Aguarde. Obrigado!</strong>'
					).fadeIn("slow");					
					window.setTimeout( function(){
						forma.find('input[name="categoria"]').val('');
						$(location)	.attr('href','dashboard.php?exe=categorias/edit&catid=' + datas);
					} , 2000);
				}			
			},
			complete: function(){
				forma.find('.load').fadeOut("fast");
			}
		});
		return false;
	});	
		
	
		/*categoria update*/
		$('form[name="editcat"]').submit(function(){
			var form = $(this);
			var bar = form.find('.progress');
			var per = form.find('.bar');
			$(this).ajaxSubmit({   /*ja vai comunicar o php com todos os dados serilizados*/
				url: 				url,
				data: 				{acao: 'categoria_update'},
				beforeSubmit:		function(){
					form.find('.load').fadeIn("fast");
				},
				uploadProgress:		function(evento, posicao, total, completo){
					var porcento = completo + '%';
					bar.fadeIn("fast", function(){
						per.width(porcento).text(porcento);
					});
				},
				success:			function(resposta){
					if(resposta == 'errempty'){
						myModal('catupdate_errempty','alert','Para atualizar a categoria precisamos que você preencha todos os campos.</p><p><strong>Obrigado</strong>');/*Campos em branco*/
					}else if(resposta == 'errisset'){
						myModal('catupdate_errisset','error','Desculpa mas não é possivel cadastrar duas categorias com o mesmo nome, altere o nome, altere o nome da categoria!</p><p><strong>Obrigado</strong>');/*já existe*/
					}else if(resposta == 'errext'){
						myModal('catupdate_errext','alert','A capa que você está tentando enviar não têm uma extensão válida. Envie uma imagem JPG, PNG ou GIF.</p><p><strong>Obrigado</strong>');/*Extensão do arquivo*/									
					}else if(resposta == 'Error'){
						myModal('catupdate_other','error','Desculpe, não foi possível atualizar, talvez você tenha enviado um arquivo muito grande!</p><p><strong>Tente uma imagem menor!</strong>');/*Tamanho do arquivo*/
					}else{						
						$('.viewcapa').fadeOut('slow', function(){
							$(this).find('img').attr('src','tim.php?src=../uploads/' + resposta + '&h=42');
							$(this).find('a').attr('href','../uploads/' + resposta);
							$('.viewcapa').fadeIn("slow");
						});
					}								
				},
				complete:			function(){
					bar.fadeOut("slow", function(){
						$('.viewcapa').val('');
						form.find('.load').fadeOut("fast");
						per.width('0%').text('0%');
					});
				}				
			});		
			return false;	
		});
		
		$('.viewcapa').on('click','a', function(){
			Shadowbox.open(this);			
			return false;
		});
	
	
	
	
	/*deleta categoria*/
	$('.j_catdelete').click(function(){
		var catid = $(this).attr('id');
		var dados = 'acao=categoria_deleta&catid=' + catid;
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				$('.catli li[id="'+ catid +'"]').css("background",'red');
			},
			success: function( datas ){						
				if(datas == 'errisset'){
					myModal('categoria_delete_errisset','alert','Você está tentando deletar uma sessão ou categoria que contêm conteúdo. O sistema não permite esta ação.</p><p><strong>Limpe a sessão ou categoria antes!</strong>');
					window.setTimeout( function(){
						$('.catli li[id="'+ catid +'"]').css("background",'#fff');					
					}, 3000 );
				}else{
					window.setTimeout( function(){
						$('.catli li[id="'+ catid +'"]').fadeOut("slow");						
					}, 1000 );
				}
			}
		});		
		return false;
	});
	
	
	
	
	
	
	
	
	/*USUARIOS*/
	/*abre modal*/
	$('.j_adduser').click(function(){
		$('.dialog').fadeIn("fast",function(){
			$('.newuser').fadeIn("slow");			
		});
		return false;
	});
	
	
	
	/*cadastra usuários*/
	$('form[name="cadnewuser"]').submit(function(){
		var forma = $(this);
		var dados = $(this).serialize() + '&acao=usuarios_manage&exe=cadastro'; /*faz o cadastro e a edição*/
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				forma.find('.load').fadeIn("fast");
			},
			success: function( datas ){				
				if(datas == 'errempty'){
					myDial('usuarios_errempty','alert','Para cadastrar um novo usuário, preencha todos os campos solicitados.</p><p><strong>Obrigado!</strong>');
				}else if(datas == 'errmail'){
					myDial('usuarios_errmail','error','O <strong>e-mail</strong> informado não tem um formato válido, favor informe um e-mail do novo usuário!<strong>Obrigado!</strong>');
				}else if(datas == 'errisset'){
					myDial('usuarios_errisset','error','O e-mail ou login que você tentou cadastrar, já está sendo utilizado por outro usuário<p></p><strong>Utilize outro e-mail ou login!</strong>');
				}else{
					myModal('usuarios_accept','accept','O novo usuário <strong>'+ datas +'</strong> criado com sucesso em seu painel admin!</p><p><strong>Parabéns!</strong>');
					/*fecha modal e atualiza */
					$('#usuarios_accept').click(function(){
						window.location.reload(); /*ele vai atualizar assim que fechar o modal*/
						return false;
					});
				}			
			},
			complete: function(){
				forma.find('.load').fadeOut("fast");
			}
		});
		return false;
	});	
	
	/*abre edição de usuário*/
	$('.j_useredit').click(function(){
		var userid = $(this).attr('id'); /*pegar id do usuário*/
		var dados = 'acao=usuarios_consulta&userid='+ userid;	
		$('.load').fadeOut("fast");
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',			
			success: function( datas ){						
				$('.dialog').fadeIn("fast",function(){
					$('.edituser').html(datas).fadeIn("slow");
				});
			}		
		});
		return false;
	});
	
	/*fechar edição de usuário*/
	$('.edituser').on('click','.j_formclose', function(){ /*manipular o DOM*/
		$('.edituser').fadeOut("slow",function(){
			$('.dialog').fadeOut("fast");
			$(this).html('');
		});	
		return false;
	});
	
	/*Edita o usuário*/
	$('.edituser').on('submit', 'form[name="edituser"]',function(){
		var forma = $(this);
		var dados = $(this).serialize() + '&acao=usuarios_manage&exe=atualiza'; 
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				forma.find('.load').fadeIn("fast");
			},
			success: function( datas ){				
				if(datas == 'errempty'){
					myDial('usuarios_errempty','alert','Para atualizar o novo usuário, preencha todos os campos solicitados.</p><p><strong>Obrigado!</strong>');
				}else if(datas == 'errmail'){
					myDial('usuarios_errmail','error','O <strong>e-mail</strong> informado não tem um formato válido, favor informe um e-mail do para este usuário!<strong>Obrigado!</strong>');
				}else if(datas == 'errisset'){
					myDial('usuarios_errisset','error','O e-mail ou login que você tentou cadastrar, já está sendo utilizado por outro usuário<p></p><strong>Utilize outro e-mail ou login!</strong>');
				}else{
					myModal('usuarios_accept','accept','O usuário <strong>'+ datas +'</strong> foi atualizado com sucesso em seu painel admin!</p><p><strong>Parabéns!</strong>');
					/*fecha modal e atualiza */
					$('#usuarios_accept').click(function(){
						window.location.reload(); /*ele vai atualizar assim que fechar o modal*/
						return false;
					});
				}			
			},
			complete: function(){
				forma.find('.load').fadeOut("fast");
			}
		});
		return false;
	});
	
	/*deleta usuário*/	
	$('.j_userdelete').click(function(){
		var userid = $(this).attr('id');
		var dados = 'acao=usuarios_deleta&userid='+ userid;		
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				$('.usuarios .users li[id="'+ userid +'"]').css("background","red");
			},
			success: function( datas ){						
				if(datas == 'errsuper'){
					myModal('usuarios_errsuper','alert','Você esta tentando deletar o unico <strong>SUPER ADMIN</strong> cadastrado. Isso não é permitido pelo sistema!</p><p><strong>Desculpe!</strong>');
					window.setTimeout( function(){
						$('.usuarios .users li[id="'+ userid +'"]').css("background","#fff");
					}, 3000 );
				}else{
					$('.usuarios .users li[id="'+ userid +'"]').fadeOut("slow");
				}			
			}			
		});		
		return false;
	});
	
	
	
	
	/*CONFIGURAÇÕES*/
	/*navegação e abas*/
	$('.configs .abas_config li a').click(function(){
		$('.configs .abas_config li a').removeClass('active');/*remove a classe active de todos*/
		$(this).addClass('active'); /*adicionar active na atual selecionada*/
		
		var formgo = $(this).attr('href');	/*pegando o name do form e armazena em formgo*/			
		$('.configs form[name != " '+ formgo +' "]').fadeOut("fast",function(){
			$('form[name="'+ formgo +'"]').delay("fast").fadeIn("fast");
		});		
		
		return false;
	});
	
	/*manutencao - evita envio*/
	$('form[name="config_manutencao"]').submit(function(){
		
		return false;
	});
	
	/*desativa manutenção*/
	$('.j_config_manutencao_no').click(function(){
		$.ajax({
			url: url,			
			data: 'acao=manutencao_desativa',
			type: 'POST',
			beforeSend: function(){
				$('.configs form .main .load').fadeIn("fast");	
			},			
			complete: function(){
				$('.configs form .main .load').fadeOut("fast",function(){
					$('.j_manutencao_desativa').fadeOut("slow", function(){
						$('.j_manutencao_ativa').fadeIn("slow");
					});	
				});
			},
			error: function(){
				alert('Erro: Manutenção AJAX');
			}
		});					
	});
	
	/*ativa manutenção*/
	$('.j_config_manutencao_yes').click(function(){
		$.ajax({
			url: url,			
			data: 'acao=manutencao_ativa',
			type: 'POST',
			beforeSend: function(){
				$('.configs form .main .load').fadeIn("fast");	
			},			
			complete: function(){
				$('.configs form .main .load').fadeOut("fast",function(){
					$('.j_manutencao_ativa').fadeOut("slow", function(){
						$('.j_manutencao_desativa').fadeIn("slow");
					});	
				});
			},
			error: function(){
				alert('Erro: Manutenção AJAX');
			}
		});					
	});
	
	/*configura servidor de e-mail*/
	/*email atualiza dados*/
	$('form[name="config_email"]').submit(function(){
		var forma = $(this);
		var dados = $(this).serialize() + '&acao=mailserver_atualiza';
		$.ajax({
			url: url,
			data: dados,
			type: 'POST',
			beforeSend: function(){
				forma.find('.load').fadeIn("fast");
			},
			success: function( datas ){
				if(datas == 'errempty'){
					myModal('config_mailserver_errempty','alert','Para atualizar o servidor de e-mail, preencha todos os campos solicitados.</p><p><strong>Obrigado!</strong>');
				}else if(datas == 'errmail'){
					myModal('config_mailserver_errmail','error','O <strong>e-mail</strong> informado não tem um formato válido, favor informe um e-mail para envio!<strong>Obrigado!</strong>');
				}else{
					myModal('config_mailserver_accept','accept','Dados atualizados, é muito importante que estas configurações estejam corretas. Então: </p><p><strong>Teste o envio!</strong>');
				}				
			},
			complete: function(){
				forma.find('.load').fadeOut("fast");
			}
		});
		return false;
	});
	
		/*email testa dados*/
		$('.j_config_email_teste').click(function(){
			var forma = $('form[name="config_email"]');			
			$.ajax({
				url: url,
				data: 'acao=mailserver_teste',
				type: 'POST',
				beforeSend: function(){
					forma.find('.load').fadeIn("fast");
				},
				success: function( datas ){					
					if(datas.indexOf('error') <= 0){ /*se não encontrar a palavras error em datas*/
						myModal('teste_mailserver_accept','accept','E-mail enviado com sucesso. Favor verifique o recebimento da mensagem em sua caixa de entrada:</p><p><strong>' + datas + '</strong>');
					}else{
						myModal('teste_mailserver_error','error','Falha ao enviar o e-mail. Favor confira os dados configurados no formulário do servidor.');
					}				
				},
				complete: function(){
					forma.find('.load').fadeOut("fast");
				}
			});
		});
		
		/*configura seo/social*/
		$('form[name="config_seo"]').submit(function(){
			var forma = $(this);
			var dados = $(this).serialize() + '&acao=seosocial_atualiza';
			$.ajax({
				url: url,
				data: dados,
				type: 'POST',
				beforeSend: function(){
					forma.find('.load').fadeIn("fast");
				},
				success: function( datas ){
					if(datas == 'errempty'){
						myModal('config_seosocial_errempty','alert','Para atualizar os dados de SEO e social, preencha todos os campos solicitados.</p><p><strong>Obrigado!</strong>');
					}else{
						myModal('config_seosocial_accept','accept','Dados atualizados. <strong>Você otimizou o site com sucesso</strong>. Suas novas configurações já estão ativas!');
					}				
				},
				complete: function(){
					forma.find('.load').fadeOut("fast");
				}
			});
			return false;			
		});
		
		
		/*configura endereço e telefone*/
		$('form[name="config_dados"]').submit(function(){
			var forma = $(this);
			var dados = $(this).serialize() + '&acao=endtel_atualiza';
			$.ajax({
				url: url,
				data: dados,
				type: 'POST',
				beforeSend: function(){
					forma.find('.load').fadeIn("fast");
				},
				success: function( datas ){
					if(datas == 'errempty'){
						myModal('config_configdados_errempty','alert','Para atualizar os dados de Endereço e telefone, preencha todos os campos solicitados.</p><p><strong>Obrigado!</strong>');
					}else{
						myModal('config_configdados_accept','accept','Dados atualizados. Seu endereço e telefone estão corretamente atualizados');
					}				
				},
				complete: function(){
					forma.find('.load').fadeOut("fast");
				}
			});
			return false;			
		});
		
		
		/*configura tema*/
		/*ler tema*/
		function lerTemas(){
			$.post(url, {acao: 'theme_read'}, function(retorno){
				$('.themes').fadeTo(500,'0.2',function(){
					$(this).html(retorno);
					$(this).queue(function(){
						$(this).fadeTo(500,'1')	;
					});
					$(this).dequeue();
				});
			});
		}		
	

		/*cadastrar temas*/
		$('form[name="config_theme"]').submit(function(){
			var forma = $(this);
			var dados = $(this).serialize() + '&acao=theme_cadastra';
			$.ajax({
				url: url,
				data: dados,
				type: 'POST',
				beforeSend: function(){
					forma.find('.load').fadeIn("fast");
				},
				success: function( datas ){					
					if(datas == 'errempty'){
						myModal('config_theme_errempty','alert','Para cadastrar um novo tema, informe o nome do mesmo e a pasta que ele se encontra dentro da pasta themes!</p><p><strong>Obrigado!</strong>');
					}else{																	
						myModal('config_theme_accept','accept','Seu novo tema ' + datas + ' foi criado com sucesso. Você já pode utiliza-lo!</p><p><strong>Parabéns!</strong>');
						lerTemas();
					} 				
				},
				complete: function(){
					forma.find('.load').fadeOut("fast");
				}
			});
			return false;			
		});
		
		/*ativa temas*/
		$('.themes').on('click', '.j_themeactive',function(){
			var themeid = $(this).attr('id');
			$.post(url, {acao:'theme_ativa',id:themeid},function(dados){				
				lerTemas();
			});
			return false;
		});
		
		/*deleta temas*/
		$('.themes').on('click', '.j_themedelete',function(){
			var themeid = $(this).attr('id');
			$.post(url, {acao:'theme_deleta',id:themeid},function(dados){	
				if(dados == 'erractive'){
					myModal('deleta_theme_error','error','O tema que você esta tentando deletar está em uso. Para remover este tema,antes ative outro!</p><p><strong>Ative outro tema para deletar este!</strong>');
				}else{
					$('.themes li[id="'+ themeid +'"]').css('background','red');
					window.setTimeout(function(){
						$('.themes li[id="'+ themeid +'"]').fadeOut("slow");
					},1000);
				}				
			
			});
			return false;
		});			
});





















