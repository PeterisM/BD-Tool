<?php
	$this->load->view("tool/head");
?>
	<body>
        <div class="page">
            <div class="header">
                <div class="low_header">
                    <div class="logo">
                        <a href="<?php echo base_url(); ?>"><h1>Datu vizuāla attēlošana tīmekļa vietnēs</h1></a>
                    </div> 
					<div class="top-links">
						<ul>
							<li style="font-weight: bold; color: #fff">Pieteicies kā: <span style="color: #FFBA00"><?php echo $user; ?></span></li>
							<?php if ($user_id != 1): ?>
							<li><a href="<?php echo base_url(); ?>tool/password">Mainīt paroli</a></li>
							<?php endif; ?>
							<li><a href="<?php echo base_url(); ?>tool">Manas diagrammas</a></li>
							<li><a href="<?php echo base_url(); ?>tool/create">Izveidot diagrammu</a></li>
							<li><a href="<?php echo base_url(); ?>tool/logout">Iziet</a></li>
						</ul>	
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
                </div>
            </div>    