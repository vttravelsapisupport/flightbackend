<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex,nofollow">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - VishalTravels</title>
    <!-- base:css -->
    <link rel="stylesheet" href="{{ asset('assets//vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets//vendors/css/vendor.bundle.base.css') }}">
    @yield('css')
    <link rel="stylesheet" href="{{ asset('assets//css/horizontal-layout-light/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('/images/favicon.ico') }}" />
</head>

<body>
<div class="container-scroller">
    <div class="horizontal-menu">
         @include('partials.horizontal-navbar')
         @include('partials.horizontal-menu')
    </div>
    <div class="container-fluid page-body-wrapper">
        <div class="main-panel">
            <div class="" id="app" style="min-height: calc(100vh - 135px - 75px);">
                @include('partials.notification')
                @yield('contents')
            </div>
            @include('partials.footer')
        </div>
    </div>
</div>

<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/template.js') }}"></script>
@yield('js')
</body>
</html>
