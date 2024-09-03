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
                    <h1>Order: #4F3S8J</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('order.index') }}" class="btn btn-primary">Back</a>
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
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header pt-3">
                            <div class="row invoice-info">
                                <div class="col-sm-4 invoice-col">
                                <h1 class="h5 mb-3">Shipping Address</h1>
                                <address>
                                    <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                                    {{ $order->apartement }}<br>
                                    {{ $order->city }}, {{ $order->state }} {{ $order->zip_code }}<br>
                                    Phone: {{ $order->mobile }}<br>
                                    Email: {{ $order->email }}
                                </address>
                                </div>
                                
                                
                                
                                <div class="col-sm-4 invoice-col">
                                    <b>Invoice #{{ $order->id }}</b><br>
                                    <br>
                                    <b>Order ID:</b> {{ $order->id }}<br>
                                    <b>Total:</b> ${{ number_format($order->grand_total, 2) }}<br>
                                    @if ($order->status == 'shipped')
                                        <b>Status:</b> <span class="text-primary">Shipped</span>  
                                    @endif
                                    @if ($order->status == 'delivered')
                                        <b>Status:</b> <span class="text-success">Delivered</span>    
                                    @endif
                                    @if ($order->status == 'pending')
                                        <b>Status:</b> <span class="text-danger">Pending</span>   
                                    @endif
                                    
                                    {{-- <b>Status:</b> <span class="text-success">Delivered</span> --}}
                                    <br>
                                </div>
                            </div>
                        </div>
                        <div class="card-body table-responsive p-3">								
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th width="100">Price</th>
                                        <th width="100">Qty</th>                                        
                                        <th width="100">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($orderItems))
                                        @foreach ($orderItems as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>${{ number_format($item->price, 2) }}</td>                                        
                                                <td>{{ $item->qty }}</td>
                                                <td>${{ number_format(($item->price * $item->qty), 2) }}</d>
                                            </tr>        
                                        @endforeach
                                    @else
                                        
                                    @endif
                                    
                                    <tr>
                                        <th colspan="3" class="text-right">Subtotal:</th>
                                        <td>${{ number_format($order->subtotal,2) }}</td>
                                    </tr>
                                    @if (isset($order->coupon_code))
                                        <tr>
                                            <th colspan="3" class="text-right">Discount: ( {{ $order->coupon_code }} ) </th>
                                            <td>${{ number_format($order->discount, 2) }}</td>
                                        </tr>    
                                    @endif
                                    <tr>
                                        <th colspan="3" class="text-right">Shipping:</th>
                                        <td>${{ number_format($order->shipping, 2) }}</td>
                                    </tr>
                                    
                                    <tr>
                                        <th colspan="3" class="text-right">Grand Total:</th>
                                        <td>${{ number_format($order->grand_total,2) }}</td>
                                    </tr>
                                </tbody>
                            </table>								
                        </div>                            
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Order Status</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option {{ ($order->status == 'pending') ? 'selected' : '' }}  value="pending">Pending</option>
                                    <option {{ ($order->status == 'shipped') ? 'selected' : '' }}  value="shipped">Shipped</option>
                                    <option {{ ($order->status == 'delivered') ? 'selected' : '' }}  value="delivered">Delivered</option>
                                    <option {{ ($order->status == 'cancelled') ? 'selected' : '' }}  value="cancelled">Cancelled</option>
                                </select>
                            </div>
                            <div class="mb-3" >
                                <label for="shipped_date">Shipping Date</label>
                                <input autocomplete="off" type="text" class="form-control"  id="shipped_date" name="shipped_date" placeholder="Select Shipping Date" >
                                <p></p>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary" id="update_order_detail" >Update</button>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h2 class="h4 mb-3">Send Inovice Email</h2>
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="">Customer</option>                                                
                                    <option value="">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customeJS')
    <script>

        $(document).ready(function(){
            $('#shipped_date').datetimepicker({
                // options here
                format:'Y-m-d H:i:s',
            });
            
        });

        $("#update_order_detail").click(function(){
            $.ajax({
                url : "{{route('order.update',$order->id)}}",
                type : "post",
                data : { 
                    order_status : $("#status").val(),
                    shipped_date  : $("#shipped_date").val() 
                },
                dataType : "json",
                success : function ( response ){
                    var error = response['error'];
                    console.log(response);
                    if(response['status'] ==  false){
                        if(error['shipped_date']){
                            $("#shipped_date").addClass('is-invalid').siblings('p').addClass('invalid-feedback').html(error['shipped_date'])    
                        } else {
                            $("#shipped_date").removeClass('is-invalid').siblings('p').removeClass('invalid-feedback').html('');
                        }
                    } else {
                        var id = response['order_id'];
                        window.location.href = `{{ url('/admin/order/${id}/detail') }}`;
                    }
                },
                error : function ( error ){
                    console.log(error.message);
                }

            })
        })
    </script>
@endsection