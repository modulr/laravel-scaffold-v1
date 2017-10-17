@extends('layouts.app')

@section('content')
    <div class="container-fluid profile-edit">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <profile-work-edit :user="{{$user}}"></profile-work-edit>
            </div>
        </div>
    </div>
@endsection
