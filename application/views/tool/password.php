<?php
	$this->load->view("tool/header");
?>
<div class="content">
    <style>
        span.error { display: none; }
    </style>
	<div class="admin box-title"><h3>Paroles mainīšana</h3></div>
	<div class="input-box">
		<?php echo form_open('tool/check_pass', 'id="form"'); ?>
			<p id="message"></p>
            <label for="currpassword">Pašreizējā parole</label>
			<input type="password" name="currpassword" id="currpassword" />
			<span class="error" id="currpassword_error"></span>
			</br>
            <label for="password">Jaunā parole</label>
			<input type="password" name="password" id="password" maxlength="20" />
			<span class="error" id="password_error"></span>
            <label for="passconf">Jaunā parole vēlreiz</label>
			<input type="password" name="passconf" id="passconf" maxlength="20" />
			<span class="error" id="passconf_error"></span>
			<div class="clear"></div>
            <button class="button" type="submit" value="Mainīt" id="submit"><span><span>Mainīt</span></span></button>
            <div class="clear"></div>
		<?php echo form_close();?>
        <script type="application/javascript">
            $j(document).ready(function() {
                var fade_in = 1200;
                var required = 'must';
                $j('#submit').click(function() {
                    var form_data = {
                        currpassword : $j('#currpassword').val(),
                        password : $j('#password').val(),
                        passconf : $j('#passconf').val()
                    };
                    $j.ajax({
                        dataType: "json",
                        url: $j('#form').attr('action'),
                        type: 'POST',
                        async : false,
                        cache: false,
                        data: form_data,
                        success: function(jdata) {
                            $j('.error').each( function(){
                                $j(this).css('display', 'none');
                            });
                            $j("#message").css('display', 'none');
                            if (jdata.currpassword){$j("#currpassword_error").html(jdata.currpassword).fadeIn(fade_in).css('display', 'block'); $j("#currpassword").addClass(required).val('');} 
                            else{$j("#currpassword").removeClass(required);}
                            
                            if (jdata.password){$j("#password_error").html(jdata.password).fadeIn(fade_in).css('display', 'block'); $j("#password").addClass(required).val('');} 
                            else{$j("#password").removeClass(required);}
                            
                            if (jdata.passconf){$j("#passconf_error").html(jdata.passconf).fadeIn(fade_in).css('display', 'block'); $j("#passconf").addClass(required).val('');} 
                            else{$j("#passconf").removeClass(required);}
                            
                            if (jdata.message){$j("#message").html(jdata.message).addClass('message').fadeIn(fade_in); $j("#currpassword").val(''); $j("#password").val(''); $j("#passconf").val('');} 
                            else{$j("#message").text('').css('display', 'none').removeClass('message');}
                        }
                    });
                    return false;
                });
            });
        </script>
	</div>
</div>
<?php
    $this->load->view("tool/footer");
?>