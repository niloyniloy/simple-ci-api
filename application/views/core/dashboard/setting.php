<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<form role="form" name="change_pass" id="change_pass" method="post" action="">
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 pull-right">
		<h1 class="page-header">		
			<?php echo dashboard_lang('_DASHBOARD_SETTINGS_CHANGE_PASSWORD') ?>
		</h1>
	</div>
	<!-- /.col-lg-12 -->
</div><!--End Heading row -->


<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="button_row">        	
            <button type="submit" class="btn btn-primary"><?php echo dashboard_lang('_CORE_EDIT_SAVE_BUTTON');?></button>                  
        </div>
	</div>
	<!-- /.col-lg-12 -->
</div><!--End button row -->

<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">

			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12">

							<div class="row">
								<div class="col-lg-6">
			
                                <?php if($this->session->flashdata("change_password_message")):?>
                                	<div class="alert alert-info" role="alert">
                                		<?php echo $this->session->flashdata("change_password_message");?>
                                	</div>
                                <?php endif; ?>
                                                            
                                <?php if($this->session->flashdata("change_password_error")):?>
                                	<div class="alert alert-danger" role="alert">
                                		<?php echo $this->session->flashdata("change_password_error");?>
                                	</div>
                                <?php endif; ?>	                               
                                
									<div class="form-group">
										 <?php echo dashboard_lang('PASSWORD_CHANGE_EMAIL') . $data['email']; ?>
									</div>
									<div class="form-group">
										<?php echo dashboard_lang('PASSWORD_CHANGE_CURRENT_PASSWORD'); ?> <input type="password" value="" class="form-control" name="current_password" id="current_password" required /> 
									</div>
									<div class="form-group">
										<?php echo dashboard_lang('PASSWORD_CHANGE_NEW_PASSWORD'); ?> <input type="password" class="form-control" name="new_password" id="new_password" required />
									</div>
									<div class="form-group">
										<?php echo dashboard_lang('PASSWORD_CHANGE_RETYPE_PASSWORD'); ?> <input type="password" class="form-control" name="retype_password" id="retype_password" oninput="check(this)" required />
									</div>									
									<?php
									/*
									foreach($data_load->field as $value){
										
											$valueType = (string) $value["type"];
											$nameField = (string) $value["name"];
											
											echo '<div class="form-group">';

												if(! in_array($nameField, $edit_field_array)){
													echo boo2_render_span($value, @$data_edit[(string)$value["name"]]);	
												}else{
													echo boo2_render_form($value, @$data_edit[(string)$value["name"]]);
												}				 							
				 							
				 							echo "</div>";

									}
									*/
									?>
								</div>
							</div>						
					
					</div>

				</div>
				<!-- /.row (nested) -->
			</div>
			<!-- /.panel-body -->
		</div>
		<!-- /.panel -->
	</div>
	<!-- /.col-lg-12 -->
</div>
</form>
<script>
var passMatchAlert = "<?php echo dashboard_lang('TWO_PASSWORD_MUST_MATCH')?>";					
function check(input) {
    if (input.value != document.getElementById('new_password').value) {
        input.setCustomValidity(passMatchAlert);
    } else {
        // input is valid -- reset the error message
        input.setCustomValidity('');
   }
}
</script>
