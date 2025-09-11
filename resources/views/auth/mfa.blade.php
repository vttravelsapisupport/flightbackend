@extends('layouts.auth')
@section('title','Login')
@section('css')
    <script src="https://www.google.com/recaptcha/api.js"
            async defer></script>
@endsection
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

                            <form class="pt-3" action="{{ route('mfa-verification') }}" method="POST">
                                @csrf
                               @include('partials/notification')
                                <h4>OTP Verification</h4>
                                <div class="form-group">

                                    <div class="input-group">
                                        <div class="input-group-prepend bg-transparent">
                                          <span class="input-group-text bg-transparent border-right-0">
                                            <i class="mdi mdi-account-outline text-primary"></i>
                                          </span>
                                        </div>
                                        <input type="text" class="form-control form-control-lg border-left-0" id="exampleInputEmail"  value="{{ old('otp') }}"placeholder="Enter the OTP" name="otp" required>
                                    </div>
                                </div>
                                <div class="g-recaptcha" id="feedback-recaptcha"
                                     data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}">
                                </div>


                                <div class="my-3">
                                    <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >Verify OTP</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 login-half-bg d-flex flex-row">
                        <p class="text-white font-weight-medium text-center flex-grow align-self-end">Copyright &copy; 2018  All rights reserved.</p>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
@endsection
@section('js')
    <script src="https://www.google.com/recaptcha/api.js"
            async defer></script>
@endsection
