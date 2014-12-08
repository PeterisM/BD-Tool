<?php
	$this->load->view("root/header");
?>
    <div class="content home">
		<img style="margin: 0 auto; display: block;" src="http://infosthetics.com/archives/what_is_visualization.jpg">
		<div class="block">
			<h4>Apraksts</h4>
			<p>Rīks paredzēts datu vizuālai attēlošanai tīmekļa vietnēs, veidojot interakstīvas diagrammas ar
			vieglu datu vērtību nolasīšanu.
			</p>
			<p>Rīks nodrošina sekojošas iespējas:</p>
			<ul>
				<li>iepriekš sagatavotu datu augšupielāde .csv formātā;</li>
				<li>augšupielādēto datu labošanu vai lejupielāde;</li>
				<li>diagrammu tipu izvēli:
					<ul>
						<li>stabiņu diagramma,</li>
						<li>līniju diagramma,</li>
						<li>sektoru diagramma;</li>
					</ul>
				</li>
				<li>diagrammu vizuālo konfigurēšanu;</li>
				<li>publiskošanu saites vai iframe elementa veidā;</li>
				<li>izveidotās diagrammas saglabāšanu kā attēlu.</li>
			</ul>
		</div>
		<div class="block">
			<h4>Rīka pamatuzbūve</h4>
			<p>Rīks izstrādāts PHP programmēšanas valodā, izmantojot 
			<a href="http://ellislab.com/codeigniter">CodeIgniter</a>					
			2.1.4 satura vadības sistēmas ietvaru,
			lai nodrošināšanu tīmekļa vietnes pamatfunkcijas.</p>
			<p>Lai nodrošinātu efektīvāku klienta puses tīmekļa lapu satura 
			pārveidošanu, rikā izmantota   
			<a href="http://jquery.com/">jQuery</a>					
			JavaScript optimizācijas bibliotēka.</p>
			<p>Par pamatu interaktīvu diagrammu veidošanai izmantota  
			<a href="https://developers.google.com/chart/">Google Charts</a>					
			JavaScript bibliotēka.</p>
			<p>Lai nodrošinātu ērtu diagrammu datu labošanu tabulā, rikā izmantots   
			<a href="http://handsontable.com/">Handsontable</a>					
			risinājums.</p>
			<p>Un ērtākai datu sēriju krāsas izvēlei rikā izmantots   
			<a href="http://www.jqueryrain.com/?FWAzbWcA">Colpick</a>					
			risinājums.</p>
		</div>
		<div class="clear"></div>
    </div>
<?php
    $this->load->view("root/footer");
?>