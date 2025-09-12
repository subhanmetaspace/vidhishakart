@extends('frontend.layouts.master')
@section('title', env('APP_NAME') .' | Terms and Conditions')
@section('main-content')

	<!-- Breadcrumbs -->
	<div class="breadcrumbs">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="bread-inner">
						<ul class="bread-list">
							<li><a href="index1.html">Home<i class="ti-arrow-right"></i></a></li>
							<li class="active"><a href="blog-single.html">Terms and Conditions</a></li>
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
                        <h3>Terms and <span>Conditions</span></h3>

						<p><strong>Website:</strong> <a href="http://vidhishakart.com" target="_blank">http://vidhishakart.com</a></p>
                        <br/>
						<h4>1. Acceptance of Terms</h4>
						<p>By accessing and using this website, you agree to comply with and be bound by these Terms and Conditions. If you do not agree, please do not use the site.</p>
                        <br/>
						<h4>2. Use of the Website</h4>
						<ul>
							<li>You must be at least 18 years old to make purchases.</li>
							<li>You agree not to misuse the website or its content.</li>
							<li>All information you provide must be accurate and current.</li>
						</ul>
                        <br/>
						<h4>3. Product Information</h4>
						<ul>
							<li>We strive to provide accurate descriptions and images, but we do not guarantee that product details are 100% accurate, complete, or error-free.</li>
							<li>Prices and availability are subject to change without notice.</li>
						</ul>
                        <br/>
						<h4>4. Orders and Payments</h4>
						<ul>
							<li>Orders are subject to acceptance and availability.</li>
							<li>We reserve the right to cancel any order at our discretion.</li>
							<li>All payments must be made through our secure payment gateways.</li>
						</ul>
                        <br/>
						<h4>5. Shipping and Delivery</h4>
						<p>Delivery timelines are estimated and not guaranteed. We are not responsible for delays caused by logistics providers or force majeure events.</p>
                        <br/>
						<h4>6. Returns and Refunds</h4>
						<p>Please refer to our Return & Refund Policy Page. Items must be returned in unused condition within 7 days of delivery.</p>
                        <br/>
						<h4>7. Intellectual Property</h4>
						<p>All content on this website, including text, images, logos, and graphics, is the property of our Company. Unauthorized use or reproduction is strictly prohibited.</p>
                        <br/>
						<h4>8. User Accounts</h4>
						<p>You are responsible for maintaining the confidentiality of your account credentials. We reserve the right to suspend or terminate accounts for violation of our terms.</p>
                        <br/>
						<h4>9. Limitation of Liability</h4>
						<p>We are not liable for:</p>
						<ul>
							<li>Indirect, incidental, or consequential damages</li>
							<li>Losses resulting from website downtime or errors</li>
							<li>Any loss due to unauthorized use of your account</li>
						</ul>
                        <br/>
						<h4>10. Privacy</h4>
						<p>Your use of the site is also governed by our <a href="{{ route('privacy-policy') }}">Privacy Policy</a>.</p>
                        <br/>
						<h4>11. Governing Law</h4>
						<p>These Terms shall be governed by and construed in accordance with the laws of Uttar Pradesh.</p>
                        <br/>
						<h4>12. Contact Information</h4>
						<p>For any questions, please contact us at:</p>
						<p>
							📧 Email: <a href="mailto:info.vidhisha@gmail.com">info.vidhisha@gmail.com</a><br>
							📞 Phone: +918291899213
						</p>
                        </div>
                    </div>
                </div>
			</div>
	</section>
	<!-- End About Us -->



@endsection
