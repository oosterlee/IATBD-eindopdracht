@extends('default')

@if(isset($user))
@section('title', 'Products of ' . $user->name)
@else
@section('title', 'Products')
@endif
@section('head-extras')
<script src="/js/search.js"></script>
<script src="/js/logoutBtn.js"></script>
@endsection

@section('header')
@include('layouts.product.navigation')
@endsection

@section('content')
<main class="main main--center main--mw100r">
	@if(isset($user))
	<h1 class="main__header">Products of {{ $user->name }} ({{ $user->email }})</h1>
	@if(auth()->check() && auth()->user()->is_admin)
	<a href="/users/{{ $user->id }}/{{ $user->blocked ? 'unblock' : 'block' }}">{{ $user->blocked ? "Unblock" : "Block" }} user</a>
	@endif
	@else
	<h1 class="main__header">Products</h1>
	@endif
	<section class="filters">
		@if(!$products->isEmpty())
		<form action="/products" method="POST" class="form form--flex">
			@csrf
			<div class="form__field form__field--fg1 form__field--fb1 form__field--w100 form__field--m1">
				<label for="search">Search</label>
				<input type="text" name="search" id="search" class="form__input form__input--w100">
			</div>
			<div class="form__field form__field--fg1 form__field--fb1 form__field--w100 form__field--m1">
				<label for="categories">Categories</label>
				<select id="categories" class="form__select">
					<option value="">Choose</option>
					@php
						$categories = [];

						foreach ($products as $p) {
							foreach ($p->categories as $c) {
								array_push($categories, $c->category);
							}
						}

						$categories = array_unique($categories);
					@endphp
					@foreach($categories as $category)
						<option value="{{$category}}">{{$category}}</option>
					@endforeach
				</select>
			</div>
		</form>
		@endif
	</section>
	<section class="products">
		<ul class="products__list">
			@if($products->isEmpty())
			<li class="products__item products__item__center">
				<h3>No products available!</h3>
			</li>
			@endif
			@foreach($products as $product)
			@if($product->listed)
			@if($product->user->blocked)
			@continue
			@endif
				<li class="products__item" data-product-name="{{$product->name}}" data-categories="@foreach($product->categories as $c){{$c->category.($loop->last ? '' : ',')}}@endforeach">
					<figure class="products__list__figure">
						@if($product->images->isEmpty())
						<img class="products__list__img" src="https://shahidafridifoundation.org/wp-content/uploads/2020/06/no-preview.jpg" alt="Default product image">
						@else
						<img class="products__list__img" src="/image/{{ $product->images[0]->id }}" alt="Image of {{ $product->name }}">
						@endif
					</figure>
					<div class="products__list__column">
						<h3 class="products__list__text products__list__text--vc">{{ $product->name }}</h3>
					</div>
					<div class="products__list__column">
						<p>{{ strlen($product->description) > 80 ? substr($product->description,0,80)."..." : $product->description }}</p>
					</div>
					<div class="products__list__column products__list__column--frr">
						<a href="/product/{{ $product->id }}" class="products__column__button button">View</a>
						@if($product->is_available())
						<a href="/product/{{ $product->id }}/loan" class="products__column__button button">Loan</a>
						@endif
					</div>
					@if(auth()->check() && auth()->user()->is_admin)
					<div class="products__list__column products__list__column--frr">
						<div class="admin__actions">
							<a href="/product/{{ $product->id }}/remove" class="">Remove</a>
							<a href="/product/{{ $product->id }}/edit" class="">Edit</a>
						</div>
					</div>
					@endif
				</li>
			@endif
			@endforeach
		</ul>
		@if(isset($loaned_products) && auth()->user()->id == $user->id)
		<h2>Products i loaned</h2>
		<ul class="products__list">
			@if($loaned_products->isEmpty())
			<li class="products__item products__item__center">
				<h3>No products available!</h3>
			</li>
			@endif
			@foreach($loaned_products as $productLoan)
			<?php
				$product = $productLoan->product;
			?>
			@if ($productLoan->returned)
			@continue
			@endif
			@if($product->listed)
				<li class="products__item" data-product-name="{{$product->name}}" data-categories="@foreach($product->categories as $c){{$c->category.($loop->last ? '' : ',')}}@endforeach">
					<figure class="products__list__figure">
						@if($product->images->isEmpty())
						<img class="products__list__img" src="https://shahidafridifoundation.org/wp-content/uploads/2020/06/no-preview.jpg" alt="Default product image">
						@else
						<img class="products__list__img" src="/image/{{ $product->images[0]->id }}" alt="Image of {{ $product->name }}">
						@endif
					</figure>
					<div class="products__list__column">
						<h3 class="products__list__text products__list__text--vc">{{ $product->name }}</h3>
					</div>
					<div class="products__list__column">
						<p>{{ strlen($product->description) > 80 ? substr($product->description,0,80)."..." : $product->description }}</p>
					</div>
					<div class="products__list__column products__list__column--frr">
						<a href="/product/{{ $product->id }}" class="products__column__button button">View</a>
						@if($product->is_available())
						<a href="/product/{{ $product->id }}/loan" class="products__column__button button">Loan</a>
						@endif
					</div>
					<div class="products__list__column products__list__column--frr">
						<p>Loaned from <a href="/products/{{ $product->user->id }}">{{ $product->user->name }}</a></p>
					</div>
					<div class="products__list__column products__list__column--frr">
						{{ date_diff(date_create(date($productLoan->loanDate)), date_create(date($productLoan->deadline)))->format('%d') }} day{{ date_diff(date_create(date($productLoan->loanDate)), date_create(date($productLoan->deadline)))->format('%d') == 1 ? '' : 's' }} left
					</div>
					@if(auth()->check() && auth()->user()->is_admin)
					<div class="products__list__column products__list__column--frr">
						<div class="admin__actions">
							<a href="/product/{{ $product->id }}/remove" class="">Remove</a>
							<a href="/product/{{ $product->id }}/edit" class="">Edit</a>
						</div>
					</div>
					@endif
				</li>
			@endif
			@endforeach
		</ul>
		@endif
		@if(isset($products_loaned_from_me) && auth()->user()->id == $user->id)
		<?php
			$plist = [];
		?>
		<h2>Products loaned from me</h2>
		<ul class="products__list">
			@if($products_loaned_from_me->isEmpty() || sizeof($products_loaned_from_me->first()->loans) <= 0)
			<li class="products__item products__item__center">
				<h3>No products available!</h3>
			</li>
			@endif
			@foreach($products_loaned_from_me as $product)
			<?php
				$loans = $product->loans;
			?>
			@if ($loans->isEmpty())
				@continue
			@endif
			<?php
				$loan = $product->loans->last();
			?>
			@if($product->listed)
				<li class="products__item" data-product-name="{{$product->name}}" data-categories="@foreach($product->categories as $c){{$c->category.($loop->last ? '' : ',')}}@endforeach">
					<figure class="products__list__figure">
						@if($product->images->isEmpty())
						<img class="products__list__img" src="https://shahidafridifoundation.org/wp-content/uploads/2020/06/no-preview.jpg" alt="Default product image">
						@else
						<img class="products__list__img" src="/image/{{ $product->images[0]->id }}" alt="Image of {{ $product->name }}">
						@endif
					</figure>
					<div class="products__list__column">
						<h3 class="products__list__text products__list__text--vc">{{ $product->name }}</h3>
					</div>
					<div class="products__list__column">
						<p>{{ strlen($product->description) > 80 ? substr($product->description,0,80)."..." : $product->description }}</p>
					</div>
					<div class="products__list__column products__list__column--frr">
						<a href="/product/{{ $product->id }}" class="products__column__button button">View</a>
						@if(!$product->loans->isEmpty() && !$product->loans->last()->returned)
						<a href="/product/{{ $product->id }}/returned" class="products__column__button button">Received</a>
						@else
						<p>Returned</p>
						@endif
					</div>
					<div class="products__list__column products__list__column--frr">
						<p>Loaned by <a href="/products/{{ $loan->user->id }}">{{ $loan->user->name }}</a></p>
					</div>
					@if(auth()->check() && auth()->user()->is_admin)
					<div class="products__list__column products__list__column--frr">
						<div class="admin__actions">
							<a href="/product/{{ $product->id }}/remove" class="">Remove</a>
							<a href="/product/{{ $product->id }}/edit" class="">Edit</a>
						</div>
					</div>
					@endif
				</li>
			@endif
			@endforeach
		</ul>
		@endif
	</section>
</main>
@endsection