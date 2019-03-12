<?php $__env->startSection('template_title'); ?>
  Showing Posts
<?php $__env->stopSection(); ?>

<?php $__env->startSection('template_linked_css'); ?>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
    <style type="text/css" media="screen">
        .users-table {
            border: 0;
        }
        .users-table tr td:first-child {
            padding-left: 15px;
        }
        .users-table tr td:last-child {
            padding-right: 15px;
        }
        .users-table.table-responsive,
        .users-table.table-responsive table {
            margin-bottom: 0;
        }

    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            Showing All Posts
                            <a href="/users/create" class="btn btn-default btn-sm pull-right">
                                <i class="fa fa-fw fa-user-plus" aria-hidden="true"></i>
                                Create New User
                            </a>
                        </div>
                    </div>

                    <div class="panel-body">

                        <div class="table-responsive users-table">
                            <table class="table table-striped table-condensed data-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Username</th>
                                        <th class="hidden-xs">Email</th>
                                        <th class="hidden-xs">First Name</th>
                                        <th class="hidden-xs">Last Name</th>
                                        <th>Role</th>
                                        <th class="hidden-sm hidden-xs hidden-md">Created</th>
                                        <th class="hidden-sm hidden-xs hidden-md">Updated</th>
                                        <th>Actions</th>
                                        
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($user->id); ?></td>
                                            <td><?php echo e($user->name); ?></td>
                                            <td class="hidden-xs"><a href="mailto:<?php echo e($user->email); ?>" title="email <?php echo e($user->email); ?>"><?php echo e($user->email); ?></a></td>
                                            <td class="hidden-xs"><?php echo e($user->first_name); ?></td>
                                            <td class="hidden-xs"><?php echo e($user->last_name); ?></td>
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
                                            <td class="hidden-sm hidden-xs hidden-md"><?php echo e($user->created_at); ?></td>
                                            <td class="hidden-sm hidden-xs hidden-md"><?php echo e($user->updated_at); ?></td>
                                            <td>
                                                <?php echo Form::open(array('url' => 'users/' . $user->id, 'class' => '', 'data-toggle' => 'tooltip', 'title' => 'Delete')); ?>

                                                    <?php echo Form::hidden('_method', 'DELETE'); ?>

                                                    <?php echo Form::button('<i class="fa fa-trash-o fa-fw" aria-hidden="true"></i> <span class="hidden-xs hidden-sm">Delete</span><span class="hidden-xs hidden-sm hidden-md"> User</span>', array('class' => 'btn btn-danger btn-sm','type' => 'button', 'style' =>'width: 100%;' ,'data-toggle' => 'modal', 'data-target' => '#confirmDelete', 'data-title' => 'Delete User', 'data-message' => 'Are you sure you want to delete this user ?')); ?>

                                                <?php echo Form::close(); ?>

                                            </td>
                                            
                                                
                                                    
                                                
                                            
                                            
                                                
                                                    
                                                
                                            
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php echo $__env->make('modals.modal-delete', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('footer_scripts'); ?>

    <?php if(count($users) > 10): ?>
        <?php echo $__env->make('scripts.datatables', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    <?php echo $__env->make('scripts.delete-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php echo $__env->make('scripts.save-modal-script', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>