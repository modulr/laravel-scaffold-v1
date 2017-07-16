@extends('layouts.app')

@section('content')
<div class="auth">
    <div class="left-pane">
        <carousel></carousel>
    </div>
    <div class="right-pane">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#"><p class="lead">Reset Password</p></a></li>
        </ul>

        <p>Please introduce your email in order to recover your password. </p>
        <br>

        <form method="POST" action="{{ route('password.email') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required>

                @if ($errors->has('email'))
                <span class="help-block">
                    {{ $errors->first('email') }}
                </span>
                @endif
            </div>

            <button type="submit" class="btn btn-warning btn-block">
                Send Password Reset Link
            </button>

            <a class="btn btn-link btn-block" href="{{ route('login') }}">
                Sing In
            </a>

        </form>

        @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif
    </div>
</div>
@endsection
