@extends('layouts.app')

@section('content')
<div class="auth">
    <div class="left-pane hidden-xs col-sm-6 col-md-9">
        <carousel></carousel>
    </div>
    <div class="right-pane col-xs-12 col-sm-6 col-md-3">
        <div>
            <div class="logo">
                <img src="/img/logos/logo-name.svg">
            </div>

            <p>Please introduce your email in order to recover your password. </p><br>

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
                <button type="submit" class="btn btn-primary btn-block">
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
</div>
@endsection
