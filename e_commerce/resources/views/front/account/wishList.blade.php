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
                        <h2 class="h5 mb-0 pt-2 pb-2">Wish List Products</h2>
                    </div>
                    <div class="card-body p-4">
                        @if (isset($wishListProducts))
                            @foreach ($wishListProducts as $product)
                                <div class="d-sm-flex justify-content-between mt-lg-4 mb-4 pb-3 pb-sm-2 border-bottom">
                                    <div class="d-block d-sm-flex align-items-start text-center text-sm-start">
                                        <a class="d-block flex-shrink-0 mx-auto me-sm-4" href="#" style="width: 10rem;">
                                            @if (count($product->getWishListProducts->product_images) > 0)
                                                <img src="{{ asset('uploads/products/small/'.$product->getWishListProducts->product_images[0]->image) }}" alt="Product">
                                            @else
                                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="Product">
                                            @endif
                                        </a>
                                        <div class="pt-2">
                                            <h3 class="product-title fs-base mb-2">
                                                <a href="shop-single-v1.html">{{ $product->getWishListProducts->title }}</a>
                                            </h3>
                                            <div class="fs-lg text-accent pt-2">
                                                ${{ number_format($product->getWishListProducts->price, 2) }}
                                            </div>
                                            @if ($product->getWishListProducts->qty > 0)
                                                <span class="badge bg-success mt-2">In Stock</span>
                                            @else
                                                <span class="badge bg-danger mt-2">Out of Stock</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="pt-2 ps-sm-3 mx-auto mx-sm-0 text-center">
                                        <button class="btn btn-outline-danger btn-sm" onclick="deleteWishListProduct({{ $product->product_id }})" type="button"><i class="fas fa-trash-alt me-2"></i>Remove</button>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="mt-4">
                                <h1 class="text-center">Wish List Empty</h1>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection