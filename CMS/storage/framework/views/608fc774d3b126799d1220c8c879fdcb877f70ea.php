<?php $__env->startSection('template_title'); ?>
	Edit your profile
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="panel panel-default">
					<div class="panel-heading">
						<?php echo e(trans('profile.editProfileTitle')); ?>

					</div>
					<div class="panel-body">

						<?php if($user->profile): ?>
							<?php if(Auth::user()->id == $user->id): ?>

								<div class="row">
									<div class="col-sm-12">
										<div id="avatar_container">
											<div class="collapseOne panel-collapse collapse <?php if($user->profile->avatar_status == 0): ?> in <?php endif; ?>">
												<div class="panel-body">
													<img src="<?php echo e(Gravatar::get($user->email)); ?>" alt="<?php echo e($user->name); ?>" class="user-avatar">
												</div>
											</div>
											<div class="collapseTwo panel-collapse collapse <?php if($user->profile->avatar_status == 1): ?> in <?php endif; ?>">
												<div class="panel-body">

													<div class="dz-preview"></div>

													<?php echo Form::open(array('route' => 'avatar.upload', 'method' => 'POST', 'name' => 'avatarDropzone','id' => 'avatarDropzone', 'class' => 'form single-dropzone dropzone single', 'files' => true)); ?>


														<img id="user_selected_avatar" class="user-avatar" src="<?php if($user->profile->avatar != NULL): ?> <?php echo e($user->profile->avatar); ?> <?php endif; ?>" alt="<?php echo e($user->name); ?>">

													<?php echo Form::close(); ?>


												</div>
											</div>
										</div>
									</div>
								</div>

								<?php echo Form::model($user->profile, ['method' => 'PATCH', 'route' => ['profile.update', $user->name],  'class' => 'form-horizontal', 'role' => 'form', 'enctype' => 'multipart/form-data'  ]); ?>


									<?php echo e(csrf_field()); ?>


									<div class="row">
										<div class="col-sm-5 col-sm-offset-4 margin-bottom-1">
											<div class="row" data-toggle="buttons">
												<div class="col-xs-6 right-btn-container">
													<label class="btn btn-primary <?php if($user->profile->avatar_status == 0): ?> active <?php endif; ?> btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne:not(.in), .collapseTwo.in">
														<input type="radio" name="avatar_status" id="option1" autocomplete="off" value="0" <?php if($user->profile->avatar_status == 0): ?> checked <?php endif; ?>> Use Gravatar
													</label>
												</div>
												<div class="col-xs-6 left-btn-container">
													<label class="btn btn-primary <?php if($user->profile->avatar_status == 1): ?> active <?php endif; ?> btn-block btn-sm" data-toggle="collapse" data-target=".collapseOne.in, .collapseTwo:not(.in)">
														<input type="radio" name="avatar_status" id="option2" autocomplete="off" value="1" <?php if($user->profile->avatar_status == 1): ?> checked <?php endif; ?>> Use My Image
													</label>
												</div>
											</div>
										</div>
									</div>


									<div class="form-group has-feedback <?php echo e($errors->has('theme') ? ' has-error ' : ''); ?>">
										<?php echo Form::label('theme', trans('profile.label-theme') , array('class' => 'col-sm-4 control-label'));; ?>

										<div class="col-sm-6">

											<select class="form-control" name="theme_id" id="theme_id">
												<?php if($themes->count()): ?>
													<?php $__currentLoopData = $themes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $theme): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													  <option value="<?php echo e($theme->id); ?>"<?php echo e($currentTheme->id == $theme->id ? 'selected="selected"' : ''); ?>><?php echo e($theme->name); ?></option>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php endif; ?>
											</select>

											<span class="glyphicon <?php echo e($errors->has('theme') ? ' glyphicon-asterisk ' : ' '); ?> form-control-feedback" aria-hidden="true"></span>

									        <?php if($errors->has('theme')): ?>
									            <span class="help-block">
									                <strong><?php echo e($errors->first('theme')); ?></strong>
									            </span>
									        <?php endif; ?>

										</div>
									</div>

									<div class="form-group has-feedback <?php echo e($errors->has('location') ? ' has-error ' : ''); ?>">
										<?php echo Form::label('location', trans('profile.label-location') , array('class' => 'col-sm-4 control-label'));; ?>

										<div class="col-sm-6">
											<?php echo Form::text('location', old('location'), array('id' => 'location', 'class' => 'form-control', 'placeholder' => trans('profile.ph-location'))); ?>

											<span class="glyphicon <?php echo e($errors->has('location') ? ' glyphicon-asterisk ' : ' glyphicon-pencil '); ?> form-control-feedback" aria-hidden="true"></span>
									        <?php if($errors->has('location')): ?>
									            <span class="help-block">
									                <strong><?php echo e($errors->first('location')); ?></strong>
									            </span>
									        <?php endif; ?>
										</div>
									</div>

									<div class="form-group has-feedback <?php echo e($errors->has('bio') ? ' has-error ' : ''); ?>">
										<?php echo Form::label('bio', trans('profile.label-bio') , array('class' => 'col-sm-4 control-label'));; ?>

										<div class="col-sm-6">
											<?php echo Form::textarea('bio', old('bio'), array('id' => 'bio', 'class' => 'form-control', 'placeholder' => trans('profile.ph-bio'))); ?>

											<span class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
									        <?php if($errors->has('bio')): ?>
									            <span class="help-block">
									                <strong><?php echo e($errors->first('bio')); ?></strong>
									            </span>
									        <?php endif; ?>
										</div>
									</div>

									<div class="form-group has-feedback <?php echo e($errors->has('twitter_username') ? ' has-error ' : ''); ?>">
										<?php echo Form::label('twitter_username', trans('profile.label-twitter_username') , array('class' => 'col-sm-4 control-label'));; ?>

										<div class="col-sm-6">
											<?php echo Form::text('twitter_username', old('twitter_username'), array('id' => 'twitter_username', 'class' => 'form-control', 'placeholder' => trans('profile.ph-twitter_username'))); ?>

											<span class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
									        <?php if($errors->has('twitter_username')): ?>
									            <span class="help-block">
									                <strong><?php echo e($errors->first('twitter_username')); ?></strong>
									            </span>
									        <?php endif; ?>
										</div>
									</div>

									<div class="form-group has-feedback <?php echo e($errors->has('github_username') ? ' has-error ' : ''); ?>">
										<?php echo Form::label('github_username', trans('profile.label-github_username') , array('class' => 'col-sm-4 control-label'));; ?>

										<div class="col-sm-6">
											<?php echo Form::text('github_username', old('github_username'), array('id' => 'github_username', 'class' => 'form-control', 'placeholder' => trans('profile.ph-twitter_username'))); ?>

											<span class="glyphicon glyphicon-pencil form-control-feedback" aria-hidden="true"></span>
									        <?php if($errors->has('github_username')): ?>
									            <span class="help-block">
									                <strong><?php echo e($errors->first('github_username')); ?></strong>
									            </span>
									        <?php endif; ?>
										</div>
									</div>

									<div class="form-group">
										<div class="col-sm-6 col-sm-offset-4">
											<?php echo Form::button(trans('profile.submitButton'), array('class' => 'btn btn-primary','type' => 'submit')); ?>

										</div>
									</div>
								<?php echo Form::close(); ?>

							<?php else: ?>
								<p><?php echo e(trans('profile.notYourProfile')); ?></p>
							<?php endif; ?>
						<?php else: ?>

							<p><?php echo e(trans('profile.noProfileYet')); ?></p>

						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

	<?php echo $__env->make('scripts.gmaps-address-lookup-api3', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
	<?php echo $__env->make('scripts.user-avatar-dz', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>