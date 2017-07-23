@extends('layouts.app')

@section('content')
<div class="auth">
    <div class="left-pane">
        <carousel></carousel>
    </div>
    <div class="right-pane">
        <ul class="nav nav-tabs nav-justified">
            <li class="active"><a href="#signIn" data-toggle="tab"><p class="lead">Sign In</p></a></li>
            <li><a href="{{ route('register') }}"><p class="lead">Sign Up</p></a></li>
        </ul>
        <form method="POST" action="{{ route('login') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required autofocus>
                @if ($errors->has('email'))
                <span class="help-block">
                    {{ $errors->first('email') }}
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control input-lg" name="password" placeholder="Password" required>
                @if ($errors->has('password'))
                <span class="help-block">
                    {{ $errors->first('password') }}
                </span>
                @endif
            </div>

            <div class="form-group">
                <div class="checkbox">
                    <label>  
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">
                Sign In
            </button>

            <a class="btn btn-link btn-block" href="{{ route('password.request') }}">
                Forgot Your Password?
            </a>
        </form>


    </div>
</div>
@endsection
