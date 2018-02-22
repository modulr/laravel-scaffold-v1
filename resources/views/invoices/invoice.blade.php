@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <invoice :invoice="{{$invoice}}"></invoice>
            </div>
        </div>
    </div>
@endsection
