@extends('layouts.app')
@section('title')
 Profile
@endsection
@section('main')
    {{ $user->name }}
@endsection


