
@extends('layouts.app')
@section('title')
    E-mail
@endsection
@section('main')
    <h2>Вы должны подтвердить свою почту</h2>
    <a href="{{ route('verification.send') }}">отправить повторно</a>
@endsection
