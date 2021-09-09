@extends('layouts.app')

@section('content')
    @include('layouts.nav')
    <div class="row">
        <div class="col-sm-5"></div>
        <div class="col-sm-2">
            <img style="margin-top: 40px;" src="{{Storage::url('img/payment.png')}}" width="190"><br><br>
        </div>
        <div class="col-sm-5"></div>
    </div>
    <h1 align="center">Оплата прошла успешно</h1>
@endsection
