<?php
ob_start(); session_start();
require('dts/configs.php');

viewManager('600');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<link rel="icon" type="image/png" href="<?php setHome();?>/tpl/images/favicon.png"/> <!-- gera a imagem da guia navegador -->
<link rel="stylesheet" type="text/css" href="<?php setHome();?>/jsc/shadowbox/shadowbox.css" />

<script type="text/javascript" src="<?php setHome();?>/jsc/jquery.js"></script>
<script type="text/javascript" src="<?php setHome();?>/jsc/jcycle.js"></script>
<script type="text/javascript" src="<?php setHome();?>/jsc/shadowbox/shadowbox.js"></script>

<?php require('themes/'.THEME.'/css/'.THEME.'.css.php'); ?>
<?php require('themes/'.THEME.'/js/'.THEME.'.js.php'); ?>

<?php getHome();?>
</body>
</html>