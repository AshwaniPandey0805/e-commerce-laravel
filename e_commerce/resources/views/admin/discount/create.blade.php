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
                    <h1>Create Discount Coupon</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('category.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <form action="" method="post" id="discountCouponForm" name="discountCouponForm">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code">Coupon Code</label>
                                    <input type="text" name="code" id="code" class="form-control" placeholder="Enter Code">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter Name">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses">Max Usage</label>
                                    <input type="number" name="max_uses" id="max_uses" class="form-control" placeholder="Max Usage">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="max_uses_user">Max Usage</label>
                                    <input type="number" name="max_uses_user" id="max_uses_user" class="form-control" placeholder="Max User Usage">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type">Discount Type</label>
                                    <select name="type" id="type" class="form-control" >
                                        <option value="percent">Percentag (%)</option>
                                        <option value="fixed">Fixed (0.0)</option>
                                    </select>
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="discount_amount">Discount Amount</label>
                                    <input type="number" name="discount_amount" id="discount_amount" class="form-control" placeholder="Enter Discount Amount">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="min_amount">Minimum Amount</label>
                                    <input type="number" name="min_amount" id="min_amount" class="form-control" placeholder="Enter Discount Amount">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Coupon Status</label>
                                    <select name="status" id="status" class="form-control" >
                                        <option value="1">Active</option>
                                        <option value="0">In-active</option>
                                    </select>
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start_at">Started At</label>
                                    <input type="text" autocomplete="off" name="start_at" id="start_at" class="form-control" placeholder="Enter Discount Amount">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end_at">End At</label>
                                    <input type="text" autocomplete="off" name="end_at" id="end_at" class="form-control" placeholder="Enter Discount Amount">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                        <input type="hidden"  id="image_id" name="image_id" >
                                        <label for="image">Image</label>
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">    
                                                <br>Drop files here or click to upload.<br><br>                                            
                                            </div>
                                        </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="short_description">Description</label>
                                    <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                </div>
                            </div>								
                            {{-- <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Activate Slug</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="show_home">Show On Home</label>
                                    <select name="show_home" id="show_home" class="form-control">
                                        <option value="Yes">Yes</option>
                                        <option value="No">No</option>
                                    </select>
                                </div>
                            </div>									 --}}
                            									
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection

@section('customeJS')
    <script>

            
        $(document).ready(function(){
            $('#start_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
            $('#end_at').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
        });
			

        $('#discountCouponForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        $("button[type=submit]").prop('disabled', true);
        console.log('clicked');

        $.ajax({
            url: "{{ route('coupon.store') }}",  // Corrected with quotes
            type: 'POST',
            data: element.serialize(),  // Using serialize() for form data
            dataType: 'json',  // Corrected dataType
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include CSRF token
            },
            success: function(response) {
                if( response['status'] == true ){
                    $("button[type=submit]").prop('disabled', false);
                    window.location.href = "{{ route('coupon.index') }}"
                    
                    $('#code').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                    $('#discount_amount').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                } else {

                    $("button[type=submit]").prop('disabled', false);
                    // validation check
                    var errors = response['errors'];
                    if( errors['code'] ) {
                            $('#code').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['code']);
                    } else {
                            $('#code').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html();
                    }

                    if( errors['discount_amount'] ) {
                            $('#discount_amount').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['discount_amount']);
                    } else {
                            $('#discount_amount').removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html();
                    }

                    if(errors['start_at']){
                        $("#start_at").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors['start_at']);
                    } else {
                        $("#start_at").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
                    }
                    if(errors['end_at']){
                        $("#end_at").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors['end_at']);
                    } else {
                        $("#end_at").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html('');
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