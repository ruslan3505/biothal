@extends('admin.layouts.app')

@section('content')
<button type="button" id="getMailing">getMailing</button>
<button type="button" id="home">home</button>
@endsection

@section('script')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="{{asset('js/test.js')}}"></script>
@endsection
