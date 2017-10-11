@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-10 col-lg-offset-1">
                <ol class="breadcrumb">
                    <li><a href="users">Users</a></li>
                    <li class="active">Groups</li>
                </ol>
                <groups></groups>
            </div>
        </div>
    </div>
@endsection
