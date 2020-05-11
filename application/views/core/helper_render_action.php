<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<div class="pull-right">
	<?php foreach($additional_buttons as $button ) { ?>
	<button type="button" class="btn btn-primary <?php echo @$button->class; ?>" <?php echo isset($button->id)?'id="'.$button->id.'"':''; ?> >
		<?php echo dashboard_lang('_DASHBOARD_LISTING_'. strtoupper(@$button->name) ); ?>
	</button>
	<?php } ?>
	<a href="<?php  echo $site_url.'/'.$edit_path; ?>">
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
</div>
