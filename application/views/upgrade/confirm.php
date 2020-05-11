<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
?>


<div>
	<h1>Welcome to upgrade dashboard version</h1>

	<div>
		<p><?php echo dashboard_lang('_DASHBOARD_VERSION_UPGRADE_CONFIRM'); ?></p>		

		<p><?php echo dashboard_lang('_DASHBOARD_VERSION_UPGRADE_RESPONSIBLE'); ?></p>
		
		<p><b><?php echo dashboard_lang('_DASHBOARD_VERSION_UPGRADE_BACKUPS'); ?></b></p>
		
		<p><?php ECHO dashboard_lang('_DASHBOARD_VERSION_UPGRADE_CLICK'); ?></p>

		<a href="<?php echo $url; ?>">
			<button
				type="button" class="btn btn-primary">
				<?php echo dashboard_lang('_DASHBOARD_VERSION_UPGRADE'); ?>
			</button>
		</a>
		
	</div>
	
</div>
