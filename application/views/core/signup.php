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
<div class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2" style="margin-top: 50px;" id="signupbox">
    <div class="panel panel-info">
        <div class="panel-heading">
            <div class="panel-title">Sign Up</div>
            
        </div>  
        <div class="panel-body">
            <form role="form" method="post" action="" class="form-horizontal" id="signupform">
                
                <div class="form-group">
                    <label class="col-md-3 control-label" for="name">Name</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="Name" name="name" value="<?php echo set_value('name'); ?>" class="form-control">
                    </div>
                     <div class="col-md-9 pull-right text-danger">
                        <?php echo form_error('name'); ?>
                    </div>
                </div>
                  
                <div class="form-group">
                    <label class="col-md-3 control-label" for="email">Email</label>
                    <div class="col-md-9">
                        <input type="text" placeholder="Email Address" name="email" value="<?php echo set_value('email'); ?>" class="form-control">
                    </div>
                    <div class="col-md-9 pull-right text-danger">
                         <?php echo form_error('email'); ?>
                    </div>
                </div>
                    
                
                
                <div class="form-group">
                    <label class="col-md-3 control-label" for="password">Password</label>
                    <div class="col-md-9">
                        <input type="password" placeholder="Password" name="password" value="<?php echo set_value('password'); ?>" class="form-control">
                    </div>
                    <div class="col-md-9 pull-right text-danger">
                        <?php echo form_error('password'); ?>
                    </div>
                </div>
                

                <div class="form-group">
                    <!-- Button -->                                        
                    <div class="col-md-offset-3 col-md-9">
                        <button class="btn btn-info" type="submit" id="btn-signup"><i class="icon-hand-right"></i> &nbsp; Sign Up</button>
                         
                    </div>
                </div>
                
                
                
                
            </form>
         </div>
    </div>

               
               
                
</div>
    
   

</body>

</html>
