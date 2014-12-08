var fade_in = 400;
var fade_out = 2400;
var required = 'must';
$j(document).ready(function() {
	

    $j('#tabs > ul li a').click(function(){ //Galvenā izvēlne
        $j('#tabs > ul li').removeClass('active');
        $j(this).parent().addClass('active');
        var currentTab = $j(this).attr('href');
        $j('#tabs > div').hide();
        $j(currentTab).show();
        return false;
    });
	
	$j('#opt1 > div').hide();
    $j('#opt1 > div:first').show();
    $j('#opt1 ul li:first').addClass('active');
    $j('#opt1 > ul > li > a').click(function(){//Diagramma
        $j('#opt1 > ul >li').removeClass('active');
        $j(this).parent().addClass('active');
        var currentTab = $j(this).attr('href');
        $j('#opt1 > div').hide();
        $j(currentTab).show();
        return false;
    });
	
	$j('#opt3 > div').hide();
    $j('#opt3 > div:first').show();
    $j('#opt3 ul li:first').addClass('active');
    $j('#opt3 > ul > li > a').click(function(){//Serijas
        $j('#opt3 > ul > li').removeClass('active');
        $j(this).parent().addClass('active');
        var currentTab = $j(this).attr('href');
        $j('#opt3 > div').hide();
        $j(currentTab).show();
        return false;
    });
	
	$j('#opt2 > div').hide();
    $j('#opt2 > div:first').show();
    $j('#opt2 ul li:first').addClass('active');
    $j('#opt2 > ul > li > a').click(function(){//Asis
        $j('#opt2 > ul > li').removeClass('active');
        $j(this).parent().addClass('active');
        var currentTab = $j(this).attr('href');
        $j('#opt2 > div').hide();
        $j(currentTab).show();
        return false;
    });
	
    $j('#backgroundColor').colpick({//Color Picker
		layout:'rgbhex',
		submit:0,
		color: window.chart.backgroundColor,
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			$j(el).css('border-color','#'+hex);
			if(!bySetColor) $j(el).val(hex);
			update_config(); draw_lines(); make_img();
		}
	}).keyup(function(){$j(this).colpickSetColor(this.value);});
	
	$j('#message').click(function(){ $j(this).fadeOut(1200); }); //ziņojuma noņemšana
	
	$j('#chart_nav .nav_t').click(function(event){ //uzstādījumu parādīšana
		event.preventDefault();
		$j('#chart_nav > li').removeClass('active');
		$j(this).parent().addClass('active');
		$j('#chart_nav .nav_block').hide();
		$j(this).next().show();
	});
	
	$j(document).click(function(e) { // paslēpj uzstādījumus
	   if (!$j(e.target).is(".colpick, .colpick div, .colpick input, #chart_nav li, #chart_nav div, #chart_nav input, #chart_nav label, #chart_nav i, #chart_nav span")) {
			$j('#chart_nav .nav_block').hide();
			$j('.colpick').hide();
			$j('#chart_nav > li').removeClass('active');
		}
	});
	
	$j('#types li').click(function(){ //Izvēlas diagrammas tipu
		var type = $j(this).attr('id');
		window.chart.type = type;
		$j('#type').val(window.chart.type);
		update_type();
		$j('#types li').removeClass('active');
		$j(this).addClass('active');
		make_img();
	});
	//uzstāda vērtību izvēles skalu
	$j("#slider-height").slider({ range: "min", value: window.chart.height,	min: 200, max: 2000,
	slide: function(event, ui) { $j("#height").val(ui.value).change(); } });
	$j("#slider-width").slider({ range: "min", value: window.chart.width, min: 200, max: 3000,
	slide: function(event, ui) { $j("#width").val(ui.value).change(); } });
	$j("#slider-ca_left").slider({ range: "min", value: window.chart.ca_left, min: 0, max: 200,
	slide: function(event, ui) { $j("#ca_left").val(ui.value).change(); } });
	$j("#slider-ca_top").slider({ range: "min", value: window.chart.ca_top, min: 0, max: 200,
	slide: function(event, ui) { $j("#ca_top").val(ui.value).change(); } });
	$j("#slider-ca_width").slider({ range: "min", value: window.chart.ca_width, min: 20, max: 100,
	slide: function(event, ui) { $j("#ca_width").val(ui.value).change(); } });
	$j("#slider-ca_height").slider({ range: "min", value: window.chart.ca_height, min: 20, max: 100,
	slide: function(event, ui) { $j("#ca_height").val(ui.value).change(); } });
	$j("#slider-h_slantedTextAngle").slider({ range: "min", value: window.chart.h_slantedTextAngle, min: 1, max: 90,
	slide: function(event, ui) { $j("#h_slantedTextAngle").val(ui.value).change(); } });
	$j("#slider-groupWidth").slider({ range: "min", value: window.chart.groupWidth, min: 0, max: 100,
	slide: function(event, ui) { $j("#groupWidth").val(ui.value).change(); } });
	
	$j("#slider-v_min_max").slider({range: true, min: -100, max: 100, values: [window.chart.v_minValue, window.chart.v_maxValue],
    slide: function(event, ui){$j('#v_minValue').val(ui.values[0]).change(); $j('#v_maxValue').val(ui.values[1]).change();}});	
	
	//saglabā visas diagrammas konfigurācijas izmaiņas un pārzīmē diagrammu
	$j('.form_input').on('change', function() { update_config(); draw_lines(); make_img(); }); 
	$j('#width').on('change', function() { $j("#chart_div").css('width', $j('#width').val()); update_iframe(); }); 
	$j('#height').on('change', function() { $j("#chart_div").css('height', $j('#height').val()); update_iframe(); }); 
	
	$j('#submit2').click(function(event) { //Saglabā diagrammas datus
		
		var data_url = window.base_url + '/tool/save_data/' + window.chart.id;
		var handsontable = $j("#chart_data").data('handsontable');
		$j.ajax({
			dataType: 'json', 
			url: data_url,
			type: 'POST',
			data: {"data": handsontable.getData()}, //returns all cells' data
			success: function(jdata) {
				if(jdata.redirect) window.location = window.base_url;
				else
				{
					window.location = window.base_url + '/tool/edit/' + window.chart.id;
				}
			}
		});		
		return false;
	});
	
	$j('#submit3').click(function(event) { //Saglabā diagrammas konfigurāciju
		event.preventDefault();
		var img_url = window.base_url + '/tool/save_img/' + window.chart.id;
		var canvas = document.getElementById('canvas');
		var img_data = { img : canvas.toDataURL() };
		var form_data = make_form_data();
		$j.ajax({
			dataType: 'json', 
			url: $j('#form2').attr('action'),
			type: 'POST',
			data: form_data,
			async : false,
			cache: false,
			success: function(jdata) {
				if(jdata.redirect) window.location = window.base_url;
				else
				{
					$j.ajax({
						dataType: 'json', 
						url: img_url,
						type: 'POST',
						data: img_data,
						async : false,
						cache: false,
						success: function(idata) {
							if(idata.redirect) window.location = window.base_url;
							else
							{
								if(jdata.message){$j("#message").css('display', 'none'); $j("#message").html(jdata.message).addClass('message').fadeIn(fade_in);} 
								if(jdata.success)
								{
									update_config();
									draw_lines();
								}
							}
						}
					});
				}
			}
		});		
		return false;
	});
	
	$j('#submit4').click(function() { //Publisko diagrammu
		var p_url = window.base_url + '/tool/make_public/' + window.chart.id;
		$j.ajax({
			dataType: 'json', 
			url: p_url,
			type: 'POST',
			async : false,
			cache: false,
			success: function(jdata) {
				if(jdata.redirect) window.location = window.base_url;
				else
				{
					if(jdata.message){$j("#message").css('display', 'none'); $j("#message").html(jdata.message).addClass('message').fadeIn(fade_in);} 
					if(jdata.success)
					{
						$j("#submit4").fadeOut().css('display', 'none');
						$j("#submit5").fadeIn().css('display', 'block');
						$j("#embed").fadeIn().css('display', 'block');
					}
				}
			}
		});
		return false;
	});
	
	$j('#submit5').click(function() { //Nepublisko diagrammu
		var u_url = window.base_url + '/tool/un_public/' + window.chart.id;
		$j.ajax({
			dataType: 'json', 
			url: u_url,
			type: 'POST',
			async : false,
			cache: false,
			success: function(jdata) {
				if(jdata.redirect) window.location = window.base_url;
				else
				{
					if(jdata.message){$j("#message").css('display', 'none'); $j("#message").html(jdata.message).addClass('message').fadeIn(fade_in);} 
					if(jdata.success)
					{
						$j("#submit5").fadeOut().css('display', 'none');
						$j("#embed").fadeOut().css('display', 'none');
						$j("#submit4").fadeIn().css('display', 'block');
					}
				}
			}
		});
		return false;
	});
});

function set_config(){ //uzstāda konfigurāciju ievades vērtības
	$j('#title').val(window.chart.title);
	$j('#height').val(window.chart.height);
	$j('#width').val(window.chart.width);
	$j('#type').val(window.chart.type);
	$j('#ca_left').val(window.chart.ca_left);
	$j('#ca_top').val(window.chart.ca_top);
	$j('#ca_width').val(window.chart.ca_width);
	$j('#ca_height').val(window.chart.ca_height);
	$j('#backgroundColor').val(window.chart.backgroundColor);
	$j('#t_poz_' + window.chart.titlePosition).prop("checked", true);
	$j('#l_poz_' + window.chart.legend_position).prop("checked", true);
		
	switch (window.chart.type) { 
        case 'line':
			$j('#' + window.chart.orientation).prop("checked", true);
			$j('#' + window.chart.focusTarget).prop("checked", true);
			$j('#vAxis_title').val(window.chart.vAxis_title);
			$j('#v_minValue').val(window.chart.v_minValue);
			$j('#v_maxValue').val(window.chart.v_maxValue);
			$j('#v_dir_' + window.chart.vAxis_direction).prop("checked", true);
			$j('#hAxis_title').val(window.chart.hAxis_title);
			$j('#h_dir_' + window.chart.hAxis_direction).prop("checked", true);
			$j('#h_slantedTextAngle').val(window.chart.h_slantedTextAngle);
			break;
		case 'bar':
			$j('#' + window.chart.orientation).prop("checked", true);
			$j('#' + window.chart.focusTarget).prop("checked", true);
			$j('#vAxis_title').val(window.chart.vAxis_title);
			$j('#v_minValue').val(window.chart.v_minValue);
			$j('#v_maxValue').val(window.chart.v_maxValue);
			$j('#v_dir_' + window.chart.vAxis_direction).prop("checked", true);
			$j('#hAxis_title').val(window.chart.hAxis_title);
			$j('#h_dir_' + window.chart.hAxis_direction).prop("checked", true);
			$j('#h_slantedTextAngle').val(window.chart.h_slantedTextAngle);
			$j('#groupWidth').val(window.chart.groupWidth);
			break;
		case 'area':
			$j('#' + window.chart.orientation).prop("checked", true);
			$j('#' + window.chart.focusTarget).prop("checked", true);
			$j('#vAxis_title').val(window.chart.vAxis_title);
			$j('#v_minValue').val(window.chart.v_minValue);
			$j('#v_maxValue').val(window.chart.v_maxValue);
			$j('#v_dir_' + window.chart.vAxis_direction).prop("checked", true);
			$j('#hAxis_title').val(window.chart.hAxis_title);
			$j('#h_dir_' + window.chart.hAxis_direction).prop("checked", true);
			$j('#h_slantedTextAngle').val(window.chart.h_slantedTextAngle);
			break; 
    };

	
	$j('#' + window.chart.type).addClass('active');
}

function update_config(){ //atjauno JS diagrammas globālo objektu vērtības
	window.chart.title = $j('#title').val();
	window.chart.height = $j('#height').val();
	window.chart.width = $j('#width').val();
	window.chart.ca_left = $j('#ca_left').val();
	window.chart.ca_top = $j('#ca_top').val();
	window.chart.ca_width = $j('#ca_width').val();
	window.chart.ca_height = $j('#ca_height').val();
	window.chart.backgroundColor = $j('#backgroundColor').val();
	window.chart.titlePosition = $j("input[name='titlePosition']:checked").val();
	window.chart.legend_position = $j("input[name='legend_position']:checked").val();

	switch (window.chart.type) { 
        case 'line':
			window.chart.orientation = $j("input[name='orientation']:checked").val();
			window.chart.focusTarget = $j("input[name='focusTarget']:checked").val();
			window.chart.vAxis_title = $j('#vAxis_title').val();
			window.chart.v_minValue = $j('#v_minValue').val();
			window.chart.v_maxValue = $j('#v_maxValue').val();
			window.chart.vAxis_direction = $j("input[name='vAxis_direction']:checked").val();
			window.chart.hAxis_title = $j('#hAxis_title').val();
			window.chart.hAxis_direction = $j("input[name='hAxis_direction']:checked").val();
			window.chart.h_slantedTextAngle = $j('#h_slantedTextAngle').val();
			
			break;
		case 'bar':
			window.chart.orientation = $j("input[name='orientation']:checked").val();
			window.chart.focusTarget = $j("input[name='focusTarget']:checked").val();
			window.chart.vAxis_title = $j('#vAxis_title').val();
			window.chart.v_minValue = $j('#v_minValue').val();
			window.chart.v_maxValue = $j('#v_maxValue').val();
			window.chart.vAxis_direction = $j("input[name='vAxis_direction']:checked").val();
			window.chart.hAxis_title = $j('#hAxis_title').val();
			window.chart.hAxis_direction = $j("input[name='hAxis_direction']:checked").val();
			window.chart.h_slantedTextAngle = $j('#h_slantedTextAngle').val();
			window.chart.groupWidth = $j('#groupWidth').val();
			
			break;
		case 'area':
			window.chart.orientation = $j("input[name='orientation']:checked").val();
			window.chart.focusTarget = $j("input[name='focusTarget']:checked").val();
			window.chart.vAxis_title = $j('#vAxis_title').val();
			window.chart.v_minValue = $j('#v_minValue').val();
			window.chart.v_maxValue = $j('#v_maxValue').val();
			window.chart.vAxis_direction = $j("input[name='vAxis_direction']:checked").val();
			window.chart.hAxis_title = $j('#hAxis_title').val();
			window.chart.hAxis_direction = $j("input[name='hAxis_direction']:checked").val();
			window.chart.h_slantedTextAngle = $j('#h_slantedTextAngle').val();
			break; 
    };
	
	$j.each(window.chart.series_titles, function( index, value ) {
		window.chart.series[index] =  $j('#' + index + '_series').val();
	});	
}

function make_form_data(){ //sagatavo diagrammas datus JSON formā nosūtīšanai uz serveri
	switch (window.chart.type) { 
        case 'line':
			var form_data = {
				orientation: $j("input[name='orientation']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				focusTarget: $j("input[name='focusTarget']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				vAxis_title: $j('#vAxis_title').val().replace(/(["])/g, "\\\\\\$1"),
				v_minValue: $j('#v_minValue').val().replace(/(["])/g, "\\\\\\$1"),
				v_maxValue: $j('#v_maxValue').val().replace(/(["])/g, "\\\\\\$1"),
				vAxis_direction: $j("input[name='vAxis_direction']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				hAxis_title: $j('#hAxis_title').val().replace(/(["])/g, "\\\\\\$1"),
				hAxis_direction: $j("input[name='hAxis_direction']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				h_slantedTextAngle: $j('#h_slantedTextAngle').val().replace(/(["])/g, "\\\\\\$1"),
			};
			break;
		case 'bar':
			var form_data = {
				orientation: $j("input[name='orientation']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				focusTarget: $j("input[name='focusTarget']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				vAxis_title: $j('#vAxis_title').val().replace(/(["])/g, "\\\\\\$1"),
				v_minValue: $j('#v_minValue').val().replace(/(["])/g, "\\\\\\$1"),
				v_maxValue: $j('#v_maxValue').val().replace(/(["])/g, "\\\\\\$1"),
				vAxis_direction: $j("input[name='vAxis_direction']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				hAxis_title: $j('#hAxis_title').val().replace(/(["])/g, "\\\\\\$1"),
				hAxis_direction: $j("input[name='hAxis_direction']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				h_slantedTextAngle: $j('#h_slantedTextAngle').val().replace(/(["])/g, "\\\\\\$1"),
				groupWidth: $j('#groupWidth').val().replace(/(["])/g, "\\\\\\$1"),
			};
			break;
		case 'area':
			var form_data = {
				orientation: $j("input[name='orientation']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				focusTarget: $j("input[name='focusTarget']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				vAxis_title: $j('#vAxis_title').val().replace(/(["])/g, "\\\\\\$1"),
				v_minValue: $j('#v_minValue').val().replace(/(["])/g, "\\\\\\$1"),
				v_maxValue: $j('#v_maxValue').val().replace(/(["])/g, "\\\\\\$1"),
				vAxis_direction: $j("input[name='vAxis_direction']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				hAxis_title: $j('#hAxis_title').val().replace(/(["])/g, "\\\\\\$1"),
				hAxis_direction: $j("input[name='hAxis_direction']:checked").val().replace(/(["])/g, "\\\\\\$1"),
				h_slantedTextAngle: $j('#h_slantedTextAngle').val().replace(/(["])/g, "\\\\\\$1"),
			};
			break; 
    }

	var default_data = {};
	default_data['type'] = $j('#type').val().replace(/(["])/g, "\\\\\\$1");
	default_data['title'] = $j('#title').val().replace(/(["])/g, "\\\\\\$1");
	default_data['height'] = $j('#height').val().replace(/(["])/g, "\\\\\\$1");
	default_data['width'] = $j('#width').val().replace(/(["])/g, "\\\\\\$1");
	default_data['ca_left'] = $j('#ca_left').val().replace(/(["])/g, "\\\\\\$1");
	default_data['ca_top'] = $j('#ca_top').val().replace(/(["])/g, "\\\\\\$1");
	default_data['ca_width'] = $j('#ca_width').val().replace(/(["])/g, "\\\\\\$1");
	default_data['ca_height'] = $j('#ca_height').val().replace(/(["])/g, "\\\\\\$1");		
	default_data['backgroundColor'] = $j('#backgroundColor').val().replace(/(["])/g, "\\\\\\$1");			
	default_data['series_count'] = window.chart.series_count;		
	default_data['titlePosition'] = $j("input[name='titlePosition']:checked").val().replace(/(["])/g, "\\\\\\$1");		
	default_data['legend_position'] = $j("input[name='legend_position']:checked").val().replace(/(["])/g, "\\\\\\$1");
	$j.each(window.chart.series_titles, function( index, value ) {
		default_data[index + '_series'] = $j('#' + index + '_series').val().replace(/(["])/g, "\\\\\\$1");
	});	
	$j.extend(true, form_data, default_data);
	return form_data;
}

function make_img(){ //sagatavo diagrammu attēla formātā
	var svg = $j("#chart_div > div:first > div").html();
	canvg('canvas', svg, { ignoreMouse: true });
	var canvas = document.getElementById('canvas');
	var imgURL = canvas.toDataURL();
	document.getElementById('download').href = imgURL;
}

function update_type(){ //samaina attiecīgās vērtības, nomainoties giagrammas tipam 
	switch (window.chart.type) { 
        case 'line':
			google.chart = new google.visualization.LineChart(document.getElementById('chart_div'));
			$j('.line_bar').show();
			$j('.bar').hide();
			break;
		case 'bar':
			google.chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
			$j('.line_bar').show();
			$j('.bar').show();
			break;
		case 'area':
			google.chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
			$j('.line_bar').show();
			$j('.bar').hide();
			break; 
    }
	google.chart.draw(window.charts_data, window.charts_opt);
}

function update_iframe(){ //uzstāda jaunās iframe vērtības
	var iframe =  '<iframe src="' + window.base_url + 'tool/view/' + window.chart.id + '" width="' + window.chart.width + '" height="' + window.chart.height + '" scrolling="no" frameborder="0" style="border:none; margin: 0 auto; display: block;" allowtransparency="true" allowfullscreen="allowfullscreen" webkitallowfullscreen="webkitallowfullscreen" mozallowfullscreen="mozallowfullscreen" oallowfullscreen="oallowfullscreen" msallowfullscreen="msallowfullscreen"></iframe>';
	$j('#iframe-code').html(iframe);
}