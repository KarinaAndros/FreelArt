@extends('layouts.app')
@section('title')
    Profile
@endsection
@section('main')
    Админ: {{ $user->name }}
@endsection

