$(function(){
	//CONTROLA O LOGIN
	$('form').submit(function() {
		var login = $(this).serialize() + '&acao=login';
		$.ajax({
			url:		'swith/login.php',
			data: 		login,
			type:		'POST',
			success:	function(resposta){
				/*recebe validação do php*/
				if(resposta == 'erroempty'){
					$('.msg').empty().html('<p class="aviso">Informe seu usuário e senha!</p>').fadeIn('slow');
				}else if(resposta == 'errosenha'){
					$('.msg').empty().html('<p class="erro">Erro ao logar! Dados não conferem!</p>').fadeIn('slow');
				}else if(resposta == 'sucess'){
					$('.msg').empty().html('<p class="sucesso">Login efetuado, aguarde...</p>').fadeIn('slow');
					window.setTimeout( function(){
						$(location).attr('href','dashboard.php');	
					}, 1000);
				}else{
					alert('Erro no sistema!');
				}
			},
			beforeSend: function(){
				$('.loginbox h1 img').fadeIn('fast');
			},
			complete:	function(){
				$('.loginbox h1 img').fadeOut('slow');
			},
			error:		function(){
				alert('Erro no sistema!');
			}	
		}); 
		
		return false;
	});	
});