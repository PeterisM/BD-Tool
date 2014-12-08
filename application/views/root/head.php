<!DOCTYPE HTML>

<html lang="<?php echo $lang; ?>" xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" >
	<head>
		<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
		<?php echo meta('keywords', hsc($key_words)); ?>
		<?php echo meta('description', hsc($description)); ?>
        <meta name="title" content="Datu vizuāla attēlošana tīmekļa vietnēs" />
        <link rel="image_src" href="http://infosthetics.com/archives/what_is_visualization.jpg" />
		<meta name="author" content="Pēteris Maltenieks" />
		<meta name="robots" content="<?php echo hsc($follow) ?>">
		<title><?php echo hsc($page_title); if(!$homepage): echo ' - Datu vizuāla attēlošana tīmekļa vietnēs'; endif; ?></title>
        <!--[if IE 7]>
            <?php echo link_tag('public/css/styles-ie.css', 'stylesheet', 'text/css'); ?>
        <![endif]-->
		<?php echo link_tag('public/css/styles.css', 'stylesheet', 'text/css'); ?>
		<?php echo link_tag('public/css/print.css', 'stylesheet', 'text/css', '', 'print'); ?>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery.js" ></script>      		
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery_noconflict.js" ></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/ready.js" ></script>
	</head>