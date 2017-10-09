@extends('layouts.app')

@section('content')
    <div class="container-fluid dashboard">
        <div class="row">
            <div class="col-md-6">
                <opportunities-widget></opportunities-widget>
            </div>
            <div class="col-md-6">
                <quotes-widget></quotes-widget>
            </div>
          </div>
        <div class="row">
            <div class="col-md-12">
                <projects-widget></projects-widget>
            </div>
        </div>
    </div>
@endsection
