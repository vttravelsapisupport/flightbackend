@extends('layouts.auth')
@section('title','Login')

@section('content')
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-stretch auth auth-img-bg">
                <div class="row flex-grow">
                    <div class="col-lg-6 d-flex align-items-center justify-content-center">
                        <div class="auth-form-transparent text-left p-3">
                            <div class="brand-logo">
                                <img src="{{asset('images/logo.png')}}" alt="logo">
                            </div>
                            <h4>Welcome back!</h4>
                            <h6 class="font-weight-light">Happy to see you again!</h6>
                            <form class="pt-3" action="{{ url('/login') }}" method="POST">
                                @csrf
                               @include('partials/notification')
                                <div class="form-group">

                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                          <span class="input-group-text bg-transparent border-right-0">
                                            <i class="mdi mdi-account-outline text-primary"></i>
                                          </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg border-left-0" id="exampleInputEmail"  value="{{ old('email') }}"placeholder="Phone / Email" name="email" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group">

                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                          <span class="input-group-text bg-transparent border-right-0">
                                            <i class="mdi mdi-lock-outline text-primary"></i>
                                          </span>
                                        </div>
                                        <input type="password" class="form-control form-control-lg border-left-0" id="exampleInputPassword" placeholder="Password" value="{{ old('password') }}" name="password" autocomplete="off" required>
                                    </div>
                                </div>

                                <div class="my-3">
                                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >LOGIN</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 login-half-bg d-flex flex-row">
                        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; {{ \Carbon\Carbon::now()->format('Y') }}  All rights reserved to KRETEQ</p>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>

@endsection
