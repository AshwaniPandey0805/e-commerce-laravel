@extends('front.layouts.app')
@section('home-content')
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="#">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="container">
        <form action="#" name="checkoutForm" id="checkoutForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="sub-title">
                        <h2>Shipping Address</h2>
                    </div>
                    <div class="card shadow-lg border-0">
                        <div class="card-body checkout-form">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" @if ( $customerAddress != null ) value="{{ $customerAddress->first_name  }}" @endif    name="first_name" id="first_name" class="form-control" placeholder="First Name">
                                        <p ></p>
                                    </div>            
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" @if ( $customerAddress != null ) value="{{ $customerAddress->last_name  }}" @endif name="last_name" id="last_name" class="form-control" placeholder="Last Name">
                                        <p ></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="email" id="email" @if ( $customerAddress != null ) value="{{ $customerAddress->email  }}" @endif class="form-control" placeholder="Email">
                                        <p ></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <select name="country" id="country" class="form-control">
                                            @if($countries->isNotEmpty())
                                                <option value="">Select a Country</option>
                                                @foreach ($countries as $country)
                                                    <option @if ( $customerAddress != null )  {{ ($customerAddress->country_id == $country->id ? 'selected' : '') }} @endif  value="{{ $country->id }}">{{ $country->name}}</option>        
                                                @endforeach
                                                
                                            @endif
                                        </select>
                                        <p ></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="address" id="address"  cols="30" rows="3" placeholder="Address" class="form-control">@if ( $customerAddress != null ) {{ $customerAddress->address  }} @endif</textarea>
                                        <p ></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="appartment" @if ( $customerAddress != null ) value="{{ $customerAddress->apartement  }}" @endif id="appartment" class="form-control" placeholder="Apartment, suite, unit, etc. (optional)">
                                        <p ></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="city" id="city" @if ( $customerAddress != null ) value="{{ $customerAddress->city  }}" @endif class="form-control" placeholder="City">
                                        <p ></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="state" id="state" @if ( $customerAddress != null ) value="{{ $customerAddress->state  }}" @endif class="form-control" placeholder="State">
                                        <p ></p>
                                    </div>            
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <input type="text" name="zip" id="zip" @if ( $customerAddress != null ) value="{{ $customerAddress->zip_code }}" @endif class="form-control" placeholder="Zip">
                                        <p ></p>
                                    </div>            
                                </div>
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <input type="text" name="mobile" id="mobile" @if ( $customerAddress != null ) value="{{ $customerAddress->mobile  }}" @endif class="form-control" placeholder="Mobile No.">
                                        <p ></p>
                                    </div>            
                                </div>
                                
    
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control"></textarea>
                                    </div>            
                                </div>
    
                            </div>
                        </div>
                    </div>    
                </div>
                <div class="col-md-4">
                    <div class="sub-title">
                        <h2>Order Summery</h3>
                    </div>                    
                    <div class="card cart-summery">
                        <div class="card-body">
                            @if (!empty(Cart::content()))
                                @foreach (Cart::content() as $item)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                                        <div class="h6">${{ ($item->price * $item->qty)  }}</div>
                                    </div>        
                                @endforeach
                            @endif
                            <div class="d-flex justify-content-between summery-end">
                                <div class="h6"><strong>Subtotal</strong></div>
                                @if ($subTotal >= 0.0)
                                    <div class="h6"><strong id="total_amount" >$ {{ number_format($subTotal, 2, '.', ',') }} </strong></div>    
                                @else
                                    <div class="h6"><strong id="total_amount" >$ {{ Cart::subtotal() }} </strong></div>
                                @endif
                            </div>
                            <div class="d-flex justify-content-between mt-2">
                                <div class="h6"><strong>Shipping</strong></div>
                                <div class="h6"><strong id="shipping_amount" >$ {{ number_format($shippingCharge, 2, '.', ',') }}</strong></div>
                            </div>
                            <div class="d-flex justify-content-between mt-2 summery-end">
                                <div class="h5"><strong>Total</strong></div>
                                <div class="h5"><strong id="sub_total_amount" >$ {{ number_format($subTotalAmount, 2, '.', ',') }}</strong></div>
                            </div>                            
                        </div>
                    </div>      
                    <div class="input-group apply-coupan mt-4">
                        <input type="text" id="applied-coupon" name="applied_coupon" @if (session()->has('coupon_code')) value="{{ session()->get('coupon_code') }}"
                        @endif placeholder="Coupon Code" class="form-control">
                        @if (session()->has('coupon_code'))
                            <button disabled  class="btn btn-dark"  type="button" id="button-addon2">Apply Coupon</button>    
                        @else
                            <button  class="btn btn-dark"  type="button" id="button-addon2">Apply Coupon</button>
                        @endif
                    </div> 
                    <div class="mt-4" >
                        <p id="coupon-error" ></p>
                    </div >
                    <div id="applied-coupon-list" >
                        {!! $html !!}
                    </div>   
                        
                    
                    
                    
                    <div class="card payment-form ">    
                        <h3 class="card-title h5 mb-3">Payment Method</h3>
                        <div class="">
                            <input type="radio" name="payment_method" value="cod" id="payment_method_one">
                            <label for="payment_method_one" class="form-check-label" >COD</label>
                        </div>
                        <div class="">
                            <input type="radio" name="payment_method" value="cod" id="payment_method_two">
                            <label for="payment_method_two" class="form-check-label" >Stripe</label>
                        </div>
    
                        {{-- <h3 class="card-title h5 mb-3 mt-3 ">Payment Details</h3> --}}
                        <div class="card-body p-0 mt-3  d-none" id="card_payment_from">
                            <div class="mb-3">
                                <label for="card_number" class="mb-2">Card Number</label>
                                <input type="text" name="card_number" id="card_number" placeholder="Valid Card Number" class="form-control">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">Expiry Date</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YYYY" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label for="expiry_date" class="mb-2">CVV Code</label>
                                    <input type="text" name="expiry_date" id="expiry_date" placeholder="123" class="form-control">
                                </div>
                            </div>
                        </div>                        
                        <div class="pt-4">
                            {{-- <a href="#" class="btn-dark btn btn-block w-100">Pay Now</a> --}}
                            <button type="submit" class="btn-dark btn btn-block w-100">Pay Now</button>
                        </div>
                    </div>
    
                          
                    <!-- CREDIT CARD FORM ENDS HERE -->
                    
                </div>
            </div>
        </form>
    </div>
</section>
@endsection
@section('customJs')
    <script>
        $("#payment_method_one").click(function(){
            if($(this).is(":checked") == true){
                $("#card_payment_from").addClass('d-none');
            }
        });
        $("#payment_method_two").click(function(){
            if($(this).is(":checked") == true){
                $("#card_payment_from").removeClass('d-none');
            }
        });

        $("#button-addon2").click(function(){
            var couponCode = $("#applied-coupon").val();
            var countryId = $("#country").val();
            $.ajax({
                url : "{{ route('front.applyDiscountCoupun') }}",
                type : 'post',
                data : { coupon_code : couponCode , country_id : countryId },
                dataType : 'json',
                success : function ( response ){
                    var errors = response['errors'];
                    if(response['status'] ==  false){
                        if(errors['applied-coupon']){
                            console.log(errors['applied-coupon'])
                            $("#applied-coupon").addClass('is-invalid');
                            $("#coupon-error").addClass('text-danger').html(errors['applied-coupon'])
                            
                        } else {
                            $("#applied-coupon").removeClass('is-invalid');
                            $("#coupon-error").removeClass('text-danger').html(errors['applied-coupon'])
                        }
                    } else {

                        $("#total_amount").html("$ "+response['amount_after_discount']);
                        $("#shipping_amount").html("$ "+response['shipping_amount']);
                        $("#sub_total_amount").html("$ "+response['sub_total_amount'])
                        if(response['html']){
                            $("#applied-coupon-list").html(response['html']);    
                        } else {
                            $("#applied-coupon-list").html('');
                        }
                        
                        // Reload the current page
                        
                        
                    }
                },
                error : function ( error ){
                    console.log('error message', error.message);
                }
            })
        });

         
        $("#remove_coupon").click(function(event){
            event.preventDefault();
            $.ajax({
                    url : "{{ route('front.removeDiscountCoupun') }}",
                    type : 'get',
                    data : {
                        'country_code' : $("#country").val()
                    },
                    dataType : 'json',
                    success : function ( response ){
                        if(response['status'] == true){
                            window.location.reload();
                        }
                    },
                    error : function ( error ){
                        console.log('errors', error.message);
                    }
                });
            });
       

       $("#country").change(function(){
           console.log('clicked');
           var countryId = $(this).val(); // getting country id
           $.ajax({
               url : "{{route('front.calculateShippingCharge')}}",
               type : 'get',
               data : { country_id : countryId },
               dataType : 'json',
               success : function ( response ){
                   // console.log(response);
                   var data = response['data'];
                   var subTotalAmount = data['subTotalAmount'];
                   var shippingCharge = data['shippingCharge'];
                   var subTotal = data['subTotal'];
                   

                   $("#shipping_amount").html("$ "+shippingCharge)
                   $("#sub_total_amount").html("$ "+subTotalAmount)
                   $("#total_amount").html("$ "+subTotal)
                   if(response['html']){
                        $("#applied-coupon-list").html(response['html']);    
                    } else {
                        $("#applied-coupon-list").html('');
                    }
                   
                   
               },
               error : function ( error ){
                   console.log(error.message());
                   
               }
           })
           
       })

        $("#checkoutForm").submit(function( event ){
            event.preventDefault();
            $("button[type='submit']").prop('disabled', true);
            var formArray = $(this).serializeArray(); 
            $.ajax({
                url : "{{ route('front.checkoutProcess')  }}",
                type : "POST",
                data : formArray,
                dataType : 'json',
                success : function ( response ){
                    var errors = response['errors'];
                    if(response['status'] == false){
                        $("button[type='submit']").prop('disabled', false);
                        if(errors['first_name']){
                            $('#first_name').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['first_name'])
                        } else {
                            $('#first_name').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }

                        if(errors['last_name']){
                            $('#last_name').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['last_name'])
                        } else {
                            $('#last_name').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }

                        if(errors['email']){
                            $('#email').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['email'])
                        } else {
                            $('#email').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }

                        if(errors['country']){
                            $('#country').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['country'])
                        } else {
                            $('#country').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }

                        if(errors['address']){
                            $('#address').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['address'])
                        } else {
                            $('#address').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }

                        if(errors['appartment']){
                            $('#appartment').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['appartment'])
                        } else {
                            $('#appartment').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }
                        if(errors['city']){
                        $('#city').addClass('is-invalid')
                        .siblings('p').addClass('invalid-feedback')
                            .html(errors['city'])
                        } else {
                            $('#city').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }
                        if(errors['state']){c
                            $('#state').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['state'])
                        } else {
                            $('#state').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }
                        if(errors['zip']){
                            $('#zip').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['zip'])
                        } else {
                            $('#zip').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }

                        if(errors['mobile']){
                            $('#mobile').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['mobile'])
                        } else {
                            $('#mobile').removeClass('is-invalid')
                            .siblings('p').removeClass('invalid-feedback')
                            .html('')
                        }

                        
                    } else {
                        // $("#button-addon2").prop('disabled', false);
                        $("button[type='submit']").prop('disabled', false);
                        window.location.href = "{{ url('/thank') }}/"+response.order_id;
                    }

                },
                errro : function ( error ){
                    console.log(error);
                }
            });
        });


    </script>
@endsection