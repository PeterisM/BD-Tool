window.chart = {};
//izveido diagrammas konfigurâciju
function make_opt(){
    
	var v_direction = 1;
	switch (window.chart.vAxis_direction) {
	case 'left':v_direction = 1;break;
	case 'right':v_direction = -1;break;
	default:v_direction = 1;
	}
	
	var h_direction = 1;
	switch (window.chart.hAxis_direction) {
	case 'left':h_direction = 1;break;
	case 'right':h_direction = -1;break;
	default:h_direction = 1;
	}
	
	window.charts_opt = {
		title: window.chart.title,
		height: window.chart.height,
		width: window.chart.width,
		chartArea: {left: window.chart.ca_left, top: window.chart.ca_top, width: window.chart.ca_width + '%', height: window.chart.ca_height + '%'},
		orientation: window.chart.orientation, //horizontal vertical
		titlePosition: window.chart.titlePosition, //in out none
		backgroundColor: '#' + window.chart.backgroundColor,
		focusTarget: window.chart.focusTarget, //datum category
		series: {},
		legend: { 
			position: window.chart.legend_position, //top bottom right in none
			textStyle: {color: '#000', fontSize: 12}
		},
		
		titleTextStyle: {color: '#000', fontName: 'Times-Roman', fontSize: 18, bold: true, italic: false },		
		axisTitlesPosition: 'out', //in out none
		fontSize: 12,
		fontName: 'Times', //
		
		
		vAxis: {
			title: window.chart.vAxis_title,
			maxValue: window.chart.v_maxValue,
			minValue: window.chart.v_minValue,
			direction: v_direction, //1 or -1
			
			titleTextStyle: { color: '#000', fontName: 'Times-Roman', fontSize: 18, bold: true, italic: false },
			textStyle: { color: '#000', fontName: 'Times-Roman', fontSize: 12, bold: true, italic: false },
			viewWindowMode: 'pretty', //pretty maximized explicit
			//gridlines: {color: '#ccc', count: 'auto'},
			//minorGridlines: { count: 2 }			
		},
		hAxis: {
			title: window.chart.hAxis_title,
			direction: h_direction, //1 or -1
			slantedText: true,
			slantedTextAngle: window.chart.h_slantedTextAngle,
			showTextEvery: 1,
			
			titleTextStyle: { color: '#000', fontName: 'Times-Roman', fontSize: 18, bold: true, italic: false },
			textStyle: { color: '#000', fontName: 'Times-Roman', fontSize: 12, bold: true, italic: false },
			viewWindowMode: 'pretty', //pretty maximized explicit
			//maxValue: 100,
			//minValue: 0,
		},
		bar: { groupWidth: window.chart.groupWidth + '%' },
	};
	
	var series = {};
	$j.each(window.chart.series_titles, function( index, value ) {
		series[index] = {};
	});
	$j.extend(true, window.charts_opt.series, series);
	
	$j.each(window.chart.series_titles, function( index, value ) {
		var temp = {};
		temp['color'] = '#' + window.chart.series[index];
		$j.extend(true, window.charts_opt.series[index], temp);
	});	
};  
//uzzimç diagrammu
function draw_lines(){
    
	make_opt();
	google.chart.draw(window.charts_data, window.charts_opt);
};  