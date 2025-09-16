@extends('frontend.layouts.master')
@section('title','Cart Page')
@section('main-content')
	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="{{('home')}}">Home<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="">Thank You</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->
<?php
//$arr = Array ( [product_image] => http://localhost/qrmart/public/storage/app/public/photos/product/FE_65ab6727702c7_Headset.jpg [product_title] => Electronic New Machine [shipping_cost] => 30 [quantity] => 180: 2 pairs [total_price] => 180 );
//print_r(session('order'));
$data = (session('order') !== null)? session('order') : [];
?>
@if(!empty($data))
	<!-- Shopping Cart -->
	<div class="shopping-cart section">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<!-- Shopping Summery -->
                    {{-- Array ( [product_image] => http://localhost/qrmart/public/storage/app/public/photos/product/FE_65ab6727702c7_Headset.jpg [product_title] => Electronic New Machine [shipping_cost] => 30 [quantity] => 180: 2 pairs [total_price] => 180 ) --}}

                        <table class="table shopping-summery">
                            <thead>
                                <tr>
                                    <th class="text-left">PRODUCT</th>
                                    {{-- <th>NAME</th> --}}
                                    <th class="text-center">QUANTITY</th>
                                    <th class="text-center">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody id="cart_item_list">
                                <tr>
                                    <td class="text-left" data-title="Product">
                                        <img src="{{ $data['product_image'] }}">
                                        <p class="product-title">
                                        {{ $data['product_title'] }}</p> </td>
                                    <td class="qty text-center" data-title="Qty"> {{ $data['quantity'] }}</td>
                                    <td class="price text-center" data-title="Price">{{ $data['total_price'] }} AED</td>
                                </tr>
                                @php
                               // print_r($data);die;
                                    $totalCartPrice    = $data['total_price'];
                                    $totalDeliveryCost = $data['shipping_cost'];
                                    //$totalVatCost      = round((($data['total_price']*5)/100), 2);
                                    if($data['vat'] == 'yes')
                                    {
                                        $totalVatCost      = round((($data['total_price']*5)/100), 2);
                                    }else{
                                        $totalVatCost      = 0;
                                    }
                                @endphp

                                @if($todayOrder)
                                    @foreach($todayOrder as $torder)
                                    <tr>
                                        <td class="text-left" data-title="Product">
                                            <img src="{{ $torder->photo }}">
                                            <p class="product-title">
                                            {{ $torder->product_name }}</p> </td>
                                        <td class="qty text-center" data-title="Qty"> {{ (!empty($torder->quantity_selected))? $torder->quantity_selected : $torder->quantity  }}</td>
                                        <td class="price text-center" data-title="Price">{{ $torder->sub_total }} AED</td>
                                    </tr>
                                        @php
                                            $totalDeliveryCost = $totalDeliveryCost + $torder->delivery_charge;
                                            $totalCartPrice    = $totalCartPrice + $torder->sub_total;
                                            if($torder->vat == 'yes')
                                            {
                                                $totalVatCost  = $totalVatCost + round((($torder->sub_total * 5)/100), 2);
                                               // $totalVatCost = round((($data['total_price']*5)/100), 2);
                                            }else{
                                                $totalVatCost  = $totalVatCost + 0;
                                            }
                                        @endphp
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
					<!--/ End Shopping Summery -->
				</div>
			</div>
			<div class="row">
				<div class="col-12">
					<!-- Total Amount -->
					<div class="total-amount">
						<div class="row">
							<div class="col-lg-8 col-md-5 col-12">
								{{-- <div class="left">
									<div class="coupon">
                                        There are no any carts available. <a href="{{route('product-grids')}}" style="color:blue;">Continue shopping</a>
									</div>
								</div> --}}
							</div>
                            <?php
                                // $cookieData = Cache::get('current_user');
                                // $decodedData = json_decode($cookieData, true);
                                // print_r($decodedData);
                            ?>
							<div class="col-lg-4 col-md-7 col-12">
								<div class="right">
									<ul>
										<li class="order_subtotal" data-price="">Shipping Cost :<span>{{ $totalDeliveryCost }} AED</span></li>
                                        <li class="order_subtotal" data-price="">Vat :<span> {{ $totalVatCost }} AED</span></li>
										<li class="last" id="order_total_price"><strong>Sub Total :<span>{{ $totalCartPrice + $totalDeliveryCost + $totalVatCost }} AED</span></strong></li>
									</ul>
								</div>
							</div>
                            <a href="{{route('home')}}" style="" class="btn btn-success pull-right text-white ml-4">Continue shopping</a>
						</div>
					</div>

					<!--/ End Total Amount -->
                    <span class="bg-success d-block text-white text-center p-2 m-2"> Your product order has been placed. Thank you for shopping with us, We will contact you shortly</span>
				</div>
			</div>
		</div>
	</div>
	<!--/ End Shopping Cart -->
@endif
@endsection
@push('styles')
	<style>
		li.shipping{
			display: inline-flex;
			width: 100%;
			font-size: 14px;
		}
		li.shipping .input-group-icon {
			width: 100%;
			margin-left: 10px;
		}
		.input-group-icon .icon {
			position: absolute;
			left: 20px;
			top: 0;
			line-height: 40px;
			z-index: 3;
		}
		.form-select {
			height: 30px;
			width: 100%;
		}
		.form-select .nice-select {
			border: none;
			border-radius: 0px;
			height: 40px;
			background: #f6f6f6 !important;
			padding-left: 45px;
			padding-right: 40px;
			width: 100%;
		}
		.list li{
			margin-bottom:0 !important;
		}
		.list li:hover{
			background:#F7941D !important;
			color:white !important;
		}
		.form-select .nice-select::after {
			top: 14px;
		}
        .shopping-cart .table td{
        padding: 10px 20px !important;
    }
    .shopping-cart .total-amount .right ul li span {
            display: inline-block;
            float: right;
            margin-right: 50px !important;
        }
        .shopping-cart .total-amount{
            margin-top: 10px !important;
        }
        .product-title
        {
            text-align: center;
            max-width: 200px;
            margin-left: 10px;
            display: inline;
        }


        @media (max-width: 767px) {
            .shopping-cart .table td{
                padding-left: calc(50% + 20px) !important;
                }
        }
	</style>

@endpush
@push('scripts')
	<script src="{{asset('frontend/js/nice-select/js/jquery.nice-select.min.js')}}"></script>
	<script src="{{ asset('frontend/js/select2/js/select2.min.js') }}"></script>
	<script>
		$(document).ready(function() { $("select.select2").select2(); });
  		$('select.nice-select').niceSelect();
	</script>
	<script>
		$(document).ready(function(){
			$('.shipping select[name=shipping]').change(function(){
				let cost = parseFloat( $(this).find('option:selected').data('price') ) || 0;
				let subtotal = parseFloat( $('.order_subtotal').data('price') );
				let coupon = parseFloat( $('.coupon_price').data('price') ) || 0;
				// alert(coupon);
				$('#order_total_price span').text('$'+(subtotal + cost-coupon).toFixed(2));
			});

		});

	</script>

@endpush
