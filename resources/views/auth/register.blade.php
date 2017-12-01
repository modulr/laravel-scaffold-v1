@extends('layouts.app')

@section('content')
<div class="auth">
    <div class="row">
        <div class="left-pane hidden-xs col-sm-6 col-md-9">
            <carousel></carousel>
        </div>
        <div class="right-pane col-xs-12 col-sm-6 col-md-3">
            <div class="logo">
                <svg viewBox="0 0 218 42">
                    <g>
                        <path fill="#feae3b" d="M40.433,19.072h17.324c0.559,0,1.011-0.452,1.011-1.01v-6.705c0-0.559-0.452-1.011-1.011-1.011H40.433
                            c-0.557,0-1.01,0.452-1.01,1.011v6.705C39.423,18.62,39.876,19.072,40.433,19.072z"/>
                        <path fill="#feae3b" d="M19.102,19.072h17.323c0.559,0,1.01-0.452,1.01-1.01v-6.705c0-0.559-0.451-1.011-1.01-1.011H19.102
                            c-0.559,0-1.011,0.452-1.011,1.011v6.705C18.091,18.62,18.543,19.072,19.102,19.072z"/>
                        <path fill="#feae3b" d="M3.831,19.072h11.418c0.558,0,1.01-0.452,1.01-1.01v-6.705c0-0.559-0.452-1.011-1.01-1.011H3.831
                            c-0.559,0-1.01,0.452-1.01,1.011v6.705C2.821,18.62,3.272,19.072,3.831,19.072z"/>
                        <path fill="#feae3b" d="M57.758,31.232H40.434c-0.558,0-1.011,0.453-1.011,1.011v6.704c0,0.558,0.453,1.01,1.011,1.01h17.324
                            c0.558,0,1.011-0.452,1.011-1.01v-6.704C58.769,31.685,58.315,31.232,57.758,31.232z"/>
                        <path fill="#feae3b" d="M36.425,31.232H19.102c-0.558,0-1.01,0.453-1.01,1.011v6.704c0,0.558,0.452,1.01,1.01,1.01h17.323
                            c0.559,0,1.012-0.452,1.012-1.01v-6.704C37.437,31.685,36.983,31.232,36.425,31.232z"/>
                        <path fill="#feae3b" d="M15.249,31.232H3.831c-0.558,0-1.01,0.453-1.01,1.011v6.704c0,0.558,0.452,1.01,1.01,1.01h11.418
                            c0.558,0,1.01-0.452,1.01-1.01v-6.704C16.259,31.685,15.807,31.232,15.249,31.232z"/>
                        <path fill="#feae3b" d="M21.155,29.556c0.558,0,1.01-0.452,1.01-1.01v-6.705c0-0.558-0.452-1.01-1.01-1.01H3.831
                            c-0.559,0-1.01,0.452-1.01,1.01v6.705c0,0.558,0.451,1.01,1.01,1.01H21.155z"/>
                        <path fill="#feae3b" d="M25.296,20.832c-0.559,0-1.012,0.452-1.012,1.01v6.705c0,0.558,0.453,1.01,1.012,1.01h17.323
                            c0.558,0,1.01-0.452,1.01-1.01v-6.705c0-0.558-0.452-1.01-1.01-1.01H25.296z"/>
                        <path fill="#feae3b" d="M57.757,20.832H46.339c-0.557,0-1.01,0.452-1.01,1.01v6.705c0,0.558,0.453,1.01,1.01,1.01h11.418
                            c0.559,0,1.011-0.452,1.011-1.01v-6.705C58.768,21.284,58.315,20.832,57.757,20.832z"/>
                    </g>
                    <g>
                        <path fill="#414042" d="M71.249,21.136c0-0.809,0.361-1.169,1.17-1.169h4.902v19.77h8.721v-19.77h4.998
                            c0.787,0,1.17,0.382,1.17,1.169v18.601h8.623V18.023c0-4.592-2.279-6.921-6.777-6.921H69.354c-4.528,0-6.825,2.312-6.825,6.873
                            v21.761h8.72V21.136z"/>
                        <path fill="#414042" d="M110.188,39.737h12.303c4.435,0,6.683-2.296,6.683-6.825V18.023c0-4.592-2.281-6.921-6.778-6.921h-12.351
                            c-4.529,0-6.825,2.312-6.825,6.873v15.127C103.219,37.505,105.563,39.737,110.188,39.737z M112.034,29.657v-8.521
                            c0-0.809,0.361-1.169,1.17-1.169h6.08c0.807,0,1.168,0.36,1.168,1.169v8.616c0,0.809-0.361,1.169-1.168,1.169h-6.08
                            C112.405,30.921,112.034,30.52,112.034,29.657z"/>
                        <path fill="#414042" d="M138.383,39.737h19.032V2.821H148.6v8.281h-10.36c-4.528,0-6.825,2.312-6.825,6.873v15.127
                            C131.414,37.505,133.759,39.737,138.383,39.737z M140.229,29.657v-8.521c0-0.809,0.36-1.169,1.17-1.169h7.2v10.955h-7.2
                            C140.601,30.921,140.229,30.52,140.229,29.657z"/>
                        <path fill="#414042" d="M166.914,39.737h11.824c4.433,0,6.681-2.296,6.681-6.825V11.103H176.7v18.649
                            c0,0.809-0.361,1.169-1.17,1.169h-5.6c-0.799,0-1.17-0.402-1.17-1.265V11.103h-8.816v22
                            C159.944,37.505,162.29,39.737,166.914,39.737z"/>
                        <rect x="187.997" y="2.821" fill="#414042" width="8.814" height="36.916"/>
                        <path fill="#414042" d="M206.31,11.103c-4.625,0-6.873,2.249-6.873,6.873v21.761h8.863V21.088c0-0.809,0.361-1.169,1.17-1.169
                            h6.394v-8.816H206.31z"/>
                    </g>
                </svg>
            </div>

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
            <a class="btn btn-link btn-block" href="{{ route('login') }}">
                Sign In?
            </a>
        </div>
    </div>
</div>
@endsection
