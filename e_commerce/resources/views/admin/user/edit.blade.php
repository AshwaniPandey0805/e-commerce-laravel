@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Users</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('user.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4" >
                    <!-- Personal Information Card -->
                    <div class="card">
                        <div class="card-header">
                            <h2 class="h5 mb-0 pt-2 pb-2">Personal Information</h2>
                        </div>
                        <form action="" id="user_detail_form" name="user_detail_form">
                            <div class="card-body p-4">
                                <div class="col-md-12 mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" value="{{ $user->name }}" placeholder="Enter Your Name" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" value="{{ $user->email }}" placeholder="Enter Your Email" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" value="{{ $user->phone }}" placeholder="Enter Your Phone" class="form-control">
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="gender">Gender</label>
                                    <select name="gender" id="gender" class="form-control">
                                        <option {{ ($user->gender == 'Male') ? 'selected' : '' }}  value="Male">Male</option>
                                        <option {{ ($user->gender == 'Female') ? 'selected' : '' }}  value="Female">Female</option>
                                        <option {{ ($user->gender == 'Other') ? 'selected' : '' }}  value="Other">Other</option>
                                    </select>
                                    <p></p>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control" >
                                        <option {{ ($user->status == 1) ? 'selected' : '' }}  value="1">Active</option>
                                        <option {{ ($user->status == 0) ? 'selected' : '' }}  value="0">In-Active</option>
                                    </select>
                                    <p></p>
                                </div>
                                <div class="d-flex justify-content-center align-content-center pt-3">
                                    <button type="submit" class="btn btn-dark">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Address Information Card -->
                    @if (isset($userAddress))
                        <div class="card">
                            <div class="card-header">
                                <h2 class="h5 mb-0 pt-2 pb-2">Address Information</h2>
                            </div>
                            <form action="" id="user_address_detail_form" name="user_address_detail_form">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="first_name">First Name</label>
                                            <input type="text" name="first_name" id="first_name" value="{{ $userAddress->first_name }}" placeholder="Enter Your First Name" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="last_name">Last Name</label>
                                            <input type="text" name="last_name" id="last_name" value="{{ $userAddress->last_name }}" placeholder="Enter Your Last Name" class="form-control">
                                        </div>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="email">Email</label>
                                            <input type="text" name="email" id="email" value="{{ $userAddress->email }}" placeholder="Enter Your Email" class="form-control">
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
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="zip_code">Zip Code</label>
                                            <input type="text" name="zip_code" id="zip_code" value="{{ $userAddress->zip_code }}" placeholder="Enter Zip Code" class="form-control">
                                        </div>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="city">City</label>
                                            <input type="text" name="city" id="city" value="{{ $userAddress->city }}" placeholder="Enter Your City" class="form-control">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="state">State</label>
                                            <input type="text" name="state" id="state" value="{{ $userAddress->state }}" placeholder="Enter State" class="form-control">
                                        </div>
                                    </div>
            
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="address">Address</label>
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Enter Address" class="form-control">{{ $userAddress->address }}</textarea>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="apartement">Apartment</label>
                                            <input type="text" name="apartement" id="apartement" value="{{ $userAddress->apartement }}" placeholder="Enter Your Apartment Number" class="form-control">
                                        </div>
                                    </div>
            
                                    <div class="d-flex justify-content-end">
                                        <button type="submit" class="btn btn-dark">Update</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customeJS')
    <script>
        $("#user_detail_form").submit(function(event){
            console.log("sasaasasaa")
            event.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url : "{{route('user.update',$user->id)}}",
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
                        var id = response['id'];
                        window.location.href = "{{ url('/admin/user') }}/" + id + "/edit";
                    }
                },
                error : function ( error ){
                    console.log(error.message);
                }
            });
        });

        $("#user_address_detail_form").submit(function(event){
            event.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url : "{{route('user.address.update',$user->id)}}",
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
                        var id = response['id'];
                        window.location.href = "{{ url('/admin/user') }}/" + id + "/edit";

                    }
                },
                error : function ( error ){
                    console.log(error.message);
                }
            });
        });
    </script>
@endsection