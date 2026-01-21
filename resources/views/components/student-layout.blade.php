<?php $attributes = $attributes ?? new \Illuminate\View\ComponentAttributeBag; ?>
@extends('layouts.student')
@section('content')
{{ $slot }}
@endsection