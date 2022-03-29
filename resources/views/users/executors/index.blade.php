@extends('layouts.app')
@section('title')
    Profile
@endsection
@section('main')
   Исполнитель: {{ $user->name }}
@endsection

