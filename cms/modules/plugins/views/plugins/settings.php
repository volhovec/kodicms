<div id="settings">
	<?php echo Form::open(NULL, array('class' => 'form-horizontal')); ?>
	
	<?php echo Form::hidden('token', Security::token()); ?>
	
	<?php echo $content; ?>

	<div class="form-actions">
		<?php echo UI::actions('plugins'); ?>
	</div>
	<?php echo Form::close(); ?>
</div>