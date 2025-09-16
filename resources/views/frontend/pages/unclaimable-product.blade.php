@extends('frontend.layouts.master')

@section('title', env('APP_NAME'). ' | Flash sale items with No-Return Policy')

@section('main-content')

	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="blog-single.html">About Us</a></li>
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
				{{-- <div class="row">
					<div class="col-lg-12 col-12">
						<div class="about-content">
							@php
								$settings=DB::table('settings')->get();
							@endphp
                            <h3>Welcome </h3>
							<p>@foreach($settings as $data) {{$data->description}} @endforeach</p>
							<div class="button">
								<a href="{{route('blog')}}" class="btn">Our Blog</a>
								<a href="{{route('contact')}}" class="btn primary">Contact Us</a>
							</div>
						</div>
					</div>
                </div> --}}

            <div class="row">
                <div class="col-md-12">
                    <div class="about-content">
                    {{-- <h3>Unclaimable <span>Products</span></h3> --}}
                    <h3>*Flash sale items with <span> No-Return Policy</span></h3>
                    <p>
                        Flash sale items, including marked down products, flash sales, season sales, special offers,
                        today's deals, open box, and daily deals,
                        are sold at special super low prices and come with a strict no-return policy.
                    </p>
                    <p>
                        <strong>Conditions for No-Return Policy</strong>
                    </p>
                    <p>

                        Free-size or multi-color products cannot be claimed for return. These include items listed on the site as multi-color, free-size, assorted color, assorted design, etc. Sizes and colors cannot be guaranteed and are subject to availability.
                    </p><br>
                    <p>
                        <strong>Return & Replacement Policy:</strong>
                    </p>
                    <p>
                        To claim a return, check if the purchased product falls under the warranty as per the Warranty Policy. If the product is under warranty, you can claim for return based on the following conditions:
                    </p>
                    <p>A customer can claim for return in case the product is damaged or not functional within 3 days from the date of receiving the product.</p>
                    <p>If a customer doesn't like the product, replacement is possible (excluding marked down products, flash sales, seasonal sales, special offers) with another product of the same or greater value. <strong>Delivery charges will apply.</strong></p>
                    <p>Shipping and payment processing charges are non-refundable.</p>

                    <p>
                        <strong> Replacement Procedure:</strong>
                    </p>
                     <p> <strong>a.</strong> Approved: If the product is found faulty as per the policy, the replacement process will be initiated.</p>
                     <p><strong>b.</strong> Not Approved: For products not faulty and claimed due to not meeting expectations, pickup charges of AED 20 and replacement with another product delivery charges will be applicable.</p>

                     <p>
                        <strong> Delivery Policy:</strong>
                    </p>
                     <p>
                        We offer free delivery in major parts of the UAE during promotions. Otherwise, delivery charges depend on the product and delivery location.
                     </p>
                     <p><strong>Note:</strong> Shipping charges and VAT charges are non-refundable.</p>
                     <p>Product Images:</p>
                     <p>All product images are for illustrative purposes only and may differ from the actual product due to enhancements. Due to differences in monitors, product colors may also appear different from those shown on the site.</p>


                     <p>
                        <strong> Privacy Policy:</strong>
                    </p>
                     <p>
                        It is DXB KART's policy to respect your privacy regarding any information collected while operating our websites.
                     </p>


                     <p>
                        <strong> Privacy Policy Changes:</strong>
                    </p>
                     <p>
                        DXB KART may change its privacy policy from time to time. Visitors are encouraged to frequently check this page for any changes. Continued use of this site after any change in the privacy policy constitutes acceptance of such change.
                     </p>

                    </div>
                </div>
                </div>
			</div>
	</section>
	<!-- End About Us -->
@endsection
