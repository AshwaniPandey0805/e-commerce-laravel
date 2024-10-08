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
                    <div class="card-body p-4">
                        <div class="table-responsive">
                            <table class="table">
                                <thead> 
                                    <tr>
                                        <th>Orders #</th>
                                        <th>Date Purchased</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($orders->isNotEmpty())
                                        @foreach ($orders as $order)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('account.orderDetail' , $order->id) }}">{{ $order->id }}</a>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M, Y') }}</td>
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
                                                <td>$ {{ number_format($order->grand_total, 2) }}</td>
                                            </tr>        
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection