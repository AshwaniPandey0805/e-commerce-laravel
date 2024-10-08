@extends('front.layouts.app')
@section('home-content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                @if (Auth::check())
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('account.profile') }}">My Account</a></li>    
                @else
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.login') }}">My Account</a></li>
                @endif
                
                <li class="breadcrumb-item">Settings</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-11 ">
    <div class="container  mt-5">
        <div class="row">
            <div class="col-md-3">
                @include('front.account.common.sidebar')
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h2 class="h5 mb-0 pt-2 pb-2">My Orders</h2>
                    </div>

                    <div class="card-body pb-0">
                        <!-- Info -->
                        <div class="card card-sm">
                            <div class="card-body bg-light mb-3">
                                <div class="row">
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Order No:</h6>
                                        <!-- Text -->
                                        <p class="mb-lg-0 fs-sm fw-bold">
                                        {{ $order->id }}
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Shipped date:</h6>
                                        <!-- Text -->
                                        <p class="mb-lg-0 fs-sm fw-bold">
                                            <time datetime="2019-10-01">
                                                01 Oct, 2019
                                            </time>
                                        </p>
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Status:</h6>
                                        <!-- Text -->
                                        @if ($order->status == 'shipped')
                                            <p class="mb-0 fs-sm fw-bold">
                                                Shipped
                                            </p>
                                            {{-- <span class="badge" style="background-color: #007bff;">Shipped</span>     --}}
                                        @endif
                                        @if ($order->status == 'delivered')
                                            <p class="mb-0 fs-sm fw-bold">
                                                Delivered
                                            </p>
                                            {{-- <span class="badge" style="background-color: #28a745;">Delivered</span>     --}}
                                        @endif
                                        @if ($order->status == 'pending')
                                            <p class="mb-0 fs-sm fw-bold">
                                                Pending
                                            </p>
                                            {{-- <span class="badge" style="background-color: #dc3545;">Pending</span>     --}}
                                        @endif
                                        
                                    </div>
                                    <div class="col-6 col-lg-3">
                                        <!-- Heading -->
                                        <h6 class="heading-xxxs text-muted">Order Amount:</h6>
                                        <!-- Text -->
                                        <p class="mb-0 fs-sm fw-bold">
                                            {{ number_format($order->grand_total, 2) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer p-3">

                        <!-- Heading -->
                        <h6 class="mb-7 h5 mt-4">Order Items (3)</h6>

                        <!-- Divider -->
                        <hr class="my-3">

                        <!-- List group -->
                        <ul>
                            @if (isset($orderItems))
                                @foreach ($orderItems as $item)
                                    <li class="list-group-item">
                                        <div class="row align-items-center">
                                            <div class="col-4 col-md-3 col-xl-2">
                                                <!-- Image -->
                                                <a href="product.html"><img src="{{ asset('uploads/products/small/'.$item->products->product_images[0]->image) }}" alt="..." class="img-fluid"></a>
                                            </div>
                                            <div class="col">
                                                <!-- Title -->
                                                <p class="mb-4 fs-sm fw-bold">
                                                    <a class="text-body" href="product.html">{{ $item->name }} x {{ $item->qty }}</a> <br>
                                                    <span class="text-muted">${{ number_format(($item->price * $item->qty),2) }}</span>
                                                </p>
                                            </div>
                                        </div>
                                    </li>        
                                @endforeach
                            @endif
                            
                            
                        </ul>
                    </div>                      
                </div>
                
                <div class="card card-lg mb-5 mt-3">
                    <div class="card-body">
                        <!-- Heading -->
                        <h6 class="mt-0 mb-3 h5">Order Total</h6>

                        <!-- List group -->
                        <ul>
                            <li class="list-group-item d-flex">
                                <span>Subtotal</span>
                                <span class="ms-auto">${{ number_format($order->subtotal,2) }}</span>
                            </li>
                            <li class="list-group-item d-flex">
                                @if (isset($order->coupon_code))
                                    <span>Discount Applied [ {{ $order->coupon_code }} ] </span>
                                    <span class="ms-auto">${{ number_format($order->discount,2) }}</span>    
                                @else
                                    <span>Discount </span>
                                    <span class="ms-auto">$0.00</span>
                                @endif
                            </li>
                            <li class="list-group-item d-flex">
                                <span>Shipping</span>
                                <span class="ms-auto">${{ number_format($order->shipping,2) }}</span>
                            </li>
                            <li class="list-group-item d-flex fs-lg fw-bold">
                                <span>Total</span>
                                <span class="ms-auto">${{ number_format($order->grand_total,2) }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection