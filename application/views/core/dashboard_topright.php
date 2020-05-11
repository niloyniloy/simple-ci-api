<?php

if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );
?>
<li class="dropdown pull-right"><a class="dropdown-toggle" data-toggle="dropdown"
	href="#"> <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
</a>
	<ul class="dropdown-menu dropdown-user">
		<li><a href="<?php echo base_url().'dashboard/home';?>"><i
				class="fa fa-user fa-fw"></i> <?php echo dashboard_lang('_DASHBOARD_TEMPLATE_HOME')?></a>
		</li>
		<li><a
			href="<?php echo base_url().'dashboard/change_password';?>"><i
				class="fa fa-user fa-fw"></i> <?php echo dashboard_lang('_DASHBOARD_TEMPLATE_CHANGE_PASSWORD')?></a>
		</li>
		<li><a href="<?php echo base_url().'dashboard/logout';?>"><i
				class="fa fa-sign-out fa-fw"></i>
			<?php echo dashboard_lang('_DASHBOARD_TEMPLATE_LOGOUT')?> </a></li>
	</ul> 
</li>
