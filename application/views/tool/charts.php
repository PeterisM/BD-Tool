<?php
	$this->load->view("tool/header");
?>
<div class="content">
	<p id="message" <?php if ($message): echo 'class="message"'; endif; ?>>
        <?php if ($message): echo $message; endif; ?>
    </p>
		<h3 class="title">Manas diagrammas</h3>
	<?php if (sizeof($charts) == 0): ?>
        <p class="info">Jūs uz doto brīdi neesiet izveidojis nevienu diagrammu!</p>
	<?php else: ?>
		<p id="total" class="info" style="display: none;">Jūs uz doto brīdi neesiet izveidojis nevienu diagrammu!</p>
		<ul class="charts">
		<?php foreach ($charts as $chart): ?>
			<?php 
				$splited = explode(" ", $chart->date_modified);
				$org_date = $splited[0];
				$time = $splited[1];
				$dates = explode("-", $org_date);
				$chart->date_modified = $dates[2] . "." . $dates[1] . "." . $dates[0] . ". " . $time;
				
				$splited = explode(" ", $chart->date_created);
				$org_date = $splited[0];
				$time = $splited[1];
				$dates = explode("-", $org_date);
				$chart->date_created = $dates[2] . "." . $dates[1] . "." . $dates[0] . ". " . $time;
				
				$chart->status == 1 ? $status = 'Publiska' : $status = 'Privāta';
			?>      
			<li id="chart_<?php echo ($chart->id); ?>" class="chart">
				<a href="<?php echo base_url() . 'tool/edit/' . $chart->id; ?>">
				<?php if($chart->img != ''):?>
				<img src="<?php echo base_url(); ?>/data/<?php echo $user . '/' . $chart->img; ?>" width="220" height="220" alt="<?php echo $chart->name; ?>" title="<?php echo $chart->name; ?>">
				<?php else: ?>
				<img src="<?php echo base_url(); ?>/public/image/chart.png" width="220" height="220" alt="<?php echo $chart->name; ?>" title="<?php echo $chart->name; ?>">
				<?php endif; ?>
				</a>
				<h3><a class="chart_name" href="<?php echo base_url() . 'tool/edit/' . $chart->id; ?>"><?php echo $chart->name; ?></a></h3>
				<p><b>Izveidota:</b> <span class="imp"><?php echo $chart->date_created; ?></span></p>
				<p><b>Labota:</b> <span class="imp"><?php echo $chart->date_modified; ?></span></p>
				<p><b>Apskatīta:</b> <span class="imp"><?php echo $chart->visited; ?></span></p>
				<p><b>Status:</b> <span class="imp"><?php echo $status; ?></span></p>
				<?php if($chart->id > 10000002):?>
				<span id="<?php echo ($chart->id); ?>" class="admin-delete" title="Dzēst diagrammu">&nbsp;</span>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
		<li class="clear">
		</ul>
		<div class="clear"></div>
    <?php endif; ?>
</div>
<script type="text/javascript">
	$j(document).ready(function() {
		var fade_in = 1200;
		function del_chart()
		{
			var chart_id = $j(this).attr('id');
			var chart_name = $j(this).siblings('h3').eq(0).text();
			jConfirm('Vai tiešām vēlieties dzēst diagrammu "' + chart_name + '"?', '', function(r) {
				if (r)
				{
					var form_data = {ajax : '1'};
					$j.ajax({
						dataType: "json",
						url: '<?php echo base_url(); ?>tool/delete/' + chart_id,
						type: 'POST',
						async : false,
						cache: false,
						data: form_data,
						success: function(jdata) {
							if(jdata.redirect) window.location = '<?php echo base_url(); ?>';
							else
							{
								$j("#message").css('display', 'none');
								$j("#message").html(jdata.message).addClass('message').fadeIn(fade_in);
								if(jdata.success)
								{
									$j('#chart_' + jdata.id).fadeOut(fade_in);
									setTimeout(function() {
										$j('#chart_' + jdata.id).remove();
										if($j('.chart').length == 0) $j('#total').fadeIn(fade_in);
									}, fade_in);
								}
							}
						}
					});
				};
			});
		};
		$j('body').on("click", '.admin-delete', del_chart);
	});
</script>
	
<?php
    $this->load->view("tool/footer");
?>