<?php $__env->startSection('template_title'); ?>
  Showing User <?php echo e($user->name); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
  <style type="text/css" media="screen">
    .user-table {
        border: 0;
    }
    .user-table tr th {
        border: 0 !important;
    }
    .user-table tr th:first-child,
    .user-table tr td:first-child {
        padding-left: 15px;
    }
    .user-table tr th:last-child,
    .user-table tr td:last-child {
        padding-right: 15px;
    }
    .user-table .table-responsive,
    .user-table .table-responsive table {
        margin-bottom: 0;
        border-top: 0;
        border-left: 0;
        border-right: 0;
    }
  </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

  <div class="container">

    <div class="row">
      <div class="col-md-12">

        <div class="panel panel-default">
          <div class="panel-heading">

            <?php echo e($user->name); ?>'s Information

            <a href="/users/" class="btn btn-primary btn-xs pull-right">
              <i class="fa fa-fw fa-mail-reply" aria-hidden="true"></i>
              Back to Users
            </a>

          </div>
          <div class="panel-body no-padding user-table table-responsive">
            <table class="table table-borderless">
                <thead>
                    <tr>
                      <th>Id</th>
                      <th>Username</th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>Email</th>
                      <th>Role</th>
                      <th>Created</th>
                      <th>Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="vertical-align: middle;">
                            <?php echo e($user->id); ?>

                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo e($user->name); ?>

                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo e($user->first_name); ?>

                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo e($user->last_name); ?>

                        </td>
                        <td style="vertical-align: middle;">
                          <a href="mailto:<?php echo e($user->email); ?>" title="email <?php echo e($user->email); ?>"><?php echo e($user->email); ?></a>
                        </td>
                        <td>
                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user_role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                <?php if($user_role->name == 'User'): ?>
                                    <?php  $labelClass = 'primary'  ?>

                                <?php elseif($user_role->name == 'Admin'): ?>
                                    <?php  $labelClass = 'warning'  ?>

                                <?php elseif($user_role->name == 'Unverified'): ?>
                                    <?php  $labelClass = 'danger'  ?>

                                <?php else: ?>
                                    <?php  $labelClass = 'default'  ?>

                                <?php endif; ?>

                                <span class="label label-<?php echo e($labelClass); ?>"><?php echo e($user_role->name); ?></span>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo e($user->created_at); ?>

                        </td>
                        <td style="vertical-align: middle;">
                            <?php echo e($user->updated_at); ?>

                        </td>
                    </tr>
                </tbody>
            </table>
          </div>
          <div class="panel-footer">
            <div class="row">
              <div class="col-xs-6">
                <a href="/users/<?php echo e($user->id); ?>/edit" class="btn btn-small btn-info btn-block">
                  <i class="fa fa-pencil fa-fw" aria-hidden="true"></i> Edit<span class="hidden-xs hidden-sm"> this</span><span class="hidden-xs"> User</span>
                </a>
              </div>
              <?php echo Form::open(array('url' => 'users/' . $user->id, 'class' => 'col-xs-6')); ?>

                <?php echo Form::hidden('_method', 'DELETE'); ?>

                <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> Delete<span class="hidden-xs hidden-sm"> this</span><span class="hidden-xs"> User</span>', array('class' => 'btn btn-danger btn-block btn-flat','type' => 'button', 'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User', 'data-message' => 'Are you sure you want to delete this user ?')); ?>

              <?php echo Form::close(); ?>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

  <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>