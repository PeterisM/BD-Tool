<!DOCTYPE HTML>
<html lang="lv" xmlns="http://www.w3.org/1999/xhtml" xml:lang="lv" >
	<head>
		<?php echo meta('Content-type', 'text/html; charset=utf-8', 'equiv'); ?>
		<meta name="author" content="<?php echo $chart->user_name; ?>" />
		<meta name="robots" content="<?php echo hsc($follow) ?>">
		<title><?php echo hsc($page_title) . ' - Datu vizuāla attēlošana tīmekļa vietnēs';?></title>
        <!--[if IE 7]>
            <?php echo link_tag('public/css/styles-ie.css', 'stylesheet', 'text/css'); ?>
        <![endif]-->
		<?php echo link_tag('public/css/charts.css', 'stylesheet', 'text/css'); ?>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery.js" ></script>      
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/jquery_noconflict.js" ></script>
		<script type="text/javascript">
			
		</script>
		<script type="text/javascript" src="<?php echo base_url(); ?>public/js/tool.js" ></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
			window.chart = JSON.parse(<?php echo $chart->config; ?>);
			window.chart.width = '<?php echo $chart->width; ?>';
			window.chart.height = '<?php echo $chart->height; ?>';
			
			// Izveido serijas ========
			window.chart.series_titles = {};
			var c_series = {};
			<?php
				for($i = 1; $i < count($chart_header); $i++)
				{
					echo 'c_series[' . ($i-1) . '] = \'' . $chart_header[$i] . '\';';
				}
			?>		
			$j.extend(true, window.chart.series_titles, c_series);
			window.chart.series_count = $j.map(window.chart.series_titles, function(n, i) { return i; }).length;
			//==============
			
			<?php if($chart_data && $chart->status == 1): ?>
				google.load("visualization", "1.1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {
					var data = google.visualization.arrayToDataTable([
						<?php echo $chart_data;?>
					]);


					make_opt();

					<?php 
						switch ($chart->type) {
							case 'line':
								echo 'google.chart = new google.visualization.LineChart(document.getElementById(\'chart_div\'));';
								break;
							case 'bar':
								echo 'google.chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div\'));';
								break;
							case 'area':
								echo 'google.chart = new google.visualization.AreaChart(document.getElementById(\'chart_div\'));';
								break;
						}
					?>
					google.chart.draw(data, window.charts_opt);
				  };
			<?php endif; ?>	
		</script>
	</head>
	<body>
		<?php if($chart->status == 1): ?>
		<div id="chart_div" style="width: <?php echo $chart->width; ?>px; height: <?php echo $chart->height; ?>px; margin: 0 auto;"></div>
		<b>Izveidots ar: </b><a id="back-link" href="<?php echo base_url(); ?>"><?php echo base_url(); ?></a>
		<?php endif; ?>
	</body>
</html>