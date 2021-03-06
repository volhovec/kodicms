<h1><?php echo __( 'Installation' ); ?></h1>


<form action="<?php echo URL::site( 'install/go' ); ?>" class="form-horizontal" method="post">
	<div class="well" id="install-page" >
		<fieldset>
			<legend><?php echo __( 'Database information' ); ?></legend>

			<br />
			<?php echo Form::hidden( 'install[db_driver]', Arr::get( $data, 'db_driver' ) ); ?>

			<div class="control-group">
				<label class="control-label" for="installDBServerField"><?php echo __( 'Database server' ); ?></label>
				<div class="controls inline">
					<?php echo Form::input( 'install[db_server]', Arr::get( $data, 'db_server' ), array(
						'class' => 'span3', 'id' => 'installDBServerField'
					) ); ?>
						
					<?php echo Form::input( 'install[db_port]', Arr::get( $data, 'db_port' ), array(
						'class' => 'span1'
					) ); ?>
						
					<?php echo UI::label( __( 'Required.' ) ); ?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installDBUserField"><?php echo __( 'Database user' ); ?></label>
				<div class="controls">
					<?php echo Form::input( 'install[db_user]', Arr::get( $data, 'db_user' ), array(
						'class' => 'input-xlarge', 'id' => 'installDBUserField'
					) ); ?> <?php echo UI::label( __( 'Required.' ) ); ?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installDBPasswordField"><?php echo __( 'Database password' ); ?></label>
				<div class="controls">
					<?php
					echo Form::password( 'install[db_password]', Arr::get( $data, 'db_password' ), array(
						'class' => 'input-xlarge', 'id' => 'installDBPasswordField'
					) );
					?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installDBNameField"><?php echo __( 'Database name' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'install[db_name]', Arr::get( $data, 'db_name' ), array(
						'class' => 'input-xlarge', 'id' => 'installDBNameField'
					) );
					?> <?php echo UI::label( __( 'Required.' ) ); ?>

					<p class="help-block"><?php echo __( 'Required. You have to create a database manually and enter its name here.' ); ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installDBPrefixField"><?php echo __( 'Prefix' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'install[table_prefix]', Arr::get( $data, 'table_prefix' ), array(
						'class' => 'input-small', 'id' => 'installDBPrefixField'
					) );
					?>

					<p class="help-block"><?php echo __( 'Optional. Usefull to prevent conflicts if you have, or plan to have, multiple Flexo installations with a single database.' ); ?></p>
				</div>
			</div>
		</fieldset>
	
	</div>
	<div class="well" id="install-page" >
		<fieldset>
			<legend><?php echo __( 'Other information' ); ?></legend>

			<div class="control-group">
				<label class="control-label" for="installSiteNameField"><?php echo __( 'Site name' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'install[site_name]', Arr::get( $data, 'site_name' ), array(
						'class' => 'span7', 'id' => 'installSiteNameField'
					) );
					?> <?php echo UI::label( __( 'Required.' ) ); ?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installUsernameField"><?php echo __( 'Administrator username' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'install[username]', Arr::get( $data, 'username' ), array(
						'class' => 'input-medium', 'id' => 'installUsernameField'
					) );
					?> <?php echo UI::label( __( 'Required.' ) ); ?>

					<p class="help-block"><?php echo __( 'Required. Allows you to specify a custom username for the administrator. Default: admin' ); ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installEmailField"><?php echo __( 'Administrator email' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'install[email]', Arr::get( $data, 'email' ), array(
						'class' => 'input-medium', 'id' => 'installEmailField'
					) );
					?> <?php echo UI::label( __( 'Required.' ) ); ?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installAdminDirNamexField"><?php echo __( 'Admin dir name' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'install[admin_dir_name]', Arr::get( $data, 'admin_dir_name' ), array(
						'class' => 'input-small', 'id' => 'installAdminDirNamexField'
					) );
					?> <?php echo UI::label( __( 'Required.' ) ); ?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installURLSuffixField"><?php echo __( 'URL suffix' ); ?></label>
				<div class="controls">
					<?php
					echo Form::input( 'install[url_suffix]', Arr::get( $data, 'url_suffix' ), array(
						'class' => 'input-small', 'id' => 'installURLSuffixField'
					) );
					?>

					<p class="help-block"><?php echo __( 'Optional. Add a suffix to simulate static html files.' ); ?></p>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="installTimezoneField"><?php echo __( 'Timezone' ); ?></label>
				<div class="controls">
					<?php
					echo Form::select( 'install[timezone]', Date::timezones(), Arr::get( $data, 'timezone' ), array(
						'class' => 'input-xlarge', 'id' => 'installTimezoneField'
					) );
					?>
				</div>
			</div>
		</fieldset>

	</div>
	<div class="well" id="install-page" >
		<?php echo $env_test; ?>
	</div>

	<div class="form-actions">
		<?php echo UI::button(__( 'Install now!' ), array(
			'class' => 'btn btn-large btn-success', 'icon' => UI::icon( 'ok icon-white' )
		)); ?>
	</div>
</form><!--/#installForm-->