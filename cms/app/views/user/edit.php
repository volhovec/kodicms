<form class="form-horizontal" action="<?php echo $action=='edit' ? URL::site('user/edit/'.$user->id): URL::site('user/add'); ?>" method="post">
	
	<?php echo Form::hidden('token', Security::token()); ?>
	
	<div class="row-fluid">
		<div class="well-small well span6">		
			<fieldset>
				<legend><?php echo __('General'); ?></legend>
				<div class="control-group">
					<label class="control-label" for="userEditNameField"><?php echo __('Name'); ?></label>
					<div class="controls">
						<?php echo Form::input('user[name]', $user->name, array(
							'class' => 'input-medium', 'id' => 'userEditNameField'
						)); ?>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="userEditEmailField"><?php echo __('E-mail'); ?></label>
					<div class="controls">
						<?php echo Form::input('user[email]', $user->email, array(
							'class' => 'input-medium', 'id' => 'userEditEmailField'
						)); ?>
						<p class="help-block"><?php echo __('Use a valid e-mail address.'); ?></p>
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="userEditUsernameField"><?php echo __('Username'); ?></label>
					<div class="controls">
						<?php echo Form::input('user[username]', $user->username, array(
							'class' => 'input-medium', 'id' => 'userEditUsernameField'
						)); ?>
						<p class="help-block"><?php echo __('At least :num characters. Must be unique.', array(
							':num' => 3
						)); ?></p>
					</div>
				</div>
			</fieldset>
		</div>
		
		<div class="well-small well span6">
			<fieldset>
				<legend><?php echo __('Password'); ?></legend>
				<div class="control-group">
					<label class="control-label" for="userEditPasswordField"><?php echo __('Password'); ?></label>
					<div class="controls">
						<?php echo Form::password('user[password]', NULL, array(
							'class' => 'input-medium', 'id' => 'userEditPasswordField'
						)); ?>
						<p class="help-block"><?php echo __('At least :num characters. Must be unique.', array(
							':num' => 5
						)); ?> 
						<?php if($action=='edit') echo __('Leave password blank for it to remain unchanged.'); ?></p>
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="userEditPasswordConfirmField"><?php echo __('Confirm Password'); ?></label>
					<div class="controls">
						<?php echo Form::password('user[confirm]', NULL, array(
							'class' => 'input-medium', 'id' => 'userEditPasswordConfirmField'
						)); ?>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	
	<?php if (AuthUser::hasPermission('administrator')): ?>
	<div class="well-small well">
		<fieldset>
			<legend><?php echo __('Roles'); ?></legend>
			<div class="control-group">
				<?php foreach ($permissions as $perm): ?>
				<label class="checkbox inline" for="userEditPerms<?php echo ucwords($perm->name); ?>">
				<?php echo Form::checkbox('user_permission['.$perm->name.']', $perm->id, in_array($perm->id, $user->roles), array(
					'id' => 'userEditPerms' . ucwords($perm->name)
				)) . ' ' .__(ucwords($perm->name)); ?>
				</label>
				<?php endforeach; ?>
				<p class="help-block"><?php echo __('Roles restrict user privileges and turn parts of the administrative interface on or off.'); ?></p>
			</div>
		</fieldset>
	</div>
	<?php endif; ?>
	
	<?php if($user->id !== NULL): ?>
	<div id="UserGravatar">
		<?php echo HTML::anchor('http://gravatar.com/emails/', $user->gravatar(300, NULL, array(
			'class' => 'img-polaroid')), array(
			'target' => '_blank'
		)); ?>
	</div>
	<?php endif; ?>

	<?php Observer::notify('view_user_edit_plugins', array($user)); ?>

	<div class="form-actions">
		<?php echo UI::actions($page_name); ?>
	</div>
</form>