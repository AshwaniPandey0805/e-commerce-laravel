@extends('layouts.app')
@section('content')
    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
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
                    <h1>Update Shipping Price</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="#" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        {{-- @dd("!") --}}
        
        <div class="container-fluid">
            <form action="#" method="post" id="shippingPriceForm" name="shippingPriceForm">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <select name="name" id="name" class="form-control">
                                        @if (!empty($countries))
                                            <option value="">Select Country Name</option>
                                            @foreach ($countries as $country)
                                                <option {{ (intval($shippingCharge->country_id) ==  $country->id) ? 'selected' : '' }}  value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p></p>	
                                </div>
                                
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="mb-3">
                                        <input type="text" value="{{ $shippingCharge->charges }}"  name="amount" id="amount" class="form-control" placeholder="Enter Amount">
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
                                    </div>
                                </div>
                            </div>				
                        </div>
                    </div>							
                </div>
                <div>
                    {{-- <table class="table table-striped">
                        <thead>
                          <tr>
                            <th scope="col">id</th>
                            <th scope="col">Name</th>
                            <th scope="col">Amount</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                            @if (!empty($shippingCharges))
                                @foreach ($shippingCharges as $charge)
                                    <tr>
                                        <th>{{ $charge->id }}</th>
                                        <td>{{ $charge->name }}</td>
                                        <td>${{ $charge->charge }}</td>
                                        <td>
                                            <a href="#">
                                                <svg class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path>
                                                </svg>
                                            </a>
                                            <a href="#" class="text-danger w-4 h-4 mr-1">
                                                <svg wire:loading.remove.delay="" wire:target="" class="filament-link-icon w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path	ath fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>          
                                @endforeach
                            @endif
                          
                          <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Thornton</td>
                            <td>@fat</td>
                          </tr>
                          <tr>
                            <th scope="row">3</th>
                            <td>Larry</td>
                            <td>the Bird</td>
                            <td>@twitter</td>
                          </tr>
                        </tbody>
                      </table> --}}
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection

@section('customeJS')
    <script>
        $('#shippingPriceForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        console.log('clicked');

        $.ajax({
            url: "{{ route('shipping.update', $shippingCharge->id ) }}",  // Corrected with quotes
            type: 'put',
            data: element.serialize(),  // Using serialize() for form data
            dataType: 'json',  // Corrected dataType
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include CSRF token
            },
            success: function(response) {
                console.log(response);
                if( response['status'] == true ){

                    window.location.href = "{{ route('shipping.create')}}"
                    
                    $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                    $('#amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                } else {
                    var errors = response['errors'];
                    if( errors['name'] ) {
                        $('#name').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['name']);
                    } else {
                        $('#name').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html();
                    }

                    if( errors['amount'] ) {
                        $('#amount').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['amount']);
                    } else {
                        $('#amount').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html();
                    }
                } 
                
                console.log('Success:', response);
                // Handle success, maybe redirect or show a success message
            },
            error: function(jqXHR, exception) {
                console.log('Something went wrong', jqXHR.responseText);
                // Handle error, maybe show a message to the user
            }
        });
    });   

    $('#name').change(function() {
        console.log('clicked');
        let element = $(this);
        console.log(element.val())
        $.ajax({
            url: "{{ route('get.slug') }}",  // Ensure this route exists and matches
            type: 'GET',  // Change to 'GET' if the route is a GET request
            data: { title: element.val() },
            dataType: 'json',
            success: function(response) {
                console.log(response);
                if (response['slug']) {
                    $('#slug').val(response['slug']);
                }
            },
            error: function(jqXHR, exception) {
                console.log('Error:', jqXHR.responseText);
            }
        });
    });

    Dropzone.autoDiscover = false;    
    const dropzone = $("#image").dropzone({ 
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) {
                    this.removeFile(this.files[0]);
                }
            });
        },
        url:  "{{ route('categories.image.upload') }}",
        maxFiles: 1,
        paramName: 'image',
        addRemoveLinks: true,
        acceptedFiles: "image/jpeg,image/png,image/gif",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }, success: function(file, response){
            $("#image_id").val(response.image_id);
            //console.log(response)
        }
    });

    
    
    </script>
@endsection