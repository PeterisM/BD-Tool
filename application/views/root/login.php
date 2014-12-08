<?php
	$this->load->view("root/header");
?>
        <div class="content sign">
            <div class="input-box">
                <div class="title">
                    <h3>Pievienoties</h3>
                </div>
				<script type="text/javascript" src="<?php echo base_url();?>public/js/sha1.js" ></script>
                <div class="form">
                    <p id="error"></p>
                    <?php echo form_open('authorize', 'id="form"'); ?>
                        <label for="user_name">Lietotājvārds: <span style="color:#bbb; font-weight:normal">viesis</span></label>
                        <input type="text" name="user_name" value="<?php echo set_value('user_name'); ?>" id="user_name" maxlength="30"/>
                        <span class="error" id="user_name_error"></span>
                        <label for="password" class="password">Parole: <span style="color:#bbb; font-weight:normal">parole</span></label>
                        <input type="password" name="password" id="password" />
                        <span class="error" id="password_error"></span>
                        <div class="clear"></div>
                        <button class="button" type="submit" value="Ieiet" id="submit"><span><span>Ieiet</span></span></button>
                        <div class="clear"></div>
                        <script type="application/javascript">
                            $j(document).ready(function() {
                                var fade_in = 400;
                                var required = 'must';
                                $j('#submit').click(function() {
                                    var pass = '';
                                    if($j('#password').val() != '') pass = SHA1($j('#password').val());
                                    var form_data = {
                                        user_name : $j('#user_name').val(),
                                        password : pass
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
                                            if (jdata.user_name){$j("#user_name_error").html(jdata.user_name).fadeIn(fade_in).css('display', 'block'); $j("#user_name").addClass(required); $j("#password").val('');} 
                                            else{$j("#user_name").removeClass(required);}
                                            
                                            if(jdata.password){$j("#password_error").html(jdata.password).fadeIn(fade_in).css('display', 'block'); $j("#password").addClass(required);} 
                                            else{$j("#password").removeClass(required);}
                                            
                                            if(jdata.error){$j("#error").html(jdata.error).addClass('error').fadeIn(fade_in); $j("#password").val('');} 
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