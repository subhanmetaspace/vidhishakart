@extends('frontend.layouts.master')
@section('title', env('APP_NAME') .' | Return & Exchange Policy')
@section('main-content')

	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="blog-single.html">Return & Exchange Policy</a></li>
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
                        <h3>Return & <span>Exchange Policy</span></h3>
                        <br/>
						<h4>1. What Products Can’t Be Returned</h4>
						<ul>
							<li>Flash sale items, season sale / special offer / daily deal products, and marked‐down / discounted items are non-returnable.</li>
							<li>Free-size or multi-color products (assorted colours, assorted designs) are also non-returnable.</li>
						</ul>
						<br/>
						<h4>2. When You Can Request a Return / Replacement</h4>
						<ul>
							<li>If a product is damaged or not functional, you may request a return within 3 days from the date of receiving it.</li>
							<li>If you simply don't like the product (in cases where it is eligible), you may request a replacement (not a refund) with another product of same or greater value, subject to delivery/return charges.</li>
						</ul>
						<br/>
						<h4>3. Procedure & Charges</h4>
						<p>
							To initiate a return or replacement, the item must be under warranty or meet the “faulty / damaged” criteria.  
							For replacements requested for reasons besides defects (e.g., style or expectational mismatch), there is a pickup charge (e.g. AED 20) + any relevant re-delivery/shipping costs.  
							Shipping charges and any payment processing fees are non-refundable.
						</p>
						<br/>
						<h4>4. Timeframe for Action</h4>
						<ul>
							<li>Damage / non-functionality claims must be raised within 3 days of delivery.</li>
							<li>Any replacement or return request for non-faulty issues must follow the timeframe laid out in the product page / policy. (If no product-specific timeframe is listed, assume “within 3 days” unless otherwise communicated.)</li>
						</ul>
						<br/>
						<h4>5. Conditions for the Product</h4>
						<p>
							Items must be in their original condition: unused, unwashed, with all original packaging, labels, and tags intact.  
							Products not meeting these conditions or those clearly used / damaged by user may be rejected for return / replacement.
						</p>
						<br/>
						<h4>6. What Happens After Approval</h4>
						<p>
							Once the return or replacement request is approved, the replacement process commences.  
							If the product is found not faulty but returned due to customer preference, applicable charges as per policy (pickup, delivery) will be applied.
						</p>
                        </div>
                    </div>
                </div>
			</div>
	</section>
	<!-- End About Us -->



@endsection
