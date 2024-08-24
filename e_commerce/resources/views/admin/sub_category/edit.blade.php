
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
               <h1>Edit Sub-Category</h1>
            </div>
            <div class="col-sm-6 text-right">
               <a href="{{ route('sub-category.index') }}" class="btn btn-primary">Back</a>
            </div>
         </div>
      </div>
      <!-- /.container-fluid -->
   </section>
   <!-- Main content -->
   <section class="content">
      <!-- Default box -->
      <div class="container-fluid">
         <form action="" name="subCategory" id="subcategory">
            <div class="card">
               <div class="card-body">								
                  <div class="row">
                              <div class="col-md-12">
                        <div class="mb-3">
                           <label for="category">Category</label>
                           <select name="category" id="category" class="form-control">
                                 @if ($categories->isNotEmpty())
                                       <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                       <option {{ $subCategory->category_id == $category->id ? 'selected' : '' }}  value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach 
                                 @else
                                    <option value="">Not Category Available</option>
                                 @endif
                                 
                           </select>
                           <p></p>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label for="name">Name</label>
                           <input type="text" name="name" id="name" value="{{ $subCategory->name }}"  class="form-control" placeholder="Name">
                           <p></p>	
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="mb-3">
                           <label for="slug">Slug</label>
                           <input type="text" readonly name="slug" id="slug" value="{{ $subCategory->slug }}"  class="form-control" placeholder="Slug">
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
                           <label for="status">Status</label>
                           <select name="status" id="status" class="form-control">
                              <option {{ $subCategory->status == 1 ? 'selected' : '' }} value="1">Active</option>
                              <option {{ $subCategory->status == 0 ? 'selected' : '' }} value="0">Block</option>
                           </select>
                           <p></p>
                        </div>
                  </div>									
                  </div>
               </div>							
            </div>
            <div class="pb-5 pt-3">
               <button type="submit" class="btn btn-primary">Update</button>
               <a href="subcategory.html" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
         </form>
      </div>
      <!-- /.card -->
   </section>
   <!-- /.content -->
@endsection

@section('customeJS')
<script>
   $('#subcategory').submit(function(event) {
        event.preventDefault();
        var element = $(this);
        console.log('clicked');

        $.ajax({
            url: "{{ route('sub-category.update', $subCategory->id) }}",  // Corrected with quotes
            type: 'PUT',
            data: element.serialize(),  // Using serialize() for form data
            dataType: 'json',  // Corrected dataType
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')  // Include CSRF token
            },
            success: function(response) {
                console.log(response);
                if( response['status'] == true ){

                    window.location.href = "{{ route('sub-category.index')}}"
                    
                    $('#name').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

                    $('#slug').removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html();

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
                    if( errors['category'] ) {

                        $('#category').addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors['category']);
                    } else {
                        $('#category').removeClass('is-invalid')
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