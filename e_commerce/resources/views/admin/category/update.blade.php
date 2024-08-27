
@extends('layouts.app')
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">					
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Update Category</h1>
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
            <form action="" method="post" id="categoryForm" name="categoryForm">
                <div class="card">
                    <div class="card-body">								
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" value="{{ $category->name }}" id="name" class="form-control" placeholder="Name">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="slug">Slug</label>
                                    <input type="text" readonly name="slug"  id="slug" value="{{ $category->slug }}" class="form-control" placeholder="Slug">
                                    <p></p>	
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                        <input type="hidden" id="image_id" name="image_id" >
                                        <label for="image">Image</label>
                                        <div id="image" class="dropzone dz-clickable">
                                            <div class="dz-message needsclick">    
                                                <br>Drop files here or click to upload.<br><br>                                            
                                            </div>
                                        </div>
                                </div>
                                @if (!empty($category->image))
                                    <div class="d-flex flex-column align-items-center justify-content-center my-3">
                                        <div class="card shadow-lg" style="width: 150px;">
                                            <img 
                                                src="{{ asset('uploads/category/thumb/'.$category->image) }}" 
                                                alt="category-image" 
                                                class="card-img-top rounded-lg img-fluid"
                                                style="height: 150px; object-fit: cover;"
                                            >
                                            
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            									
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Activate Slug</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $category->status == 1 ? 'selected' : '' }}  value="1">Active</option>
                                        <option {{ $category->status == 0 ? 'selected' : '' }}  value="0">Block</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="show_home">Show On Home</label>
                                    <select name="show_home" id="show_home" class="form-control">
                                        <option {{ $category->show_home == 'Yes' ? 'selected' : '' }}  value="Yes"> Yes </option>
                                        <option {{ $category->show_home == 'No' ? 'selected' : ''  }}  value="No">  No  </option>
                                    </select>
                                </div>
                            </div>									
                        </div>
                    </div>							
                </div>
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </form>
        </div>
        <!-- /.card -->
    </section>
@endsection

@section('customeJS')
    <script>
        $('#categoryForm').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        console.log('clicked');

        $.ajax({
            url: "{{ route('categories.update', $category->id) }}",  // Corrected with quotes
            type: 'PUT',
            data: element.serialize(),  // Using serialize() for form data
            dataType: 'json',  // Corrected dataType
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include CSRF token
            },
            success: function(response) {
                console.log(response);
                if( response['status'] == true ){

                    window.location.href = "{{ route('category.index')}}"
                    
                    $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                    $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                } else {
                    console.log("ddsdsdsd")
                    if(response['notFound'] ==  true){

                        window.location.href = "{{ route('category.index')}}"
                    }
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

                    if( errors['slug'] ) {

                        $('#slug').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['slug']);
                    } else {
                        $('#slug').removeClass('is-invalid')
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