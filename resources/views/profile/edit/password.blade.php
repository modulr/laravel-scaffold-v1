@extends('layouts.app')

@section('content')
    <div class="container-fluid profile-edit">
        <div class="row">
            <div class="col-md-12">
                <profile-password-edit :user="{{$user}}"></profile-password-edit>
            </div>
        </div>
    </div>
@endsection
