<?php echo Form::open(NULL, array('class' => 'form-horizontal')); ?>
	<?php echo Form::hidden('token', Security::token()); ?>
	<div class="well">
	
		<div class="control-group">
			<label class="control-label title" for="filename"><?php echo __('File name'); ?></label>
			<div class="controls">
				<?php echo Form::input('file[name]', $filesystem->getFilename(), array(
					'class' => 'focus input-title', 'id' => 'filename',
					'tabindex'	=> 1
				)); ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="filetype"><?php echo __('File type'); ?></label>
			<div class="controls">
				<?php echo UI::field($filesystem->getMime()); ?>
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="fileurl"><?php echo __('File url'); ?></label>
			<div class="controls">
				<?php echo UI::field($filesystem->getUrl(), 'span12'); ?>
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="filecreated"><?php echo __('Create date'); ?></label>
			<div class="controls">
				<?php echo UI::field(Date::format($filesystem->getCTime()), 'span12'); ?>
			</div>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
		<?php if($filesystem->isImage()): ?>
		<?php echo HTML::image(PUBLIC_URL . $filesystem->getRelativePath(), array(
			 'class' => 'img-polaroid'
		)) ;?>
		<?php else: ?>
		<?php echo Form::textarea('file[content]', $content, array(
			'id' => 'highlight_content', 'class' => 'span12', 'rows' => 200
		)); ?>
		<?php endif; ?>
		</div>
	</div>

	<div class="form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
<?php echo Form::close(); ?>