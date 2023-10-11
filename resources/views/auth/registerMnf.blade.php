@extends('admin.admin_master')

@section('admin')
<!DOCTYPE html>
<html lang="en">
<head>
  <head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="Sleek Dashboard - Free Bootstrap 4 Admin Dashboard Template and UI Kit. It is very powerful bootstrap admin dashboard, which allows you to build products like admin panels, content management systems and CRMs etc.">


  <title>Login</title>

  <!-- GOOGLE FONTS -->
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500|Poppins:400,500,600,700|Roboto:400,500" rel="stylesheet" />
  <link href="https://cdn.materialdesignicons.com/4.4.95/css/materialdesignicons.min.css" rel="stylesheet" />


  <!-- PLUGINS CSS STYLE -->
  <link href="{{ asset('backend/assets/plugins/nprogress/nprogress.css') }}" rel="stylesheet" />

  

  <!-- SLEEK CSS -->
  <link id="sleek-css" rel="stylesheet" href="{{ asset('backend/assets/css/sleek.css') }}" />

  <!-- FAVICON -->
  <link href="{{ asset('backend/assets/img/favicon.png') }}" rel="shortcut icon" />

  

  <!--
    HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries
  -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <script src="{{ asset('backend/assets/plugins/nprogress/nprogress.js') }}"></script>
</head>

<div class="card card-default">
    <div class="card-header card-header-border-bottom">
        <h2>Register a Manufacturer</h2>
    </div>
    <div class="card-body">
        <form class="form-pill" method="POST" action="{{ url('/registration/manufacturer/submit') }}">
            @csrf
            <div class="form-group">
                <label for="name">name</label>
                <input id="name" class="form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" class="form-control" type="text" name="email" :value="old('email')" required autofocus autocomplete="email" />
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>
                
            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-jet-label for="terms">
                        <div class="flex items-center">
                            <x-jet-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-jet-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <button type="submit" class="btn btn-primary btn-default">
                    Register
                </button>
            </div>
        </form>
    </div>
</div>
@endsection