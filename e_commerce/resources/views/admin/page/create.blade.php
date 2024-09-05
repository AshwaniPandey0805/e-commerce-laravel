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
								<h1>Create Page</h1>
							</div>
							<div class="col-sm-6 text-right">
								<a href="{{route('page.index')}}" class="btn btn-primary">Back</a>
							</div>
						</div>
					</div>
					<!-- /.container-fluid -->
				</section>
				<!-- Main content -->
				<section class="content">
					<!-- Default box -->
					<form action="" id="page_form" name="page_form" >
                        <div class="container-fluid">
                            <div class="card">
                                <div class="card-body">								
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                                <p></p>	
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="slug">Slug</label>
                                                <input readonly type="text" name="slug" id="slug" class="form-control" placeholder="Slug">
                                                <p></p>	
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="status">Status</label>
                                                <select name="status" id="status" class="form-control">
                                                    <option value="">Select</option>
                                                    <option value="1">Active</option>
                                                    <option value="0">In-Active</option>
                                                </select>
                                                <p></p>
                                            </div>
                                        </div>	
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="content">Content</label>
                                                <textarea name="content" id="content" class="summernote" cols="30" rows="10"></textarea>
                                                <p></p>
                                            </div>								
                                        </div>                                    
                                    </div>
                                </div>							
                            </div>
                            <div class="pb-5 pt-3">
                                <button type="submit" class="btn btn-primary">Create</button>
                                <a href="pages.html" class="btn btn-outline-dark ml-3">Cancel</a>
                            </div>
                        </div>
                    </form>
					<!-- /.card -->
				</section>
				<!-- /.content -->
    
@endsection

@section('customeJS')
    <script>

        $("#page_form").submit(function(event){
            event.preventDefault();
            var formData = $(this).serializeArray();
            $.ajax({
                url : "{{route('page.store')}}",
                type : 'POST',
                data : formData,
                dataType : 'json',
                success : function ( response ){
                    if(response['status'] == false){
                        var errors = response['errors'];
                        if(errors['name']){
                            $("#name").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['name'])
                        } else {
                            $("#name").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html()
                        }
                        if(errors['slug']){
                            $("#slug").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['slug'])
                        } else {
                            $("#slug").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html()
                        }
                        if(errors['content']){
                            $("#content").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['content'])
                        } else {
                            $("#content").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html()
                        }
                        if(errors['status']){
                            $("#status").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(errors['status'])
                        } else {
                            $("#status").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html()
                        }
                    } else {
                        window.location.href = "{{route('page.index')}}"
                    }
                }, 
                error : function ( errror ){
                    console.log(error.message);
                }
            })
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
    </script>
@endsection