@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-fw fa-bullhorn"></i> News
                    </div>
                    <div class="panel-body" style="height:400px; overflow-y:scroll;">
                        <news :user="user"></news>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-fw fa-tasks"></i> Tasks
                    </div>
                    <div class="panel-body" style="height:400px; overflow-y:scroll;">
                        <tasks :user="user"></tasks>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-fw fa-address-book-o"></i> Contacts
                    </div>
                    <div class="panel-body" style="height:500px; overflow-y:scroll;">
                        <contacts></contacts>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
