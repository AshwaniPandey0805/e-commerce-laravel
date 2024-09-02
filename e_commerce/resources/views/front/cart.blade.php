@extends('front.layouts.app')
@section('home-content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.index') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('shop.index') }}">Shop</a></li>
                <li class="breadcrumb-item">Cart</li>
            </ol>
        </div>
    </div>
</section>
@if (Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>{{ Session::get('success') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>    
@endif
@if (Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>{{ Session::get('error') }}</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>    
@endif

@if (Cart::count() > 0)
<section class=" section-9 pt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="table-responsive">
                
                    <table class="table" id="cart">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($cartContents->isNotEmpty())
                                @foreach ($cartContents as $content)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if (!empty($content->options->productImage->image))
                                                    {{-- @dd('ehi gaya h',$content->options->productImage) --}}
                                                    <img src="{{ asset('uploads/products/small/'.$content->options->productImage->image) }}" width="300" height="300">
                                                @else
                                                    {{-- @dd('ehi gaya assas',$content->options->productImage)     --}}
                                                    <img src="{{ asset('admin-assets/img/default-150x150.png') }}" width="" height="">
                                                @endif
                                                
                                                <h2>{{ $content->name }}</h2>
                                            </div>
                                        </td>
                                        <td>${{ $content->price }}</td>
                                        <td>
                                            <div class="input-group quantity mx-auto" style="width: 100px;">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub " data-id={{ $content->rowId }} >
                                                        <i class="fa fa-minus"></i>
                                                    </button>
                                                </div>
                                                <input type="text" value="{{ $content->qty }}"  class="form-control form-control-sm  border-0 text-center" value="1">
                                                <div class="input-group-btn">
                                                    <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add " data-id="{{ $content->rowId }}" >
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            ${{ ( $content->qty * $content->price ) }}
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-danger" onclick="deleteCart('{{ $content->rowId }}');" ><i class="fa fa-times"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                
                </div>
            </div>
            <div class="col-md-4">            
                <div class="card cart-summery">
                    <div class="sub-title">
                        <h2 class="bg-white">Cart Summery</h3>
                    </div> 
                    <div class="card-body">
                        <div class="d-flex justify-content-between pb-2">
                            <div>Subtotal</div>
                            <div>${{ $cartSubTotal }}</div>
                        </div>
                        {{-- <div class="d-flex justify-content-between pb-2">
                            <div>Shipping</div>
                            <div>$20</div>
                        </div> --}}
                        <div class="d-flex justify-content-between summery-end">
                            <div>Total</div>
                            <div>${{ $cartSubTotal }} </div>
                        </div>
                        <div class="pt-5">
                            <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>     
            </div>
        </div>
    </div>
</section>
@else
    <div class=" flex d-flex justify-content-center align-content-center text-red" >
        <h2>Cart is Empty</h2>
    </div>
@endif
    
@endsection
@section('customJs')
    <script>
        $('.add').click(function(){
            var qtyElement = $(this).parent().prev(); // Qty Input
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue < 10) {
                qtyElement.val(qtyValue+1);
                
                var rowId = $(this).data('id');
                var qty = qtyElement.val();
                updateCardQuantity(rowId, qty);
            }            
        });

        $('.sub').click(function(){
            var qtyElement = $(this).parent().next(); 
            var qtyValue = parseInt(qtyElement.val());
            if (qtyValue > 1) {
                qtyElement.val(qtyValue-1);
                
                var rowId = $(this).data('id');
                var qty = qtyElement.val();
                
                updateCardQuantity(rowId, qty);
            }        
        });

        function updateCardQuantity(rowId, qty){
            $.ajax({
                url : "{{ route('front.updateCart') }}",
                type: 'POST',
                data : {rowId : rowId , qty : qty},
                dataType : 'json',
                success : function ( response ){
                    window.location.href = "{{ route('front.cart') }}"
                },
                error : function ( error ) {
                    console.log("Something went wrong");
                }
            })
        }

        function deleteCart(rowId){
            $.ajax({
                url : "{{ route('front.deleteCart') }}",
                type : 'post',
                data : { rowId : rowId},
                dataType : 'json',
                success : function ( response ){
                    if(response['status'] ==  true){
                        window.location.href = "{{ route('front.cart') }}"
                    }
                },
                error : function ( error ){
                    console.log(error);
                }
            })
        }
    </script>
@endsection