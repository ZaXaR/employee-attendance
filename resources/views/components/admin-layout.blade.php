@extends('layouts.admin')

@section('title')
    {{ $title ?? 'Admin Panel' }}
@endsection

@section('header')
    {{ $header ?? '' }}
@endsection

@section('content')
    {{ $slot }}
@endsection
