<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title><?php echo $this->config->item('site_title'); ?></title>

    <!-- Bootstrap Core CSS  -->
    <link href="<?php echo base_url()?>dashboardmedia/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="<?php echo base_url()?>dashboardmedia/css/sb-admin-2.css" rel="stylesheet">
    
    <script
	src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"
	type="text/javascript"></script>

</head>

<body>

    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Password Reset</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" id="new_password" placeholder="New Password" name="new_password" type="password" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" id="re_password" placeholder="Re-Password" name="re_password" type="password" required value="">
                                </div>
<!--                                 <div class="checkbox"> -->
<!--                                     <label> -->
<!--                                         <input name="remember" type="checkbox" value="Remember Me">Remember Me -->
<!--                                     </label> -->
<!--                                 </div>                             -->
                                <?php if($this->session->flashdata("login_error")):?>
                                	<div class="alert alert-danger" role="alert">
                                		<?php echo $this->session->flashdata("login_error");?>
                                	</div>
                                <?php endif; ?>	                               
								<button	type="submit" name="reset_submit" id="reset_submit" value="1" class="btn btn-lg btn-success btn-block">
									<?php echo dashboard_lang('_RESET_PASS_SUBMIT'); ?>
								</button>     
								
                                                     
                            </fieldset>
                        </form>
                        		
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>

</html>

<script type="text/javascript">

$(document).ready(function(){

	$(document).on("click","#reset_submit",function(){
		var pass1 = $("#new_password").val();
		var pass2 = $("#re_password").val();

		if(pass1 != pass2){
			  alert("<?php echo dashboard_lang('_RE_PASS_NOT_MATCH'); ?>");
			  return false;
		}
		
	});
	
});

</script>
