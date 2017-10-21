@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <news-publish></news-publish>
                <news-list></news-list>
            </div>
        </div>
    </div>
@endsection
