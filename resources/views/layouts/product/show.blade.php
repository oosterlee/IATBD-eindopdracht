@extends('default')

@section('title', $product->name)

@section('header')
@include('layouts.product.navigation')
<script src="/js/logoutBtn.js"></script>
<script src="/js/review.js"></script>
<script src="/js/image-carousel.js"></script>
@endsection

@section('content')
<main class="main main--center main--mw100r">
<!-- @if ($errors->any())
	    <div class="alert alert--danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li class="alert__message">{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif -->

	<section class="product">
		<section class="product__images-wrapper">
			<figure class="product__image-wrapper">
				@if($images->isEmpty())
				<img class="product__image" src="https://shahidafridifoundation.org/wp-content/uploads/2020/06/no-preview.jpg" alt="{{ $product->name }}">
				@else
				<img class="product__image" src="/image/{{ $images[0]->id }}" alt="{{ $product->name }}">
				@endif
			</figure>
			<ul class="product__images">
				@foreach($images as $img)

					<li class="product__image-item">
						<img class="product__image-item__image" src="/image/{{ $img->id }}" alt="{{ $product->name }}">
					</li>

				@endforeach
			</ul>
		</section>
		<section class="product__content">
			<h1 class="product__header">{{ $product->name }}</h1>
			<p class="product__stars">
				<?php
					$avg = round($reviews->avg('stars'))
				?>
				<i class="far fa-star product__star{{ $avg >= 1 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $avg >= 2 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $avg >= 3 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $avg >= 4 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $avg >= 5 ? ' star--yellow' : '' }}"></i>
			</p>
			<a href="/products/{{ $user->id }}" class="product__user">{{ $user->name }}</a>
			<p class="product__description">{{ $product->description }}</p>
			@auth
			<a class="product__loan button button-w100" href="/product/{{ $product->id }}/loan" data-days-to-loan="{{ $product->days_to_loan }} Days" {{ $product->is_available() ? '' : 'data-disabled' }}>Loan</a>
			@endauth
			@guest
			<a class="product__loan button button-w100" href="/login?_r=/product/{{ $product->id }}/loan" data-days-to-loan="{{ $product->days_to_loan }} Days" {{ $product->is_available() ? '' : 'data-disabled' }}>Loan</a>
			@endguest
		</section>
		
	</section>
</main>
@endsection

@section('footer')
<footer class="footer">
	<h2>Reviews</h2>
	<ul class="reviews">
		@auth
		<li class="review">
			<form class="review__form" method="POST" action="/review/create">
				@csrf
				<input class="review__input review__input--star" type="checkbox" name="star[]" id="star1">
				<label class="review__input__label review__input__label--star" for="star1"></label>
				<input class="review__input review__input--star" type="checkbox" name="star[]" id="star2">
				<label class="review__input__label review__input__label--star" for="star2"></label>
				<input class="review__input review__input--star" type="checkbox" name="star[]" id="star3">
				<label class="review__input__label review__input__label--star" for="star3"></label>
				<input class="review__input review__input--star" type="checkbox" name="star[]" id="star4">
				<label class="review__input__label review__input__label--star" for="star4"></label>
				<input class="review__input review__input--star" type="checkbox" name="star[]" id="star5">
				<label class="review__input__label review__input__label--star" for="star5"></label>

				<textarea name="text" class="review__textarea"></textarea>
				<input type="submit" name="submit" class="review__input button">

				<input type="hidden" name="productid" value="{{ $product->id }}">
			</form>
		</li>
		@endauth
		@guest
		<li class="review">
			<p><a href="/login?_r=/{{ Request::path() }}" class="login__link">Login</a> to place a review.</p>
		</li>
		@endguest

		@forelse ($reviews as $review)

		<li class="review">
			<p class="review__stars">
				<i class="far fa-star product__star{{ $review->stars >= 1 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $review->stars >= 2 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $review->stars >= 3 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $review->stars >= 4 ? ' star--yellow' : '' }}"></i>
				<i class="far fa-star product__star{{ $review->stars >= 5 ? ' star--yellow' : '' }}"></i>
			</p>
			<p class="review__text">
				{{$review->text}}
			</p>
			<p class="review__user">By <span>{{$review->user->name}}</span></p>
			@if(auth()->check() && auth()->user()->is_admin)
			<div class="admin__actions">
				<a href="/review/{{ $review->id }}/remove">Remove</a>
			</div>
			@endif
		</li>

		@empty

		<li class="review">
			<p>There are no reviews. Be the first one!</p>
		</li>

		@endforelse
		<!-- <li class="review">
			<p class="review__stars">
				<i class="far fa-star product__star star--yellow"></i>
				<i class="far fa-star product__star star--yellow"></i>
				<i class="far fa-star product__star star--yellow"></i>
				<i class="far fa-star product__star star--yellow"></i>
				<i class="far fa-star product__star star--yellow"></i>
			</p>
			<p class="review__text">
				Dit is mijn review over dit product, geweldig product!
			</p>
			<p class="review__user">By <span>test</span></p>
		</li>

		<li class="review">
			<p class="review__stars">
				<i class="far fa-star review__star star--yellow"></i>
				<i class="far fa-star review__star star--yellow"></i>
				<i class="far fa-star review__star star--yellow"></i>
				<i class="far fa-star review__star star--yellow"></i>
				<i class="far fa-star review__star"></i>
			</p>
			<p class="review__text">
				Dit is mijn review over dit product, geweldig product! De verkoper was ook super!
			</p>
			<p class="review__user">By <span>test</span></p>
		</li> -->
	</ul>
</footer>
@endsection