<script type="text/javascript" src="<?php setHome();?>/themes/<?php echo THEME;?>/js/jshome.js"></script>

<?php echo getSeo(SITENAME.' | Home',SITEDESC); ?>
</head>

<!--body -->
<body>

<?php 
	setArq('tpl/sidebars/modais');
	setArq('tpl/sidebars/slider');
	setArq('tpl/sidebars/header');	
?> 
  
<!-- BLOCO SITE GERAL HOME -->
<div id="site">
<div class="home">



<!-- BLOCO UM - h1. h2. Img Topo -->
<div class="bloco_um">
    <h1>Home</h1>
    <h2>Confira conteúdo de primeira aqui no MyContent da Up. Aqui você confere os melhores vídeos e artigos da internet!</h2>

    <div class="capa">
        <img src="<?php setHome();?>/tpl/_tmp/02.png" />
        <a href="<?php setHome();?>/ver/shael-sonnen-x-spidder-quem-leva">
            SHAEL SONNEN X SPIDDER, QUEM LEVA?
            <p>Confira conteúdo de primeira aqui no MyContent da Up.</p>
        </a>
    </div><!-- /capa -->
</div><!-- /BLOCO UM -->


<!-- BLOCO DOIS - Destaques, de olho, feed x 4 -->
<div class="bloco_dois">
    
    <ul class="navbldois">
        <li class="destaq">DESTAQUES!</li>
        <li class="deolho">DE OLHO!</li>
    </ul>       

    <div class="content"> 
                    
        <ul class="arts">
        <?php for($i=1;$i<=4;$i++):?>
            <li <?php if($i%2==0) echo 'style="float:right;"';?>>
                <img src="<?php setHome();?>/tpl/_tmp/03.png" />
                <a href="<?php setHome();?>/ver">SHAEL SONNEN X SPIDDER, QUEM LEVA?</a>
                <p>Confira conteúdo de primeira aqui no MyContent da Up.</p>
            </li>
        <?php endfor;?>
        </ul><!-- /arts -->  
                         
    </div><!-- /content -->                    
</div><!-- /BLOCO DOIS -->


<!-- BLOCO TRES - vídeos -->
<div id="videos" class="bloco_tres">
    <div class="content">
        <h2>Vídeos</h2>
        
        <ul class="videos">
        <?php for($i=1;$i<=3;$i++):?>
            <li<?php if($i%3==0) echo ' style="float:right; margin-right:0"';?>>
                <img src="<?php setHome();?>/tpl/_tmp/04.png" />
                <div class="licontent">
                    <a href="<?php setHome();?>/ver">SHAEL SONNEN X SPIDDER, QUEM LEVA?</a>
                    <p>Confira conteúdo de primeira aqui no MyContent da Up. Aqui você confere os melhores vídeos e artigos da internet!</p>
                </div><!-- /content -->
            </li>
        <?php endfor;?>
        <li class="readmore"><a href="<?php setHome();?>/categoria" title="Ver vídeos na MyContent">VEJA +</a></li>
        </ul>
        
    </div><!-- /content -->
</div><!-- /BLOCO TRESs -->


<!-- BLOCO QUATRO - artigos -->
<div id="artigos" class="bloco_quatro">
    <div class="content">
        <h2>Artigos</h2>
        
        <div class="destaq">
            <img src="<?php setHome();?>/tpl/_tmp/05.png" />
            <a href="<?php setHome();?>/ver">SHAEL SONNEN X SPIDDER, QUEM LEVA?</a>
            <p>Confira conteúdo de primeira aqui no MyContent da Up. Aqui você confere os melhores vídeos e artigos da internet!</p>
        </div>
        
        <ul class="artigos">
        <?php for($i=1;$i<=3;$i++):?>
            <li>
                <img src="<?php setHome();?>/tpl/_tmp/06.png" />
                <a href="<?php setHome();?>/ver">SHAEL SONNEN X SPIDDER, QUEM LEVA?</a>
                <p>Confira conteúdo de primeira aqui no MyContent da Up.</p>
            </li>
        <?php endfor;?>
        	<li class="readmore"><a href="<?php setHome();?>/categoria" title="Ler artigos na MyContent">LEIA +</a></li>
        </ul>
        
    </div><!-- /content -->
</div><!-- /BLOCO QUATRO -->



<div class="clear"></div><!--/clear-->
</div><!-- /HOME GERAL -->  
</div><!-- #SITE -->
    
  
<!-- FOOTER -->    
<div id="footer" class="footer">
    <div class="content">
        <?php setArq('tpl/sidebars/menu');?>                       
    </div><!-- /content -->
</div><!-- /#FOOTER -->