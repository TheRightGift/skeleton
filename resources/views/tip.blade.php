
@extends('layouts.app')
@section('title', 'Tippaz - Tip')
@section('content')
    <tip-component :user="{{ json_encode($user) }}" :tipping_url="{{ json_encode($tipping_url) }}" :key="{{ $key }}"></tip-component>
@endsection
