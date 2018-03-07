@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <students-edit :id="{{$id}}"></students-edit>
            </div>
          </div>
        </div>
    </div>
@endsection
