<!DOCTYPE HTML>
<html lang="<?php echo $lang; ?>" xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" >
	<head>
		<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
		<meta name="author" content="Pēteris Maltenieks" />
		<title><?php echo hsc($page_title); if(!$homepage): echo ' - Datu vizuāla attēlošana tīmekļa vietnēs'; endif; ?></title>
        <!--[if IE 7]>
            <?php echo link_tag('public/css/styles-ie.css', 'stylesheet', 'text/css'); ?>
        <![endif]-->
		<?php echo link_tag('public/css/styles.css', 'stylesheet', 'text/css'); ?>
		<?php echo link_tag('public/css/alerts/jquery.alerts.css', 'stylesheet', 'text/css'); ?>
		<?php echo link_tag('public/css/jquery-ui.css', 'stylesheet', 'text/css'); ?>
		<?php echo link_tag('public/css/colpick.css', 'stylesheet', 'text/css'); ?>
		<?php echo link_tag('public/css/jquery.handsontable.full.css', 'stylesheet', 'text/css'); ?>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery.js" ></script>      
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery-migrate.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery.alerts.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery-ui.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/colpick.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery.handsontable.full.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/canvg/rgbcolor.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/canvg/StackBlur.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/canvg/canvg.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery_noconflict.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/tool.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/logged.js" ></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	</head>