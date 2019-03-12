<?php $__env->startSection('template_title'); ?>
  Create New Style
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_fastload_css'); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">

            Create New Style

            <a href="/styles" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              Back <span class="hidden-xs">to</span><span class="hidden-xs"> Styles</span>
            </a>

          </div>
          <div class="panel-body">

            <?php echo Form::open(array('action' => 'StylesManagementController@store', 'method' => 'POST', 'role' => 'form')); ?>


              <?php echo csrf_field(); ?>


              <div class="form-group has-feedback row <?php echo e($errors->has('name') ? ' has-error ' : ''); ?>">
                <?php echo Form::label('name', 'Style Name', array('class' => 'col-md-3 control-label')); ?>

                <div class="col-md-9">
                  <div class="input-group">
                    <?php echo Form::text('name', NULL, array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'style name')); ?>

                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-gear }}" aria-hidden="true"></i></label>
                  </div>
                  <?php if($errors->has('name')): ?>
                    <span class="help-block">
                        <strong><?php echo e($errors->first('name')); ?></strong>
                    </span>
                  <?php endif; ?>
                </div>
              </div>

              <?php echo Form::button('<i class="fa fa-user-plus" aria-hidden="true"></i>&nbsp;' . trans('Create New Style'), array('class' => 'btn btn-success btn-flat margin-bottom-1 pull-right','type' => 'submit', )); ?>


            <?php echo Form::close(); ?>


          </div>
        </div>
      </div>
    </div>
  </div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>