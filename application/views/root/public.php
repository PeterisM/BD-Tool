<?php
	$this->load->view("root/header");
?>
<div class="content">
	<?php if (sizeof($charts) == 0): ?>
            <p class="info">Uz doto brīdi nav neviena diagramma!</p>
	<?php else: ?>
        <h3 class="title">Publiskās diagrammas</h3>
		<ul class="charts">
		<?php foreach ($charts as $chart): ?>
			<?php 
				$splited = explode(" ", $chart->date_modified);
				$org_date = $splited[0];
				$time = $splited[1];
				$dates = explode("-", $org_date);
				$chart->date_modified = $dates[2] . "." . $dates[1] . "." . $dates[0] . ".";
			?>      
			<li class="chart">
				<a href="<?php echo base_url() . 'chart/' . $chart->id; ?>">
				<?php if($chart->img != ''):?>
				<img src="<?php echo base_url(); ?>/data/<?php echo $chart->user_name . '/' . $chart->img; ?>" width="220" height="220" alt="<?php echo $chart->name; ?>" title="<?php echo $chart->name; ?>">
				<?php else: ?>
				<img src="<?php echo base_url(); ?>/public/image/chart.png" width="220" height="220" alt="<?php echo $chart->name; ?>" title="<?php echo $chart->name; ?>">
				<?php endif; ?>
				</a>
				<h3><a href="<?php echo base_url() . 'chart/' . $chart->id; ?>"><?php echo $chart->name; ?></a></h3>
				<p><b>Autors:</b> <span class="imp"><?php echo $chart->user_name; ?></span></p>
			</li>
		<?php endforeach; ?>
		</ul>
		<div class="clear"></div>
    <?php endif; ?>
</div>
<?php
    $this->load->view("root/footer");
?>