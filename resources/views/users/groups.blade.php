@extends('layouts.app')

@section('content')

        <ol class="breadcrumb">
            <li><a href="users">Users</a></li>
            <li class="active">Groups</li>
        </ol>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <groups></groups>
            </div>
        </div>
    </div>
@endsection
