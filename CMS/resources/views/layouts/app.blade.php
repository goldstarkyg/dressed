<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow, noydir, noodp, nosnippet, noimageindex">
    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {!! trans('titles.app') !!}</title>
    <meta name="description" content="">
    <meta name="author" content="Dressd">
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- facebook applinks --}}
    <meta property="al:ios:url" content="grabble://applinks" />
    <meta property="al:ios:app_store_id" content="12345" />
    <meta property="al:ios:app_name" content="Dress'd" />
    <meta property="og:title" content="example page title" />
    <meta property="og:type" content="website" />

    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Fonts --}}
    @yield('template_linked_fonts')

    {{-- Styles --}}

    @yield('template_linked_css')

    <style type="text/css">
        @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
            background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;
            background-size: auto 100%;
        }
        @endif

    </style>

    {{-- Scripts --}}
    <script>
        window.Laravel ={!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/mainadmin.css" rel="stylesheet">

    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/js/inspinia.js"></script>
    <script src="/js/plugins/pace/pace.min.js"></script>

    @yield('head')

</head>
<body class="top-navigation">

<div id="wrapper">
    <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
            @include('partials.nav')
        </div>
        <div class="wrapper wrapper-content">
            @include('partials.form-status')
            @yield('content')
        </div>
        <div class="footer">
            <div class="pull-right">

            </div>
            <div>
                <strong>Copyright</strong> Dressd &copy; {{ date('Y') }}
            </div>
        </div>
    </div>
</div>
{{-- Scripts --}}

@yield('footer_scripts')

</body>
</html>