@extends('layouts.auth')

@section('content')
<div class="register">
  <div class="content">
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
            <a href="/login"><button class="sign-in inactive">Sign In</button></a>
            <a href="/register"><button class="sign-up active">Sign Up</button></a>
          </div>
        </div>
        <form class="form" method="POST" action="{{ route('register') }}">
            {{ csrf_field() }}

          <div class="input-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <input id="name" type="text" placeholder="Name" name="name" value="{{ old('name') }}" required autofocus>

            @if ($errors->has('name'))
                <span class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </span>
            @endif
          </div>
          <div class="input-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <input id="email" type="email" placeholder="Email" name="email" value="{{ old('email') }}" required>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
          </div>
          <div class="input-group{{ $errors->has('password') ? ' has-error' : '' }}">
            <input id="password" type="password" placeholder="Password" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          </div>
          <div class="input-group">
            <input id="password-confirm" type="password" placeholder="Repeat password" name="password_confirmation" required>
          </div>
          <div class="send-input">
            <button type="submit">Sign Up</button>
          </div>
          <div class="policy">
            <p>By clicking sign up you are agreeing to the <a href="#">terms of use and privacy policy.</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
