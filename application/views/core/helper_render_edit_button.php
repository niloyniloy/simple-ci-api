<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

	<button type="submit" class="btn btn-primary"><?php echo dashboard_lang('_CORE_EDIT_SAVE_BUTTON');?></button>
    <?php if(get_permission_role()):?>
    <?php if(isset($id) && ($id > 0)):?>	
	<a href="<?php  echo $base_url . $edit_path; ?>">
		<button type="button" class="btn btn-primary">
			<?php echo dashboard_lang('_DASHBOARD_LISTING_ADD'); ?>
		</button>
	</a>            
	<button type="button" name="copy" id="copy" class="btn btn-primary">
		<?php echo dashboard_lang('_DASHBOARD_LISTING_COPY'); ?>
	</button>
	<button type="button" name="delete" id="delete" class="btn btn-danger">
		<?php echo dashboard_lang('_DASHBOARD_LISTING_DELETE'); ?>
	</button>
	<?php endif;?>
	<?php endif;?>		
