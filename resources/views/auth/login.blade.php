@extends('layouts.app')

@section('content')
<div class="auth">
    <div class="row">
        <div class="left-pane hidden-xs col-sm-6 col-md-9">
            <carousel></carousel>
        </div>
        <div class="right-pane col-xs-12 col-sm-6 col-md-3">
            <div class="logo text-center">
                <img src="/img/logos/stb.png">
            </div>
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
                <p class="text-center">or</p>
                <a class="btn btn-default btn-block" href="{{route('register')}}">
                    Sign Up?
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
