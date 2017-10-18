@extends('layouts.app')

@section('content')
<div class="auth">
    <div class="left-pane">
        <carousel></carousel>
    </div>
    <div class="right-pane">
        <ul class="nav nav-tabs nav-justified">
            <li><a href="{{ route('login') }}"><p class="lead">Sign In</p></a></li>
            <li class="active"><a href="#signUp" data-toggle="tab"><p class="lead">Sign Up</p></a></li>
        </ul>
        <form method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Name" required autofocus>

                @if ($errors->has('name'))
                <span class="help-block">
                    {{ $errors->first('name') }}
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="E-Mail Address" required>

                @if ($errors->has('email'))
                <span class="help-block">
                    {{ $errors->first('email') }}
                </span>
                @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input id="password" type="password" class="form-control" name="password" placeholder="Password" required>

                @if ($errors->has('password'))
                <span class="help-block">
                    {{ $errors->first('password') }}
                </span>
                @endif
            </div>

            <div class="form-group">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password" required>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    SIGN UP
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
