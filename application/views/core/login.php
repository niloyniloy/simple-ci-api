<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">

    <title><?php echo $this->config->item('site_title'); ?></title>

    <!-- Bootstrap Core CSS  -->
    <link href="<?php echo CDN_URL;?>dashboardmedia/css/bootstrap.min.css" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="<?php echo CDN_URL;?>dashboardmedia/css/sb-admin-2.css" rel="stylesheet">
    
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
                        <h3 class="panel-title">Please Sign In</h3>
                    </div>
                    <div class="panel-body">
                        <form role="form" method="post" action="">
                            <fieldset>
                                <div class="form-group">
                                    <input class="form-control" id="email" placeholder="E-mail" name="email" type="email" required autofocus>
                                </div>
                                <div class="form-group">
                                    <input class="form-control" placeholder="Password" id="password" name="password" type="password" required value="">
                                </div>
                              <div class="checkbox" > 
                                <label> 
                                  <input name="remember" id="checkbox" type="checkbox" value="Remember Me">Remember Me 
                                 </label> 

                              </div>  
                             
                              
                                                        
                                <?php if($this->session->flashdata("login_error")):?>
                                	<div class="alert alert-danger" role="alert">
                                		<?php echo $this->session->flashdata("login_error");?>
                                	</div>
                                <?php endif; ?>	                               
								<button	type="submit" name="login_btn" id="login_btn" value="1" class="btn btn-lg btn-success btn-block">
									<?php echo dashboard_lang('_DASHBOARD_LOGIN'); ?>
								</button>   
								<span><?php echo dashboard_lang('_SIGNUP_TEXT'); ?> 

							    </span> 
                                                     
                            </fieldset>
                        </form>
                        <form role="form" id="pass_reset_form" method="post" action="<?php echo CDN_URL."dashboard/reset_password"; ?>">
                            <input type="hidden" id="reset_email" name="reset_email" value="" />
                            
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

	$(document).on("click","#pass_reset",function(){
		

        $("#password").parent().addClass('hide');
        $("#checkbox").parent().parent().addClass('hide');
        $(this).parent().addClass('hide');
        $(".panel-title").text('Reset Password');
        $('#login_btn').text("<?php echo dashboard_lang('_RESET_PASS_SUBMIT'); ?>");
        $('#login_btn').attr("type","button");
        $('#login_btn').attr("id","pass_reset_button");
        
       return false;
        
		
	});

	$(document).on("click","#pass_reset_button",function(){
		
		var email = $("#email").val();
		if(email){
	    $("#reset_email").val(email);
	    $("#pass_reset_form").submit();
		}else{
		    alert("<?php echo dashboard_lang('_EMAIL_INVALID'); ?>");
		}
        
	});
	
});

</script>
