
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
                    <h1>Orders</h1>
                </div>
                <div class="col-sm-6 text-right">
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
        
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                              </button>
                            </div>
                          </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">								
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Orders #</th>											
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($orders))
                                @foreach ($orders as $order)
                                    <tr>
                                        <td><a href="{{ route('order.detail', $order->id) }}">{{ $order->id }}</a></td>
                                        <td>{{ $order->first_name }} {{ $order->last_name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->mobile }}</td>
                                        @if ($order->status == 'shipped')
                                            
                                        @endif
                                        <td>
                                            @if ($order->status == 'shipped')
                                                <span class="badge" style="background-color: #007bff;">Shipped</span>    
                                            @endif
                                            @if ($order->status == 'delivered')
                                                <span class="badge" style="background-color: #28a745;">Delivered</span>    
                                            @endif
                                            @if ($order->status == 'pending')
                                                <span class="badge" style="background-color: #dc3545;">Pending</span>    
                                            @endif
                                        </td>
                                        <td>${{ number_format($order->grand_total, 2) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format("d M, Y") }}</td>																				
                                    </tr>        
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" >No record found</td>
                                </tr>
                            @endif
                            
                            
                        </tbody>
                    </table>										
                </div>
                <div class="card-footer clearfix">
                    {{ $orders->links() }}
                    {{-- <ul class="pagination pagination m-0 float-right">
                      <li class="page-item"><a class="page-link" href="#">«</a></li>
                      <li class="page-item"><a class="page-link" href="#">1</a></li>
                      <li class="page-item"><a class="page-link" href="#">2</a></li>
                      <li class="page-item"><a class="page-link" href="#">3</a></li>
                      <li class="page-item"><a class="page-link" href="#">»</a></li>
                    </ul> --}}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection

@section('customeJS')
@endsection