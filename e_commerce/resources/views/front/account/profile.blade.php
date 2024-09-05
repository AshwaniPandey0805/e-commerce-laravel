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
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form action="" id="user_profile_form" name="user_profile_form" >
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="mb-3">               
                                        <label for="name">Name</label>
                                        <input type="text" name="name" id="name" value="{{ $user->name }}"  placeholder="Enter Your Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">            
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" value="{{ $user->email }}" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="mb-3">                                    
                                        <label for="phone">Phone</label>
                                        <input type="text" name="phone" id="phone" value="{{ $user->phone }}"  placeholder="Enter Your Phone" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="d-flex">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card mt-5 ">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Address Information</h2>
                        </div>
                        <form action="" id="user_address_form" name="user_address_form">
                            <div class="card-body p-4">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="first_name">First Name</label>
                                        <input type="text" name="first_name" id="first_name" value="{{ $userAddress->first_name }}" placeholder="Enter Your First Name" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" name="last_name" id="last_name" value="{{ $userAddress->last_name }}" placeholder="Enter Your Last Name" class="form-control">
                                        <p></p>
                                    </div>
                                </div>
                        
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" id="email" value="{{ $userAddress->email }}" placeholder="Enter Your Email" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="mobile">Mobile</label>
                                        <input type="text" name="mobile" id="mobile" value="{{ $userAddress->mobile }}" placeholder="Enter Your Mobile Number" class="form-control">
                                    </div>
                                </div>
                        
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="country_id">Country</label>
                                        <select name="country_id" id="country_id" class="form-control">
                                            @if (isset($countries))
                                                @foreach ($countries as $country)
                                                    <option {{ ($userAddress->country_id == $country->id) ? 'selected' : '' }} value="{{ $country->id }}">{{ $country->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" name="zip_code" id="zip_code" placeholder="Enter Zip Code" value="{{ $userAddress->zip_code }}" class="form-control">
                                        <p></p>
                                    </div>
                                    
                                    
                                </div>
                        
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="city">City</label>
                                        <input type="text" name="city" id="city" value="{{ $userAddress->city }}" placeholder="Enter Your City" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="state">State</label>
                                        <input type="text" name="state" id="state" placeholder="Enter State" value="{{ $userAddress->state }}" class="form-control">
                                        <p></p>
                                    </div>
                                    
                                    
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="apartement">Apartment</label>
                                        <input type="text" name="apartement" id="apartement" value="{{ $userAddress->apartement }}" placeholder="Enter Your Apartment Number" class="form-control">
                                        <p></p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="address">Address</label>
                                        <textarea name="address" id="address" cols="30" rows="3" placeholder="Enter Address" class="form-control">{{ $userAddress->address }}</textarea>
                                        <p></p>
                                    </div>
                                    
                                </div>
                        
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('customJs')
    <script>
        $("#user_profile_form").submit(function(event){
            event.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url : "{{route('account.profile.update',$user->id)}}",
                type : 'POST',
                data : formData,
                dataType : 'json',
                success : function ( response ){
                    console.log(response);
                    if(response['status'] == false){
                        var errors = response['errors'];
                        if(errors['name']){
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-fedback').html(errors['name'])
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-fedback').html('')
                        }
                        if(errors['email']){
                            $("#email").addClass('is-invalid').siblings('p').addClass('invalid-fedback').html(errors['email'])
                        } else {
                            $("#email").removeClass('is-invalid').siblings('p').removeClass('invalid-fedback').html('')
                        }
                        if(errors['phone']){
                            $("#phone").addClass('is-invalid').siblings('p').addClass('invalid-fedback').html(errors['phone'])
                        } else {
                            $("#phone").removeClass('is-invalid').siblings('p').removeClass('invalid-fedback').html('')
                        }
                    } else {
                        window.location.href = "{{route('account.profile')}}"
                    }
                },
                error : function ( error ){
                    console.log(error.message);
                }
            });
        });
        $("#user_address_form").submit(function(event){
            event.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url : "{{route('account.address.update',$user->id)}}",
                type : 'POST',
                data : formData,
                dataType : 'json',
                success : function ( response ){
                    console.log(response);
                    if(response['status'] == false){
                        var errors = response['errors'];
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
                            $('#country_id').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['country_id'])
                        } else {
                            $('#country_id').removeClass('is-invalid')
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

                        if(errors['apartement']){
                            $('#apartement').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['apartement'])
                        } else {
                            $('#apartement').removeClass('is-invalid')
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
                        if(errors['zip_code']){
                            $('#zip_code').addClass('is-invalid')
                            .siblings('p').addClass('invalid-feedback')
                            .html(errors['zip_code'])
                        } else {
                            $('#zip_code').removeClass('is-invalid')
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
                        window.location.href = "{{route('account.profile')}}"
                    }
                },
                error : function ( error ){
                    console.log(error.message);
                }
            });
        });
    </script>
@endsection