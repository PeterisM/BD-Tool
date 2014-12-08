<?php
	$this->load->view("root/header");
?>
<div class="content chart">
	<h3><?php echo $chart->name; ?></h3>
	<iframe src="<?php echo base_url() . 'tool/view/' . $chart->id; ?>" width="<?php echo $chart->width; ?>" height="<?php echo $chart->height; ?>" scrolling="no" frameborder="0" style="border:none; margin: 0 auto; display: block;" allowtransparency="true" allowfullscreen="allowfullscreen" webkitallowfullscreen="webkitallowfullscreen" mozallowfullscreen="mozallowfullscreen" oallowfullscreen="oallowfullscreen" msallowfullscreen="msallowfullscreen"></iframe>
	<p><b>Autors:</b> <span class="imp"><?php echo $chart->user_name; ?></span></p>
</div>
<?php
    $this->load->view("root/footer");
?>