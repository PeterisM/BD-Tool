<?php
	$this->load->view("tool/header");
?>
<div class="content chart tool">
	<?php 
		$class_all = '';
		$active = '';
		$class1 = 'class="show active"';
		$style1 = 'style="display: block;"';
		$style2 = $style3 = $style4 = 'style="display: none;"';
		$href1 = '#upload';
		$href2 = '#check';
		$href3 = '#config';
		$href4 = '#share';
		$form1_url = base_url() . 'tool/create';
		if($chart->id != 0) //edit
		{
			$class1 = 'class="show"';
			$class_all = 'class="show"';
			$active = 'class="show active"';
			$href1 = base_url() . 'tool/update/' . $chart->id;
			$style2 = 'style="display: block;"';
			$style1 = 'style="display: none;"';
			
		}
		if($up_data)
		{
			$form1_url = base_url() . 'tool/update/' . $chart->id;
			$style1 = 'style="display: block;"';
			$style2 = 'style="display: none;"';
			$style3 = 'style="display: none;"';
			$class_all = '';
			$active = 'class="show"';
			$class1 = 'class="show active"';
			$href2 = base_url() . 'tool/edit/' . $chart->id;
			$href3 = base_url() . 'tool/edit/' . $chart->id;
			$href4 = base_url() . 'tool/edit/' . $chart->id;
		}
	?>
	<script type="text/javascript">
		window.base_url = '<?php echo base_url(); ?>';
	<?php if($chart_data): ?>	
		window.chart = JSON.parse(<?php echo $chart->config; ?>);
		window.chart.type = '<?php echo $chart->type; ?>';
		window.chart.width = '<?php echo $chart->width; ?>';
		window.chart.height = '<?php echo $chart->height; ?>';
		
		// Izveido serijas ========
		window.chart.series_titles = {};
		var c_series = {};
		<?php for($i = 1; $i < count($chart_header); $i++){ echo 'c_series[' . ($i-1) . '] = \'' . $chart_header[$i] . '\';'; }	?>		
		$j.extend(true, window.chart.series_titles, c_series);
		window.chart.series_count = $j.map(window.chart.series_titles, function(n, i) { return i; }).length;
		//==============
		
		google.load("visualization", "1.1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
			window.charts_data = google.visualization.arrayToDataTable([
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
			google.chart.draw(window.charts_data, window.charts_opt);
			make_img();
		};
		$j(document).ready(function() {
			set_config();
			<?php 
			switch ($chart->type) {
				case 'line': echo '$j(\'.line_bar\').show();' . '$j(\'.bar\').hide();'; break;
				case 'bar': echo '$j(\'.line_bar\').show();' . '$j(\'.bar\').show();'; break;
				case 'area': echo '$j(\'.line_bar\').show();' . '$j(\'.bar\').hide();'; break;}
			?>
			$j('#backgroundColor').css('border-color', '#' + window.chart.backgroundColor);
			
			//data table ==============================
			var chart_data = [
				<?php echo $chart_data;?>
			];

			$j("#chart_data").handsontable({
				data: chart_data,
				contextMenu: true,
				colHeaders: true
			});
			//=========================================
			
			//Sēriju konfigurēšana ====================
			var temp_color = 'ffffff';
			$j.each(window.chart.series_titles, function( index, value ) {
				$j('#s_col').append( '<label for="' + index + '_series">Sērijas "' + value + '" krāsa:</label>' );
				$j('#s_col').append( '<span class="c_wrapper"><input class="form_input color_picker" type="text" name="' + index + '_series" value="' + window.chart.series[index] + '" id="' + index + '_series" maxlength="6"/></span>' );
				$j('#' + index + '_series').colpick({layout:'rgbhex',submit:0,color: window.chart.series[index],onChange:function(hsb,hex,rgb,el,bySetColor) {
					$j(el).css('border-color','#'+hex);	if(!bySetColor) $j(el).val(hex); update_config(); draw_lines(); make_img();
				}}).keyup(function(){$j(this).colpickSetColor(this.value);});
				$j('#' + index + '_series').css('border-color', '#' + window.chart.series[index]);
			});
			$j('#form2').append( '<input class="form_input hidden" type="text" name="series_count" value="' + window.chart.series_count + '" id="series_count" />' );
			//==========================================
			
		});
	<?php endif; ?>	
		
	</script>
	<p id="message" <?php if ($message): echo 'class="message"'; endif; ?>>
        <?php if ($message): echo $message; endif; ?>
    </p>
	<div id="tabs">
		<ul class="titles">
			<li id="step1" <?php echo $class1; ?>><a href="<?php echo $href1; ?>">Datu pievienošana</a></li>
			<li id="step2" <?php echo $active; ?>><a href="<?php echo $href2; ?>">Datu labošana</a></li>
			<li id="step3" <?php echo $class_all; ?>><a href="<?php echo $href3; ?>">Diagramma</a></li>
			<li id="step4" <?php echo $class_all; ?>><a href="<?php echo $href4; ?>">Publiskošana</a></li>
		</ul>
		<ul class="clear"></ul>
		<div id="upload" class="tab" <?php echo $style1; ?>>
			<div class="block">
				<div class="box-title"><h4>Diagrammas dati</h4></div>
				<?php echo form_open_multipart($form1_url, 'id="form1"'); ?>
					<label for="name">Diagrammas nosaukums:</label>
					<input type="text" name="name" maxlength="60" value="<?php if(!empty($chart->name)): echo $chart->name; else: echo set_value('name'); endif; ?>" id="name" />
					<?php if (form_error('name')): ?><div class="clear"></div><span class="error"><?php echo form_error('name'); ?></span><?php endif; ?>
					<!--<span class="error" id="name_error"></span>-->
					<label for="data">Datu datne:</label>
					<input type="file" name="data" id="data" />
					<?php if (isset($error)): ?><div class="clear"></div><span class="error"><?php echo $error; ?></span><?php elseif (form_error('data')): ?><div class="clear"></div><span class="error"><?php echo form_error('data'); ?></span><?php endif; ?>
					<!--<span class="error" id="data_error"></span>-->
					<div class="clear"></div>
					<?php if($up_data): ?>
					<button class="button" type="submit" value="Saglabāt" id="submit1"><span><span>Saglabāt</span></span></button>
					<?php else: ?>
					<button class="button" type="submit" value="Saglabāt" id="submit1"><span><span>Izveidot</span></span></button>
					<?php endif; ?>
					<div class="clear"></div>
				<?php echo form_close();?>
			</div>
			<div class="block">
				<div class="box-title"><h4>Informācija</h4></div>
				<p>Tiek atbalstīta datne ar .csv palašinājumu, kurā visu vērtību atdalītājsimbols ir komats (<b>,</b>).</p>
				</br>
				<p>Parauga datnes:</p>
				<ul style="list-style-type:disc; padding: 0 0 0 40px;">
					<li><a class="download" href="<?php echo base_url(); ?>public/data/tok_lon.csv">Tokyo & London monthly average temperature</a></li>
					<li><a class="download" href="<?php echo base_url(); ?>public/data/baltics_pop.csv">Baltics population by year</a></li>
				</ul>
			</div>
			<div class="clear"></div>
		</div>
		<div id="check" class="tab" <?php echo $style2; ?>>
			<div class="block">
				<div class="box-title"><h4>Pārbaudi "<?php echo $chart->name; ?>" diagrammas datus</h4></div>
				<button class="button" type="button" value="Saglabāt" id="submit2"><span><span>Saglabāt</span></span></button>
				<div class="clear"></div>
				<span style="float: right; color: red;">Diemžēl nav nodrošināta datu validācija!</span>
				<div class="clear"></div>
				<?php if($chart_data): ?>
				<a class="download" href="<?php echo base_url() . 'data/' . $chart->data; ?>">Lejupielādēt orģinālo datni</a>
				<?php endif; ?>
				<div id="chart_data" class="handsontable"></div>
			</div>
		</div> 
		<div id="config" class="tab" <?php echo $style3; ?>>
			<div class="box-title"><h3>Veido diagrammu "<?php echo $chart->name; ?>"</h3></div>
			<?php echo form_open('tool/save/' . $chart->id, 'id="form2"'); ?>
			<ul id="types">
				<li id="line">Līnijas</li>
				<li id="area">Laukumi</li>
				<li id="bar">Stabiņi</li>
			</ul>
			<h4 id="type_t">Diagrammas tips: </h4>
			<ul id="chart_nav">
				<li><span class="nav_t">Diagramma</span>
					<div class="nav_block">
						<div id="opt1" class="opt">
							<ul class="opt1_list">
								<li><a href="#vis">Vispārīgi</a></li>
								<li><a href="#ats">Atstarpes</a></li>
							</ul>
							<ul class="clear"></ul>
							<div id="vis" class="tab">
								<label for="title">Virsraksts:</label>
								<input class="form_input" type="text" name="title" value="" id="title" maxlength="100"/>
								<div class="radio-box">
									<label>Virsraksta pozīcija:</label>
									<input class="radio form_input" type="radio" name="titlePosition" id="t_poz_in" value="in">
									<label for="t_poz_in" class="radio_l">Iekšpusē</label>
									<input class="radio form_input" type="radio" name="titlePosition" id="t_poz_out" value="out">
									<label for="t_poz_out" class="radio_l">Ārpusē</label>
									<input class="radio form_input" type="radio" name="titlePosition" id="t_poz_none" value="none">
									<label for="t_poz_none" class="radio_l">Nerādīt</label>
									<div class="clear"></div>
								</div>
								<label>Fona krāsa:</label>
								<span class="c_wrapper"><input class="form_input color_picker" type="text" name="backgroundColor" value="" id="backgroundColor"/></span>
								<div class="slider-box">
									<label for="width">Platums:</label>
									<input class="form_input" type="number" name="width" value="" id="width" maxlength="4" min="200" max="3000"/>
									<div id="slider-width" class="slider"></div>
									<div class="clear"></div>
								</div>
								<div class="slider-box">
									<label for="height">Augstums:</label>
									<input class="form_input" type="number" name="height" value="" id="height" maxlength="4" min="200" max="2000"/>
									<div id="slider-height" class="slider"></div>
									<div class="clear"></div>
								</div>
								<div class="radio-box line_bar">
									<label>Datu nolasīšanai izmantot:</label>
									<input class="radio form_input" type="radio" name="focusTarget" id="datum" value="datum">
									<label for="datum" class="radio_l">Vienu datu sērīju</label>
									<input class="radio form_input" type="radio" name="focusTarget" id="category" value="category">
									<label for="category" class="radio_l">Visas datu sērijas</label>
									<div class="clear"></div>
								</div>
								<div class="radio-box line_bar">
									<label>Virziens:</label>
									<input class="radio form_input" type="radio" name="orientation" id="horizontal" value="horizontal">
									<label for="horizontal" class="radio_l">Horizontāli</label>
									<input class="radio form_input" type="radio" name="orientation" id="vertical" value="vertical">
									<label for="vertical" class="radio_l">Vertikāli</label>
									<div class="clear"></div>
								</div>
								<div class="slider-box bar">
									<label for="groupWidth">Atstarpe starp stabiņiem:</label>
									<input class="form_input" type="number" name="groupWidth" value="" id="groupWidth" maxlength="3" min="0" max="100"/><i>%</i>
									<div id="slider-groupWidth" class="slider"></div>
									<div class="clear"></div>
								</div>
								<div class="radio-box">
									<label>Sēriju nosaukumu pozīcija:</label>
									<input class="radio form_input" type="radio" name="legend_position" id="l_poz_top" value="top">
									<label for="l_poz_top" class="radio_l">Augšā</label>
									<input class="radio form_input" type="radio" name="legend_position" id="l_poz_bottom" value="bottom">
									<label for="l_poz_bottom" class="radio_l">Apakšā</label>
									<input class="radio form_input" type="radio" name="legend_position" id="l_poz_right" value="right">
									<label for="l_poz_right" class="radio_l">Pa labi</label>
									<div class="clear"></div>
									<input class="radio form_input" type="radio" name="legend_position" id="l_poz_in" value="in">
									<label for="l_poz_in" class="radio_l">Iekšpusē</label>
									<input class="radio form_input" type="radio" name="legend_position" id="l_poz_none" value="none">
									<label for="l_poz_none" class="radio_l">Nerādīt</label>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>
							<div id="ats" class="tab">
								<div class="slider-box">
									<label for="ca_left">Atkāpe no kreisās:</label>
									<input class="form_input" type="number" name="ca_left" value="" id="ca_left" maxlength="3" min="0" max="200"/>
									<div id="slider-ca_left" class="slider"></div>
									<div class="clear"></div>
								</div>
								<div class="slider-box">
									<label for="ca_top">Atkāpe no augšas:</label>
									<input class="form_input" type="number" name="ca_top" value="" id="ca_top" maxlength="3" min="0" max="200"/>
									<div id="slider-ca_top" class="slider"></div>
									<div class="clear"></div>
								</div>
								<div class="slider-box">
									<label for="ca_width">Izmantotais platums:</label>
									<input class="form_input" type="number" name="ca_width" value="" id="ca_width" maxlength="3" min="20" max="100"/><i>%</i>
									<div id="slider-ca_width" class="slider"></div>
									<div class="clear"></div>
								</div>
								<div class="slider-box">
									<label for="ca_height">Izmantotais augstums:</label>
									<input class="form_input" type="number" name="ca_height" value="" id="ca_height" maxlength="3" min="20" max="100"/><i>%</i>
									<div id="slider-ca_height" class="slider"></div>
									<div class="clear"></div>
								</div>
								<div class="clear"></div>
							</div>
						</div> 	
					</div>
				</li>
				<li><span class="nav_t">Sērijas</span>
					<div class="nav_block" id="series">
						<div id="opt3" class="opt">
							<ul class="opt1_list">
								<li><a href="#s_col">Sēriju krāsas</a></li>
							</ul>
							<ul class="clear"></ul>
							<div id="s_col" class="tab"></div>
						</div>
					</div>
				</li>
				<li class="line_bar"><span class="nav_t">Asis</span>
					<div class="nav_block">
						<div id="opt2" class="opt">
							<ul class="opt1_list">
								<li><a href="#yaxis">Y ass</a></li>
								<li><a href="#xaxis">X ass</a></li>
							</ul>
							<ul class="clear"></ul>
							<div id="yaxis" class="tab">
								<label for="vAxis_title">Y ass nosaukums:</label>
								<input class="form_input" type="text" name="vAxis_title" value="" id="vAxis_title" maxlength="100"/>
								<div class="clear"></div>
								<div class="radio-box">
									<label>Datu attēlošanas virziens:</label>
									<input class="radio form_input" type="radio" name="vAxis_direction" id="v_dir_left" value="left">
									<label for="v_dir_left" class="radio_l">No kreisās</label>
									<input class="radio form_input" type="radio" name="vAxis_direction" id="v_dir_right" value="right">
									<label for="v_dir_right" class="radio_l">No labās</label>
									<div class="clear"></div>
								</div>
								<div class="slider-box-duo">
									<label for="v_min_max">Vērtību datu intervāls:</label>
									<label for="v_minValue" style="float: left;">Min</label>
									<label for="v_maxValue" style="float: right;">Max</label>
									<div class="clear"></div>
									<input class="form_input" type="number" name="v_minValue" value="" id="v_minValue" maxlength="3" style="float: left;"/>
									<div id="slider-v_min_max" class="slider"></div>
									<input class="form_input" type="number" name="v_maxValue" value="" id="v_maxValue" maxlength="3" style="float: right;"/>
									<div class="clear"></div>
								</div>
							</div>
							<div id="xaxis" class="tab">
								<label for="hAxis_title">X ass nosaukums:</label>
								<input class="form_input" type="text" name="hAxis_title" value="" id="hAxis_title" maxlength="100"/>
								<div class="clear"></div>
								<div class="radio-box">
									<label>Datu attēlošanas virziens:</label>
									<input class="radio form_input" type="radio" name="hAxis_direction" id="h_dir_left" value="left">
									<label for="h_dir_left" class="radio_l">No kreisās</label>
									<input class="radio form_input" type="radio" name="hAxis_direction" id="h_dir_right" value="right">
									<label for="h_dir_right" class="radio_l">No labās</label>
									<div class="clear"></div>
								</div>
								<div class="slider-box">
									<label for="h_slantedTextAngle">Vērtību leņķiskais novietojums:</label>
									<input class="form_input" type="number" name="h_slantedTextAngle" value="" id="h_slantedTextAngle" maxlength="2" min="1" max="90"/><i>°</i>
									<div id="slider-h_slantedTextAngle" class="slider"></div>
									<div class="clear"></div>
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<div class="clear"></div>
			<button class="button" type="button" value="Saglabāt" id="submit3"><span><span>Saglabāt</span></span></button>
			<div class="clear"></div>
			<?php if($chart_data): ?>	
			<div id="chart_div" style="width: <?php echo $chart->width; ?>px; height: <?php echo $chart->height; ?>px; margin: 0 auto;"></div>
			<?php endif; ?>
			<input style="display: none;" type="text" name="type" value="" id="type"/>
			<?php echo form_close();?>
		</div>  
		<div id="share" class="tab" <?php echo $style4; ?>>
			<div class="block">
				<div class="box-title"><h4>Publisko diagrammu</h4></div>
				<?php
					if($chart->status == 0) { $b1 = 'style="display: block;"'; $b2 = 'style="display: none;"'; }
					else { $b1 = 'style="display: none;"'; $b2 = 'style="display: block;"';	}
				?>
				<?php if($chart->id > 10000002):?>
				<button <?php echo $b1; ?> class="button" type="button" value="Publiskot" id="submit4"><span><span>Publiskot</span></span></button>
				<button <?php echo $b2; ?> class="button" type="button" value="Nepubliskot" id="submit5"><span><span>Nepubliskot</span></span></button>
				<?php endif; ?>
				<div class="clear"></div>
				<div id="embed" <?php echo $b2; ?>>
					<label for="public_url">Publiskā saite:</label>
					<input onClick="this.select();" type="text" name="public_url"  value="<?php echo base_url() . 'chart/' .  $chart->id; ?>" id="public_url" />
					<label for="iframe-code">Ieliec savas lapas saturā:</label>
					<?php $iframe =  '<iframe src="' . base_url() . 'tool/view/' . $chart->id . '" width="' . $chart->width . '" height="' . $chart->height . '" scrolling="no" frameborder="0" style="border:none; margin: 0 auto; display: block;" allowtransparency="true" allowfullscreen="allowfullscreen" webkitallowfullscreen="webkitallowfullscreen" mozallowfullscreen="mozallowfullscreen" oallowfullscreen="oallowfullscreen" msallowfullscreen="msallowfullscreen"></iframe>';?>
					<textarea onClick="this.select();" id="iframe-code"><?php echo hsc($iframe); ?></textarea>
				</div>
			</div>
			<?php if($chart_data): ?>
			<div class="block">
				<div class="box-title"><h4>Saglabā kā attēlu</h4></div>
				<div class="clear"></div>
				<p style="margin: 0 0 10px;">Izveidoto diagrammu var saglabāt kā .png attēlu:</p>
				<a class="download" id="download" href="" download>Lejupielādēt</a>
			</div> 
			<?php endif; ?>	
			<div class="clear"></div>
		</div> 
		<div class="clear"></div>
	</div>
	<canvas id="canvas" width="1000" height="600"></canvas> 
</div>
<?php
    $this->load->view("tool/footer");
?>