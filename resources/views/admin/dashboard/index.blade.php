@extends('admin.layouts.app')

@section('content')
    <div class="p-3">
        <div class="page-header" style="justify-content: center; display: flex">
            <h2>Добро Пожаловать, {{ auth()->user()->name }}!</h2>
        </div>
    </div>
@endsection
