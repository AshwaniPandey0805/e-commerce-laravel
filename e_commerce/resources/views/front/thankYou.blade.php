@extends('front.layouts.app')
@section('home-content')
    <div class="container" >
        <div class="col-md-12 text-center py-5">
            <h1>Thank You!</h1>
            <p>Your Order ID is : {{ $order->id }}</p>
        </div>
    </div>
@endsection