<?php
	$this->load->view("root/header");
?>
		<div class="content sign">
            <div class="input-box">
                <div class="title">
                    <h3>Reģistrācija</h3>
                </div>
                <div class="form">
                    <p id="error"></p>
                    <?php echo form_open('root/register', 'id="form"'); ?>
                        <label for="email">E-pasts:</label>
                        <input type="text" name="email" value="<?php echo set_value('email'); ?>" id="email" />
                        <span class="error" id="email_error"></span>
						<label for="name">Lietotājvārds:</label>
                        <input type="text" name="name" maxlength="30" value="<?php echo set_value('name'); ?>" id="name" />
                        <span class="error" id="name_error"></span>
						<label for="password">Parole</label>
						<input type="password" name="password" id="password" />
						<span class="error" id="password_error"></span>
						<label for="passconf">Parole vēlreiz</label>
						<input type="password" name="passconf" id="passconf"/>
						<span class="error" id="passconf_error"></span>
                        <div class="clear"></div>
                        <button class="button" type="submit" value="Reģistrēties" id="submit"><span><span>Reģistrēties</span></span></button>
                        <div class="clear"></div>
                        <script type="application/javascript">
                            $j(document).ready(function() {
                                var fade_in = 400;
                                var required = 'must';
                                $j('#submit').click(function() {
                                    var form_data = {
                                        name : $j('#name').val(),
                                        email : $j('#email').val(),
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
                                            $j("#error").css('display', 'none');
											if (jdata.name){$j("#name_error").html(jdata.name).fadeIn(fade_in).css('display', 'block'); $j("#name").addClass(required);} 
                                            else{$j("#name").removeClass(required);}
                                            if (jdata.email){$j("#email_error").html(jdata.email).fadeIn(fade_in).css('display', 'block'); $j("#email").addClass(required);} 
                                            else{$j("#email").removeClass(required);}
                                            
                                            if(jdata.password){$j("#password_error").html(jdata.password).fadeIn(fade_in).css('display', 'block'); $j("#password").addClass(required); $j("#password").val('');} 
                                            else{$j("#password").removeClass(required);}
											if(jdata.passconf){$j("#passconf_error").html(jdata.passconf).fadeIn(fade_in).css('display', 'block'); $j("#passconf").addClass(required); $j("#passconf").val('');} 
                                            else{$j("#passconf").removeClass(required);}
                                            
                                            if(jdata.error){$j("#error").html(jdata.error).addClass('error').fadeIn(fade_in); } 
                                            else{$j("#error").text('').css('display', 'none').removeClass('error');}
                                            
                                            if(jdata.success) window.location = '<?php echo base_url() . 'tool/'; ?>';
                                        }
                                    });
                                    return false;
                                });
                            });
                        </script>
                    <?php echo form_close();?>
                </div>
            </div>
        </div>
<?php
    $this->load->view("root/footer");
?>