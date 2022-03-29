@extends('layouts.app')
@section('title')
    Profile
@endsection
@section('main')
   Заказчик: {{ $user->name }}
@endsection

