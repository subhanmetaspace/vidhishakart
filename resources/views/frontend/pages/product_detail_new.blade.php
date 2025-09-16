@extends('frontend.layouts.master')

@section('meta')
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name='copyright' content=''>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="keywords" content="online shop, purchase, cart, ecommerce site, best online shopping UAE">
	<meta name="description" content="{{$product_detail->summary}}">
	<meta property="og:url" content="{{route('product-detail',$product_detail->slug)}}">
	<meta property="og:type" content="article">
	<meta property="og:title" content="{{$product_detail->title}}">
	<meta property="og:image" content="{{$product_detail->photo}}">
	<meta property="og:description" content="{{$product_detail->description}}">
    <style>
        .m-0
        {
            padding:0px !important;
        }
        .prefix-code
        {
            margin-right: 0px;
            border: 1px solid #ccc;
            padding: 5px 5px;
            background: #eee;
            border-right: 0px;
        }
        label
        {
            display: inline-block;
            margin-bottom: 0px !important;
            margin-top: 10px;
            font-size: 16px;
        }
        input[type="text"],input[type="number"],input[type="email"], textarea{
            padding: 8px !important;
        }
        .cart .nice-select
        {
            height: 45px !important;
            width: 100% !important;
            padding: 8px !important;
        }
        .shop.single .product-info {
            margin-top: 20px;
            background: #eee;
            padding: 20px;
        }
        .shop.single .single-des {
            margin-top: 20px !important;
        }

        .shop.single .nav-tabs li a.active, .shop.single .nav-tabs li:hover a
        {
            background: none !important;
            color: #000!important;
            border-bottom: 1px solid #333 !important;
            font-size: 18px;
            padding-left: 19px;
        }
        .shop.single .product-gallery .slides li {
            position: relative;
            max-height: 500px;
        }
        .flex-control-thumbs li img
        {
            max-height: 100px !important;
        }

    </style>
@endsection
@section('title', env('APP_NAME'). ' | ' . $product_detail->title)
@section('main-content')

		<!-- Breadcrumbs -->
		<div class="breadcrumbs">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="bread-inner">
							<ul class="bread-list">
								<li><a href="{{route('home')}}">Home<i class="ti-arrow-right"></i></a></li>
								<li class="active"><a href="">Shop Details</a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End Breadcrumbs -->

		<!-- Shop Single -->
		<section class="shop single section m-0" >
            <div class="container">
                <div class="row">
                    <div class="col-12">
                    <?php 
                        $data = (session('status') !== null)? session('status') : [];
                        // print_r($data);
                     ?>
                    @if(@$data[status] == 'FAILED')
                        <span class="bg-danger d-block text-white text-center p-2 m-2">Your order not completed due to payment failed ({{ $data['message'] }}).</span>
                    @endif
                        <div class="row">
                            <div class="col-lg-6 col-12">
                                <!-- Product Slider -->
                                <div class="product-gallery">
                                    <!-- Images slider -->
                                    <div class="flexslider-thumbnails">
                                        <ul class="slides">
                                            <li data-thumb="{{$product_detail->photo }}" rel="adjustX:10, adjustY:">
                                                <img src="{{ $product_detail->photo }}" alt="{{  $product_detail->photo }}">
                                            </li>
                                            @php
                                                $images  =   $product_detail->images;
                                            @endphp
                                            @foreach($images as $data)
                                            @php $fullUrl = url(env('APP_URL').'/storage/app/public/photos/'.$product_detail->id.'/'.$data->image) @endphp
                                                <li data-thumb="{{ $fullUrl }}" rel="">
                                                    <img src="{{$fullUrl}}" alt="{{$fullUrl}}" >
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    <!-- End Images slider -->
                                </div>
                                <!-- End Product slider -->
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="product-des">
                                    <!-- Description -->
                                    <div class="short">
                                        <h4>{{$product_detail->title}}</h4>
                                        {{-- <div class="rating-main">
                                            <ul class="rating">
                                                @php
                                                    $rate=ceil($product_detail->getReview->avg('rate'))
                                                @endphp
                                                    @for($i=1; $i<=5; $i++)
                                                        @if($rate>=$i)
                                                            <li><i class="fa fa-star"></i></li>
                                                        @else
                                                            <li><i class="fa fa-star-o"></i></li>
                                                        @endif
                                                    @endfor
                                            </ul>
                                            <a href="#" class="total-review">({{$product_detail['getReview']->count()}}) Review</a>
                                        </div> --}}
                                        @php
                                           // $after_discount=($product_detail->price-(($product_detail->price*$product_detail->discount)/100));
                                        @endphp
                                        <p class="price">
                                            <span class="discount">{{number_format($product_detail->discount_price, 2)}} INR</span>
                                            <s>{{number_format($product_detail->price, 2)}} INR</s>
                                        </p>
                                        {{-- <p class="description">{!!($product_detail->summary)!!}</p> --}}
                                    </div>
                                    <!--/ End Description -->
                                    <!-- Color -->
                                    {{-- <div class="color">
                                        <h4>Available Options <span>Color</span></h4>
                                        <ul>
                                            <li><a href="#" class="one"><i class="ti-check"></i></a></li>
                                            <li><a href="#" class="two"><i class="ti-check"></i></a></li>
                                            <li><a href="#" class="three"><i class="ti-check"></i></a></li>
                                            <li><a href="#" class="four"><i class="ti-check"></i></a></li>
                                        </ul>
                                    </div> --}}
                                    <!--/ End Color -->

                                    <!-- Product Buy -->
                                    <div class="product-buy">
                                        <form action="{{route('cart.order_new')}}" method="POST" id="order-form">
                                            @csrf
                                            <?php //print_r($predata);  ?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                        <label>First Name<span>*</span></label>
                                                        <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Full name" value="{{ @$predata->first_name }}" required="">
                                                       {{-- <span class="text-danger error-msg-first_name"> </span> --}}
                                                    </div>
                                                {{-- <div class="col-lg-6 col-md-6 col-6">
                                                    <label>Last Name<span></span></label>
                                                    <input type="text" class="form-control" name="last_name" placeholder="Last name" value="">
                                                </div> --}}
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <label>Mobile<span>*</span></label>
                                                    {{-- <input type="number" class="form-control" name="phone" placeholder="" value="" required=""> --}}
                                                    <div class="input-group input-group-sm">
                                                        <div class="input-group-prepend prefix-code">
                                                            <!-- img src="https://reliancezone.ae/uploads/lib/in.png" width="20" -->
                                                            <span class="input-group-text" id="inputGroup-sizing-sm">+91 </span>
                                                        </div>
                                                        <input type="number" id="phone" name="phone" class="form-control" aria-label="Small" placeholder="Mobile" aria-describedby="inputGroup-sizing-sm" value="{{ @$predata->phone }}" required="">
                                                       {{-- <br> <span class="text-danger error-msg-phone"> </span> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            @if($product_detail->size || $product_detail->color)
                                            <div class="row">
                                                @if($product_detail->size)
                                                <div class="col-lg-12 col-md-12 col-12 cart">
                                                    <label>Size<span>*</span></label><br>
                                                    @php
                                                        $sizes=explode(',',$product_detail->size);
                                                    @endphp
                                                    <select name="size" id="size" class="" style="width: 100%">
                                                        @foreach($sizes as $size)
                                                            @if(!empty($size))
                                                            <option value="{{$size}}">{{$size}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                                @if($product_detail->color)
                                                <div class="col-lg-12 col-md-12 col-12  cart">
                                                    <label>Color<span>*</span></label><br>
                                                    @php
                                                        $colors=explode(',',$product_detail->color);
                                                    @endphp
                                                    <select name="color" class="" id="color" style="width: 100%">
                                                        @foreach($colors as $color)
                                                            @if(!empty($color))
                                                                <option value="{{$color}}">{{$color}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @endif
                                            </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12 cart">
                                                    <label>Shipping Area<span>*</span></label><br>
                                                    <select name="shipping" class="" id="shipping" style="width: 100%">
                                                        <option value="">Select your address</option>
                                                        @foreach(Helper::shipping() as $shipping)
                                                            <option value="{{$shipping->id}}" class="shippingOption" data-price="{{$shipping->price}}" @if(isset($predata->shipping) && $shipping->id == $predata->shipping) selected  @endif >{{$shipping->type}}: {{ ($product_detail->delivery_charge)? $product_detail->delivery_charge.' INR':' Free' }} Delivery</option>
                                                            {{-- <option value="{{$shipping->id}}" class="shippingOption" data-price="{{$shipping->price}}">{{$shipping->type}}: {{$shipping->price}} INR</option> --}}
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="quantity">
                                                <h6>Quantity :</h6>
                                                <div class="input-group">
                                                    <div class="button minus">
                                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quantity">
                                                            <i class="ti-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="hidden" name="slug" value="{{$product_detail->slug}}">
                                                    <input type="text" name="quantity" class="input-number"  data-min="1" data-max="1000" value="1" id="quantity">
                                                    <div class="button plus">
                                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quantity">
                                                            <i class="ti-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div> --}}
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12 cart">
                                                    <label>Quantity<span>*</span></label><br>
                                                        <select name="quantity" class="" id="quantity" style="width: 100%">
                                                            <option value="">Select Quantity</option>
                                                            @if(!empty($product_detail->quantity_price) && $product_detail->quantity_price != '')
                                                                @php
                                                                    $quantity_prices  =   explode(',', $product_detail->quantity_price);
                                                                @endphp
                                                                    @foreach($quantity_prices as $key => $quantity_price)

                                                                        @if(!empty($quantity_price))
                                                                            @php
                                                                                $quantity_price_part  =   explode(':', $quantity_price);
                                                                            @endphp
                                                                            <option value="key-{{ $key+1 }}">{{ ucWords($quantity_price_part[1]) }} - {{ $quantity_price_part[0] }} INR</option>
                                                                        @endif
                                                                    @endforeach
                                                            @else
                                                                    @for($i = 1; $i<=10; $i++)
                                                                        {{-- @if(!empty($product_detail->discount_price)) --}}
                                                                            <option value="actual-{{ $i }}">{{ $i }} - {{ $product_detail->discount_price*$i }} INR</option>
                                                                        {{-- @endif --}}
                                                                    @endfor

                                                            @endif
                                                        </select>
                                                            {{-- @foreach(Helper::shipping() as $shipping)
                                                                <option value="{{$shipping->id}}" class="shippingOption" data-price="{{$shipping->price}}">{{$shipping->type}}: {{ ($shipping->price)? $shipping->price.' INR':' Free' }} Delivery</option>
                                                            @endforeach
                                                        </select>--}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                        <label>Email</label>
                                                        <input type="email"class="form-control" name="email" placeholder="Email"  value="{{ @$predata->email }}">
                                                        <br>   <span class="text-danger error-msg-email"> </span>
                                                </div>
                                            </div>
                                           
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                        <label>Address</label>
                                                        <textarea id="address1" name="address1" placeholder="Delivery Address" required="">{{ @$predata->address1 }}</textarea>
                                                        {{-- <br>    <span class="text-danger error-msg-address1"> </span> --}}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12 cart">
                                                    <select name="payment_method" class="" id="payment_method" style="">
                                                        <option value="">Select Payment Type</option>
                                                        <option value="cod" selected>COD</option>
                                                        <option value="online">ONLINE</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-12">
                                                    <div class="add-to-cart mt-4">
                                                        <input type="hidden" name="product_id" id="product_id" value="{{ $product_detail->id }}">
                                                        <input type="hidden" name="product_name" id="product_name" value="{{ $product_detail->title }}">
                                                        <input type="hidden" name="slug" id="slug" value="{{ $product_detail->slug }}">
                                                        {{-- <input name="payment_method"  type="hidden" value="cod"> --}}
                                                        <input name="country"  type="hidden" value="IN">
							                            @if($product_detail->stock > 0)
                                                        	<button type="submit" class="btn btn-primary" id="button-submit">Submit Order</button>
							                            @endif
                                                        {{-- <a href="{{route('add-to-wishlist',$product_detail->slug)}}" class="btn min"><i class="ti-heart"></i></a> --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <span class="text-danger error-msg"> </span>
                                                </div>
                                            </div>
                                        </form>

                                        {{-- <p class="cat">Category :<a href="{{route('product-cat',$product_detail->cat_info['slug'])}}">{{$product_detail->cat_info['title']}}</a></p>
                                        @if($product_detail->sub_cat_info)
                                        <p class="cat mt-1">Sub Category :<a href="{{route('product-sub-cat',[$product_detail->cat_info['slug'],$product_detail->sub_cat_info['slug']])}}">{{$product_detail->sub_cat_info['title']}}</a></p>
                                        @endif --}}
                                        <!-- <p class="availability">Stock : @if($product_detail->stock>0)<span class="badge badge-success">{{$product_detail->stock}}</span>@else <span class="badge badge-danger">{{$product_detail->stock}}</span>  @endif</p> -->
                                    <p class="availability"> Stock:
                                        @if($product_detail->stock > 0)
                                            @if($product_detail->stock < 5)
                                                <span class="badge badge-warning">Low in stock</span>
                                            @else
                                                <span class="badge badge-success">Available</span>
                                            @endif
                                        @else
                                            <span class="badge badge-danger">Out of stock</span>
                                        @endif
                                    </p>

                                    </div>
                                    <!--/ End Product Buy -->
                                    <!-- Visit 'codeastro' for more projects -->
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product-info mb-4">
                                    <div class="nav-main">
                                        <!-- Tab Nav -->
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#description" role="tab">Description</a></li>
                                            {{-- <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reviews" role="tab">Reviews</a></li> --}}
                                        </ul>
                                        <!--/ End Tab Nav -->
                                    </div>
                                    <div class="tab-content" id="myTabContent">
                                        <!-- Description Tab -->
                                        <div class="tab-pane fade show active" id="description" role="tabpanel">
                                            <div class="tab-single">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="single-des">
                                                            <p>{!! ($product_detail->description) !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/ End Description Tab -->
                                        <!-- Reviews Tab -->
                                        {{-- <div class="tab-pane fade" id="reviews" role="tabpanel">
                                            <div class="tab-single review-panel">
                                                <div class="row">
                                                    <div class="col-12">

                                                        <!-- Review -->
                                                        <div class="comment-review">
                                                            <div class="add-review">
                                                                <h5>Add A Review</h5>
                                                                <p>Your email address will not be published. Required fields are marked</p>
                                                            </div>
                                                            <h4>Your Rating <span class="text-danger">*</span></h4>
                                                            <div class="review-inner">
                                                                    <!-- Form -->
                                                        @auth
                                                        <form class="form" method="post" action="{{route('review.store',$product_detail->slug)}}">
                                                            @csrf
                                                            <div class="row">
                                                                <div class="col-lg-12 col-12">
                                                                    <div class="rating_box">
                                                                            <div class="star-rating">
                                                                            <div class="star-rating__wrap">
                                                                                <input class="star-rating__input" id="star-rating-5" type="radio" name="rate" value="5">
                                                                                <label class="star-rating__ico fa fa-star-o" for="star-rating-5" title="5 out of 5 stars"></label>
                                                                                <input class="star-rating__input" id="star-rating-4" type="radio" name="rate" value="4">
                                                                                <label class="star-rating__ico fa fa-star-o" for="star-rating-4" title="4 out of 5 stars"></label>
                                                                                <input class="star-rating__input" id="star-rating-3" type="radio" name="rate" value="3">
                                                                                <label class="star-rating__ico fa fa-star-o" for="star-rating-3" title="3 out of 5 stars"></label>
                                                                                <input class="star-rating__input" id="star-rating-2" type="radio" name="rate" value="2">
                                                                                <label class="star-rating__ico fa fa-star-o" for="star-rating-2" title="2 out of 5 stars"></label>
                                                                                <input class="star-rating__input" id="star-rating-1" type="radio" name="rate" value="1">
                                                                                <label class="star-rating__ico fa fa-star-o" for="star-rating-1" title="1 out of 5 stars"></label>
                                                                                @error('rate')
                                                                                <span class="text-danger">{{$message}}</span>
                                                                                @enderror
                                                                            </div>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-12">
                                                                    <div class="form-group">
                                                                        <label>Write a review</label>
                                                                        <textarea name="review" rows="6" placeholder="" ></textarea>
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-12">
                                                                    <div class="form-group button5">
                                                                        <button type="submit" class="btn">Submit</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                        @else
                                                        <p class="text-center p-5">
                                                            You need to <a href="{{route('login.form')}}" style="color:rgb(54, 54, 204)">Login</a> OR <a style="color:blue" href="{{route('register.form')}}">Register</a>

                                                        </p>
                                                        <!--/ End Form -->
                                                        @endauth
                                                            </div>
                                                        </div>

                                                        <div class="ratting-main">
                                                            <div class="avg-ratting">

                                                                <h4>{{ceil($product_detail->getReview->avg('rate'))}} <span>(Overall)</span></h4>
                                                                <span>Based on {{$product_detail->getReview->count()}} Comments</span>
                                                            </div>
                                                            @foreach($product_detail['getReview'] as $data)
                                                            <!-- Single Rating -->
                                                            <div class="single-rating">
                                                                <div class="rating-author">
                                                                    @if($data->user_info['photo'])
                                                                    <img src="{{$data->user_info['photo']}}" alt="{{$data->user_info['photo']}}">
                                                                    @else
                                                                    <img src="{{asset('backend/img/avatar.png')}}" alt="Profile.jpg">
                                                                    @endif
                                                                </div>
                                                                <div class="rating-des">
                                                                    <h6>{{$data->user_info['name']}}</h6>
                                                                    <div class="ratings">

                                                                        <ul class="rating">
                                                                            @for($i=1; $i<=5; $i++)
                                                                                @if($data->rate>=$i)
                                                                                    <li><i class="fa fa-star"></i></li>
                                                                                @else
                                                                                    <li><i class="fa fa-star-o"></i></li>
                                                                                @endif
                                                                            @endfor
                                                                        </ul>
                                                                        <div class="rate-count">(<span>{{$data->rate}}</span>)</div>
                                                                    </div>
                                                                    <p>{{$data->review}}</p>
                                                                </div>
                                                            </div>
                                                            <!--/ End Single Rating -->
                                                            @endforeach
                                                        </div>

                                                        <!--/ End Review -->

                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
                                        <!--/ End Reviews Tab -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</section>
		<!--/ End Shop Single -->
		<!-- Visit 'codeastro' for more projects -->
		<!-- Start Most Popular -->
	{{-- <div class="product-area most-popular related-product section">
        <div class="container">
            <div class="row">
				<div class="col-12">
					<div class="section-title">
						<h2>Related Products</h2>
					</div>
				</div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="owl-carousel popular-slider">
                        @foreach($product_detail->rel_prods as $data)
                            @if($data->id !==$product_detail->id)
                                <!-- Start Single Product -->
                                <div class="single-product">
                                    <div class="product-img">
										<a href="{{route('product-detail',$data->slug)}}">
											@php
												$photo=explode(',',$data->photo);
											@endphp
                                            <img class="default-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                            <img class="hover-img" src="{{$photo[0]}}" alt="{{$photo[0]}}">
                                            <span class="price-dec">{{$data->discount}} % Off</span>
                                        </a>
                                        <div class="button-head">
                                            <div class="product-action">
                                                <a data-toggle="modal" data-target="#modelExample" title="Quick View" href="#"><i class=" ti-eye"></i><span>Quick Shop</span></a>
                                                <a title="Wishlist" href="#"><i class=" ti-heart "></i><span>Add to Wishlist</span></a>
                                                <a title="Compare" href="#"><i class="ti-bar-chart-alt"></i><span>Add to Compare</span></a>
                                            </div>
                                            <div class="product-action-2">
                                                <a title="Add to cart" href="#">Add to cart</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3><a href="{{route('product-detail',$data->slug)}}">{{$data->title}}</a></h3>
                                        <div class="product-price">
                                            @php
                                                $after_discount=($data->price-(($data->discount*$data->price)/100));
                                            @endphp
                                            <span class="old">${{number_format($data->price,2)}}</span>
                                            <span>${{number_format($after_discount,2)}}</span>
                                        </div>

                                    </div>
                                </div>
                                <!-- End Single Product -->
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
	<!-- End Most Popular Area -->


  <!-- Modal -->
  {{-- <div class="modal fade" id="modelExample" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span class="ti-close" aria-hidden="true"></span></button>
            </div>
            <div class="modal-body">
                <div class="row no-gutters">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <!-- Product Slider -->
                            <div class="product-gallery">
                                <div class="quickview-slider-active">
                                    <div class="single-slider">
                                        <img src="images/modal1.png" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal2.png" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal3.png" alt="#">
                                    </div>
                                    <div class="single-slider">
                                        <img src="images/modal4.png" alt="#">
                                    </div>
                                </div>
                            </div>
                        <!-- End Product slider -->
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="quickview-content">
                            <h2>Flared Shift Dress</h2>
                            <div class="quickview-ratting-review">
                                <div class="quickview-ratting-wrap">
                                    <div class="quickview-ratting">
                                        <i class="yellow fa fa-star"></i>
                                        <i class="yellow fa fa-star"></i>
                                        <i class="yellow fa fa-star"></i>
                                        <i class="yellow fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                    </div>
                                    <a href="#"> (1 customer review)</a>
                                </div>
                                <div class="quickview-stock">
                                    <span><i class="fa fa-check-circle-o"></i> in stock</span>
                                </div>
                            </div>
                            <h3>$29.00</h3>
                            <div class="quickview-peragraph">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Mollitia iste laborum ad impedit pariatur esse optio tempora sint ullam autem deleniti nam in quos qui nemo ipsum numquam.</p>
                            </div>
                            <div class="size">
                                <div class="row">
                                    <div class="col-lg-6 col-12">
                                        <h5 class="title">Size</h5>
                                        <select>
                                            <option selected="selected">s</option>
                                            <option>m</option>
                                            <option>l</option>
                                            <option>xl</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 col-12">
                                        <h5 class="title">Color</h5>
                                        <select>
                                            <option selected="selected">orange</option>
                                            <option>purple</option>
                                            <option>black</option>
                                            <option>pink</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="quantity">
                                <!-- Input Order -->
                                <div class="input-group">
                                    <div class="button minus">
                                        <button type="button" class="btn btn-primary btn-number" disabled="disabled" data-type="minus" data-field="quant[1]">
                                            <i class="ti-minus"></i>
                                        </button>
									</div>
                                    <input type="text" name="qty" class="input-number"  data-min="1" data-max="1000" value="1">
                                    <div class="button plus">
                                        <button type="button" class="btn btn-primary btn-number" data-type="plus" data-field="quant[1]">
                                            <i class="ti-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <!--/ End Input Order -->
                            </div>
                            <div class="add-to-cart">
                                <a href="#" class="btn">Add to cart</a>
                                <a href="#" class="btn min"><i class="ti-heart"></i></a>
                                <a href="#" class="btn min"><i class="fa fa-compress"></i></a>
                            </div>
                            <div class="default-social">
                                <h4 class="share-now">Share:</h4>
                                <ul>
                                    <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                                    <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                                    <li><a class="youtube" href="#"><i class="fa fa-pinterest-p"></i></a></li>
                                    <li><a class="dribbble" href="#"><i class="fa fa-google-plus"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> --}}
<!-- Modal end -->

@endsection
@push('styles')
	<style>
		/* Rating */
		.rating_box {
		display: inline-flex;
		}

		.star-rating {
		font-size: 0;
		padding-left: 10px;
		padding-right: 10px;
		}

		.star-rating__wrap {
		display: inline-block;
		font-size: 1rem;
		}

		.star-rating__wrap:after {
		content: "";
		display: table;
		clear: both;
		}

		.star-rating__ico {
		float: right;
		padding-left: 2px;
		cursor: pointer;
		color: #F7941D;
		font-size: 16px;
		margin-top: 5px;
		}

		.star-rating__ico:last-child {
		padding-left: 0;
		}

		.star-rating__input {
		display: none;
		}

		.star-rating__ico:hover:before,
		.star-rating__ico:hover ~ .star-rating__ico:before,
		.star-rating__input:checked ~ .star-rating__ico:before {
		content: "\F005";
		}

	</style>
@endpush
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    {{-- <script>
        $('.cart').click(function(){
            var quantity=$('#quantity').val();
            var pro_id=$(this).data('id');
            // alert(quantity);
            $.ajax({
                url:"{{route('add-to-cart')}}",
                type:"POST",
                data:{
                    _token:"{{csrf_token()}}",
                    quantity:quantity,
                    pro_id:pro_id
                },
                success:function(response){
                    console.log(response);
					if(typeof(response)!='object'){
						response=$.parseJSON(response);
					}
					if(response.status){
						swal('success',response.msg,'success').then(function(){
							document.location.href=document.location.href;
						});
					}
					else{
                        swal('error',response.msg,'error').then(function(){
							document.location.href=document.location.href;
						});
                    }
                }
            })
        });
    </script> --}}

<script>
    $(document).ready(function() {

        $('#button-submit').click(function(e) {
           // e.preventDefault();
           var first_name = $('#first_name').val();
            var pattern = /^[a-zA-Z\s]+$/;
            if (!pattern.test(first_name) || first_name == '')
            {
               $(".error-msg").html("Name is required Field & Special Characters & Numbers are not allowed!");
               return false;
            }
            var mobileNumber = $('#phone').val().trim();
            var pattern = /^[1-9]\d{9}$/;
            if (!pattern.test(mobileNumber)) {
               $(".error-msg").html("Invalid mobile number! Please enter a 10-digit number not starting with 0.");
               return false;
            }
            var address1 = $('#address1').val().trim();
            if (address1 == '')
            {
               $(".error-msg").html("Address is required field!");
               return false;
            }
            var shipping = $('#shipping').val().trim();
            if (shipping == '')
            {
               $(".error-msg").html("Shipping is required field!");
               return false;
            }

            var quantity = $('#quantity').val().trim();
            if (quantity == '')
            {
               $(".error-msg").html("Quantity is required field!");
               return false;
            }

            var product = {
            id: $('#product_id').val(),
            name: $('#product_name').val(),
            slug: $('#slug').val(),
            quantity: quantity,
            price: parseFloat("{{ $product_detail->discount_price }}"), // Use the discounted price
            timestamp: new Date().toISOString()
            };
    
            var cart = JSON.parse(localStorage.getItem('checkout_cart')) || [];
            cart.push(product);
            localStorage.setItem('checkout_cart', JSON.stringify(cart));
                return true;


            // Send AJAX request to backend route to send email
       var orderData = {
            name: first_name,
            email: email,
            items: cart
        };

        // Send AJAX request
        $.ajax({
            url: "{{ route('checkout.sendEmail') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                ...orderData
            },
            success: function(response) {
                if (response.status === 'success') {
                    swal("Success!", response.message, "success").then(() => {
                        localStorage.removeItem('checkout_cart'); // clear cart
                        location.reload();
                    });
                } else {
                    swal("Error!", response.message || "Email not sent!", "error");
                }
            },
            error: function(xhr) {
                swal("Error!", xhr.responseJSON?.message || "Something went wrong!", "error");
            }
        });
        });

    });

//Save missing records
// document.addEventListener('DOMContentLoaded', function() {

//     window.addEventListener('beforeunload', function (e) {
//             // Cancel the event as the default action
//             e.preventDefault();
//             // Chrome requires returnValue to be set
//             e.returnValue = '';
//             // Prompt the user with a message
//             var formData = {
//                                 product_id: document.getElementById('product_id').value,
//                                 product_name: document.getElementById('product_name').value,
//                                 name: document.getElementById('first_name').value,
//                                 phone: document.getElementById('phone').value,
//                                 city: document.getElementById('shipping_id').value,
//                             };

//             return 'Are you sure you want to leave? Your changes may not be saved.';
//         });

// });


    $(document).ready(function() {
        $('#first_name, #phone').on('blur', function(e) {
          //  alert();
          var formData = {
                            product_id: $('#product_id').val(),
                            product_name: $('#product_name').val(),
                            name: $('#first_name').val(),
                            phone: $('#phone').val(),
                            city: $('#shipping_id').val(),
                        } ;
        if(formData['name'] != '' && formData['phone'])
        {
            var pattern = /^[1-9]\d{8}$/;
            if (pattern.test(formData['phone']))
            {
                console.log(formData['name'] + "====" + formData['phone']);
                $.ajax({
                    url:"{{route('order.addMissingOrder')}}",
                    type:"POST",
                    data:{
                            _token:"{{csrf_token()}}",
                            product_id:formData['product_id'],
                            product_name:formData['product_name'],
                            name:formData['name'],
                            phone:formData['phone'],
                            city:formData['city']
                        },
                    success:function(response){
                        console.log(response);
                    }
                });
            }
        }
    });
});



</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const paymentSelect = document.getElementById("payment_method");
    const orderForm = document.getElementById("order-form");
    const errorMsg = document.querySelector(".error-msg");

    orderForm.addEventListener("submit", function(e) {
        if(paymentSelect.value === "online") {
            e.preventDefault(); // stop form submission
            errorMsg.textContent = "Online payment is coming soon! Please choose COD.";
        } else {
            errorMsg.textContent = ""; // clear error if COD is selected
        }
    });
});
</script>

@endpush
