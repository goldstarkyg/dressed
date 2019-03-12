<?php $__env->startSection('template_title'); ?>
  Editing Style
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
  <style type="text/css">
    .btn-save,
    .pw-change-container {
      display: none;
    }
  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <div class="container">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="panel panel-default">
          <div class="panel-heading">

            <strong>Editing Style:</strong> <?php echo e($style->name); ?>


            <a href="/styles" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              Back <span class="hidden-xs">to</span><span class="hidden-xs"> Styles</span>
            </a>

            <!--<a href="/users/<?php echo e($style->id); ?>" class="btn btn-primary btn-xs pull-right" style="margin-left: 1em;">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
             Back  <span class="hidden-xs">to User</span>
            </a>

            <a href="/users" class="btn btn-info btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              <span class="hidden-xs">Back to </span>Users
            </a>-->

          </div>

          <?php echo Form::model($style, array('action' => array('StylesManagementController@update', $style->id), 'method' => 'PUT')); ?>


            <?php echo csrf_field(); ?>


            <div class="panel-body">

              <div class="form-group has-feedback row <?php echo e($errors->has('name') ? ' has-error ' : ''); ?>">
                <?php echo Form::label('name', 'Style Name' , array('class' => 'col-md-3 control-label')); ?>

                <div class="col-md-9">
                  <div class="input-group">
                    <?php echo Form::text('name', old('name'), array('id' => 'name', 'class' => 'form-control', 'placeholder' => 'style name')); ?>

                    <label class="input-group-addon" for="name"><i class="fa fa-fw fa-gear }}" aria-hidden="true"></i></label>
                  </div>
                </div>
              </div>



            </div>
            <div class="panel-footer">

              <div class="row">

                
                  
                    
                    
                  
                

                <div class="col-xs-12">
                  <?php echo Form::button('<i class="fa fa-fw fa-save" aria-hidden="true"></i> Save Changes', array('class' => 'btn btn-success btn-block margin-bottom-1 btn-save','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmSave', 'data-title' => trans('modals.edit_user__modal_text_confirm_title'), 'data-message' => trans('modals.edit_user__modal_text_confirm_message'))); ?>

                </div>
              </div>
            </div>

          <?php echo Form::close(); ?>


        </div>
      </div>
    </div>
  </div>

  <?php echo $__env->make('modals.modal-save', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

  <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
  <?php echo $__env->make('scripts.check-changed', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>