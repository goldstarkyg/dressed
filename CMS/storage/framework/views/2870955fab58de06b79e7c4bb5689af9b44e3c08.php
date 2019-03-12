<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only"><?php echo trans('titles.toggleNav'); ?></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <style>
                .navbar-brand:hover {
                    background-color: #91c2e3!important;
                }
            </style>

            
            <a class="navbar-brand" href="<?php echo e(url('/')); ?>" style="padding:0px 15px;">
                <img src="/img/logo_sen.png">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            
            <ul class="nav navbar-nav">
                <?php if (Auth::check() && Auth::user()->hasRole('admin')): ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Users <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo e(Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'class=active' : null); ?>><?php echo HTML::link(url('/users'),'Users'); ?></li>
                            <!--<li <?php echo e(Request::is('users/create') ? 'class=active' : null); ?>><?php echo HTML::link(url('/users/create'), Lang::get('titles.adminNewUser')); ?></li>
                            <li <?php echo e(Request::is('logs') ? 'class=active' : null); ?>><?php echo HTML::link(url('/logs'), Lang::get('titles.adminLogs')); ?></li>
                            <li <?php echo e(Request::is('php') ? 'class=active' : null); ?>><?php echo HTML::link(url('/php'), Lang::get('titles.adminPHP')); ?></li>
                            <li <?php echo e(Request::is('routes') ? 'class=active' : null); ?>><?php echo HTML::link(url('/routes'), Lang::get('titles.adminRoutes')); ?></li>-->
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Posts <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo e(Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'class=active' : null); ?>><?php echo HTML::link(url('/posts'),'Posts'); ?></li>

                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Styles <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo e(Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'class=active' : null); ?>><?php echo HTML::link(url('/styles'),'Styles'); ?></li>

                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Brands <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo e(Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'class=active' : null); ?>><?php echo HTML::link(url('/brands'),'Brands'); ?></li>

                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Reports <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo e(Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'class=active' : null); ?>><?php echo HTML::link(url('/reports'),'Reports'); ?></li>

                        </ul>
                    </li>
                    <!--<li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                            Administrators <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo e(Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'class=active' : null); ?>><?php echo HTML::link(url('/users'), 'Administrators'); ?></li>
                            <li <?php echo e(Request::is('users/create') ? 'class=active' : null); ?>><?php echo HTML::link(url('/users/create'), 'Create Administrators'); ?></li>
                            <li <?php echo e(Request::is('logs') ? 'class=active' : null); ?>><?php echo HTML::link(url('/logs'), Lang::get('titles.adminLogs')); ?></li>
                            <li <?php echo e(Request::is('php') ? 'class=active' : null); ?>><?php echo HTML::link(url('/php'), Lang::get('titles.adminPHP')); ?></li>
                            <li <?php echo e(Request::is('routes') ? 'class=active' : null); ?>><?php echo HTML::link(url('/routes'), Lang::get('titles.adminRoutes')); ?></li>
                        </ul>
                    </li>-->
                <?php endif; ?>
            </ul>

            
            <ul class="nav navbar-nav navbar-right">
                
                <?php if(Auth::guest()): ?>
                    <li><a href="<?php echo e(route('login')); ?>"><?php echo trans('titles.login'); ?></a></li>
                    <li><a href="<?php echo e(route('register')); ?>"><?php echo trans('titles.register'); ?></a></li>
                <?php else: ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">

                            <?php echo e(Auth::user()->name); ?> <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li <?php echo e(Request::is('profile/'.Auth::user()->name, '/users/'.Auth::user()->id . '/edit') ? 'class=active' : null); ?>>
                                <?php echo HTML::link(url( '/users/'.Auth::user()->id . '/edit'), trans('titles.profile')); ?>

                            </li>
                            <li>
                                <a href="<?php echo e(route('logout')); ?>"
                                    onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                    <?php echo trans('titles.logout'); ?>

                                </a>

                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                                    <?php echo e(csrf_field()); ?>

                                </form>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>