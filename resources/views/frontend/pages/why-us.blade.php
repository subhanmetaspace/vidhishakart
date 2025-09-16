@extends('frontend.layouts.master')
@section('title', env('APP_NAME') .' | Why Us')
@section('main-content')

	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="blog-single.html">Why Us</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- About Us -->
	<section class="about-us section">
			<div class="container">
				<div class="row">
                    <div class="col-md-12">
                        <div class="about-content">
                        <h3>Why <span>Us</span></h3>
                        <p><strong>Quality and Price Assurance </strong>:  product on our site comes with a guarantee of both quality and the best prices.
                            We source our items from authorized sellers and even directly from manufacturers to ensure
                            authenticity.
                        </p>
                        <p><strong> Best Prices Guaranteed: </strong> Prices Guaranteed: We take pride in offering you the best prices possible.
                            While you may not find everything under the sun on our website,
                            you can be sure that whatever you discover will come at unbeatable prices.
                        </p>
                        <p>
                            <strong>Local Shipping: </strong> All orders are shipped from the UAE, ensuring a swift and reliable delivery experience.
                        </p>
                         <p>
                           <strong>Flexible Delivery: </strong> Need your parcel on a specific day? Our Customer Services team is here to make it happen, at no extra charge.
                        </p>
                        <p><strong>100% Genuine Products: </strong> We guarantee that every item sold by DXB KART.COM is 100% genuine,
                            sourced directly from the manufacturer.
                        </p>
                        <p>
                            <strong>Price Promise</strong> We stand by our lowest price promise, ensuring you get the best deals without compromising on authenticity.<br>
                            We stand by our lowest price promise.
                            </p>
                            <p>Choose DXB KART.COM – where authenticity meets affordability!</p>
                        </div>
                    </div>
                </div>
			</div>
	</section>
	<!-- End About Us -->



@endsection
