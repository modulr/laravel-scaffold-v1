@extends('layouts.app')

@section('content')
    <h2><i class="fa fa-folder-o" aria-hidden="true"></i> {{$breadcrumb}}</h2>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <files></files>
        </div>
    </div>
@endsection
