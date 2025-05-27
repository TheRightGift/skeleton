@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <dashboard-component :initial-user="{{ json_encode($user) }}"></dashboard-component>
        </div>
    </div>
</div>
@endsection
