
@extends('layouts.app')
@section('title', 'Tippaz - Tip')
@section('content')
    <tip-component :user="{{ json_encode($user) }}" :tipping_url="{{ json_encode($tipping_url) }}" :keyering="{{ json_encode($key) }}"></tip-component>
@endsection
