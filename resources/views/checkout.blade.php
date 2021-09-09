@extends('layouts.app')

@section('content')
    @include('layouts.navCheckout')

    <div class="main-body-container">
        <div class="container">

            <div class="checkout-container">
                @include('partials.checkout')
            </div>
        </div>
    </div>
    @include('layouts.footer')
@endsection
