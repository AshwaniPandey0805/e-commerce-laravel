
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
                    <h1>Create Product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('product.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <form action="" name="productForm" id="productForm">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-8">
                        <div class="card mb-3">
                            <div class="card-body">								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="title">Title</label>
                                            <input type="text" name="title" id="title" class="form-control" placeholder="Title">
                                            <p class="error" ></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="slug">Slug</label>
                                            <input type="text" readonly name="slug" id="slug" class="form-control" placeholder="Slug">
                                            <p class="error" ></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="short_description">Short Description</label>
                                            <textarea name="short_description" id="short_description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                        </div>
                                    </div>                                            
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="description">Description</label>
                                            <textarea name="description" id="description" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                        </div>
                                    </div>                                            
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="shipping_return">Shipping and Return</label>
                                            <textarea name="shipping_return" id="shipping_return" cols="30" rows="10" class="summernote" placeholder="Description"></textarea>
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>								
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">    
                                        <br>Drop files here or click to upload.<br><br>                                            
                                    </div>
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="row" id="product-gallary">

                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Pricing</h2>								
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="price">Price</label>
                                            <input type="text" name="price" id="price" class="form-control" placeholder="Price">
                                            <p class="error" ></p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="compare_price">Compare at Price</label>
                                            <input type="text" name="compare_price" id="compare_price" class="form-control" placeholder="Compare Price">
                                            <p class="text-muted mt-3">
                                                To show a reduced price, move the product’s original price into Compare at price. Enter a lower value into Price.
                                            </p>	
                                        </div>
                                    </div>                                            
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Inventory</h2>								
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku">SKU (Stock Keeping Unit)</label>
                                            <input type="text" name="sku" id="sku" class="form-control" placeholder="sku">
                                            <p class="error"></p>	
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="barcode">Barcode</label>
                                            <input type="text" name="barcode" id="barcode" class="form-control" placeholder="Barcode">	
                                        </div>
                                    </div>   
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="hidden" name="track_qty" value='No'>
                                                <input class="custom-control-input" type="checkbox" id="track_qty" name="track_qty" value="Yes" checked>
                                                <label for="track_qty" class="custom-control-label">Track Quantity</label>
                                                <p class="error" ></p>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <input type="number" min="0" name="qty" id="qty" class="form-control" placeholder="Qty">	
                                        </div>
                                    </div>                                         
                                </div>
                            </div>	                                                                      
                        </div>
                        <div class="card mb-3">
                            <div class="card-body" >
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="related_products">Related Product</label>
                                        <select multiple class="related_products w-100" name="related_products[]"  id="related_products">

                                        </select>
                                        <p class="error"></p>	
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product status</h2>
                                <div class="mb-3">
                                    <select name="status" id="status" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card">
                            <div class="card-body">	
                                <h2 class="h4  mb-3">Product category</h2>
                                <div class="mb-3">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select Category</option>
                                        @if (!empty($categories))
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <p class="error" ></p>
                                </div>
                                <div class="mb-3">
                                    <label for="sub_category">Sub category</label>
                                    <select name="sub_category" id="sub_category" class="form-control">
                                        <option value="">Mobile</option>
                                        <option value="">Home Theater</option>
                                        <option value="">Headphones</option>
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Product brand</h2>
                                <div class="mb-3">
                                    <select name="brand" id="brand" class="form-control">
                                        <option value="">Select Brand</option>
                                        @if (!empty($brands))
                                            @foreach ($brands as $brand)
                                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div> 
                        <div class="card mb-3">
                            <div class="card-body">	
                                <h2 class="h4 mb-3">Featured product</h2>
                                <div class="mb-3">
                                    <select name="is_featured" id="is_featured" class="form-control">
                                        <option value="No">No</option>
                                        <option value="Yes">Yes</option>                                                
                                    </select>
                                    <p class="error" ></p>
                                </div>
                            </div>
                        </div>                                 
                    </div>
                </div>
                
                <div class="pb-5 pt-3">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <a href="products.html" class="btn btn-outline-dark ml-3">Cancel</a>
                </div>
            </div>
        </form>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customeJS')
   <script>

        $('.related_products').select2({
            ajax: {
                url: "{{ route('product.getProducts') }}",
                dataType: 'json',
                tags: true,
                multiple: true,
                minimumInputLength: 3,
                processResults: function (data) {
                    return {
                        results: data.tags
                    };
                }
            }
        });

        $('#title').change(function() {
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

        $('#productForm').submit( function(event) {

            event.preventDefault();
            let formArray = $(this).serializeArray();
            $("button[type='submit']").prop('disable', true);
            
            $.ajax({
                url : "{{ route('product.store') }}",
                type : 'POST',
                data : formArray,
                dataType : 'json',
                success : function (response) {
                    if( response['status'] == true ){
                        $("button[type='submit']").prop('disable', false); 
                            window.location.href  = "{{ route('product.index') }}";
                    } else {
                        var errors = response['errors'];
                        
                        $('.error').removeClass('invalid-feedback').html('');
                        $("input[type='text'], select, input[type='number']").removeClass('is-invalid');
                        $.each(errors, function(key, value){
                            $(`#${key}`).addClass('is-invalid')
                            .siblings('p')
                            .addClass('invalid-feedback')
                            .html(value)
                        })

                    }

                },
                error : function () {
                    console.log('SomeThing went wrong');
                }
            })
        });

        $('#category').change( function() {
            console.log('clcked')
            var category_id = $(this).val();

            $.ajax({
                url : "{{ route('product.SubCategory') }}",
                type : 'post',
                data : { category_id : category_id },
                dataType : 'json',
                success : function ( response ){
                    console.log(response.subCategory);
                    $('#sub_category').find('option').not(':first').remove()
                    $.each( response['subCategory'], function( key, item ){
                        $('#sub_category').append(`<option value='${item.id}'>${item.name}</option>`)
                    } )
                },
                error : function () {
                    console.log('Something went wrong');
                }    
            });
        });

        Dropzone.autoDiscover = false;    
        const dropzone = $("#image").dropzone({ 
            // init: function() {
            //     this.on('addedfile', function(file) {
            //         if (this.files.length > 1) {
            //             this.removeFile(this.files[0]);
            //         }
            //     });
            // },
            url:  "{{ route('categories.image.upload') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }, success: function(file, response){
                // $("#image_id").val(response.image_id);
                //console.log(response)
                var html = `
                    <div class='col-md-3 mb-3' id='image-row-${response.image_id}'>  
                        <div class="card h-100"> 
                            <input type="hidden" name='image_array[]' value='${response.image_id}' > 
                            <img src="${response.path}" class="card-img-top" alt="Image">
                            <div class="card-body d-flex flex-column"> <!-- d-flex and flex-column to align content -->
                                <a href="javascript:void(0)" onclick='deleteImage(${response.image_id})'  class="btn btn-danger mt-auto">Delete</a> 
                            </div>
                        </div>
                    </div>`;

                $('#product-gallary').append(html);
            },
            complete : function (file) {
                        this.removeFile(file);
                        }
        });

        function deleteImage(id){
            $('#image-row-'+id).remove();
        }

   </script>
@endsection