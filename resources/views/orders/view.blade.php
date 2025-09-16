@extends('frontend.layouts.master')

@section('title','Order Details')

@section('main-content')
    <!-- Breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="bread-inner">
                        <ul class="bread-list">
                            <li><a href="{{ route('home') }}">Home<i class="ti-arrow-right"></i></a></li>
                            <li class="active"><a href="#">Order #{{ $order->order_number }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Breadcrumbs -->

    <div class="shopping-cart section">
        <div class="container">
            <div class="row">
                <!-- Customer Info -->
                <div class="col-lg-4 col-md-6 col-12 mb-4 d-flex">
                    <div class="card shadow-sm p-3 w-100 h-100">
                        <h5 class="mb-3">Customer Info</h5>
                        <p><strong>Name:</strong> {{ $order->first_name }} {{ $order->last_name }}</p>
                        <p><strong>Email:</strong> {{ $order->email ?? 'N/A' }}</p>
                        <p><strong>Phone:</strong> {{ $order->phone }}</p>
                        <p><strong>Address:</strong><br>
                            {{ $order->address1 }} {{ $order->address2 ?? '' }}<br>
                            {{ $order->country }}
                        </p>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="col-lg-4 col-md-6 col-12 mb-4 d-flex">
                    <div class="card shadow-sm p-3 w-100 h-100">
                        <h5 class="mb-3">Order Info</h5>
                        <p><strong>Order #:</strong> {{ $order->order_number }}</p>
                        <p><strong>Date:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
                        <p>
                            <strong>Status:</strong>
                            <span class="badge badge-info">{{ ucfirst($order->status) }}</span>
                        </p>
                        <p>
                            <strong>Payment:</strong>
                            <span class="badge {{ $order->payment_status == 'paid' ? 'badge-success' : 'badge-warning' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        <p><strong>Method:</strong> {{ ucfirst($order->payment_method) }}</p>
                    </div>
                </div>

                <!-- Summary -->
                <div class="col-lg-4 col-md-12 col-12 mb-4 d-flex">
                    <div class="card shadow-sm p-3 w-100 h-100">
                        <h5 class="mb-3">Summary</h5>
                        <p><strong>Subtotal:</strong> ₹{{ $order->sub_total }}</p>
                        <p><strong>Shipping:</strong> ₹{{ $order->shipping_amount ?? 0 }}</p>
                        <p><strong>VAT:</strong> ₹{{ $order->vat_amount ?? 0 }}</p>
                        <h5 class="mt-2"><strong>Total:</strong> ₹{{ $order->total_amount }}</h5>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm p-3">
                        <h5 class="mb-3">Ordered Products</h5>
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Product</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $products = explode(',', $order->product_name);
                                    $quantities = explode(',', $order->quantity_selected ?? $order->quantity);
                                    $subtotals = explode(',', $order->sub_total);
                                @endphp
                                @foreach($products as $key => $product)
                                    <tr>
                                        <td>{{ $product }}</td>
                                        <td class="text-center">{{ $quantities[$key] ?? 1 }}</td>
                                        <td class="text-center">₹{{ $subtotals[$key] ?? $order->sub_total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="text-right mt-3">
                            <a style="color:#fff" href="{{ route('home') }}" class="btn">Continue Shopping</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Newsletter -->
    @include('frontend.layouts.newsletter')
@endsection
