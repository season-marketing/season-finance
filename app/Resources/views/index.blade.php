@extends('layout')

@section('header')
  @include('modules.header')
@endsection

@section('body')
  <main>
    @include('modules.home')
    @include('modules.services')
    @include('modules.about')
    @include('modules.contact')
  </main>
@endsection

@section('footer')
  @include('modules.footer')
@endsection
