@extends('layouts.app')

@section('content')
<div class="login">
  <div class="container-fluid">
    <div class="left-pane">
      <div class="content">
        <div class="logo">
          <img src="img/logos/logowhite.png" alt="">
        </div>
        <div class="content">
          <h3 class="heading">
            Lorem ipsum dolor sit amet, consectetur.
          </h3>
          <p class="text">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque velit sapien, aliquam ac turpis a, varius condimentum risus. Integer eget velit id ex congue euismod.
          </p>
        </div>
      </div>
    </div>
    <div class="right-pane">
      <div class="content">
        <div class="indicator">
          <div class="input">
            <a href="{{ route('login') }}"><button class="sign-in active">Sign In</button></a>
            <a href="{{ route('register') }}"><button class="sign-up inactive">Sign Up</button></a>
          </div>
        </div>
        <form class="form" method="POST" action="{{ route('login') }}">
          {{ csrf_field() }}

          <div class="input-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required autofocus>
          </div>
          <div class="input-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="password" type="password" placeholder="Password" name="password" required>
          </div>
          <div class="input-group{{ $errors->has('email') ? ' has-error' : '' }}">
            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
          </div>
          <div class="remember-me">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> <span>Remember me</span>
          </div>
          <div class="send-input">
            <button type="submit">Sign In</button>
          </div>
          <div class="forgot">
            <p><a href="{{ route('password.request') }}"><small>Forgot password?</small></a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
