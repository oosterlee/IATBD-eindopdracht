@extends('default')

@section('title', 'Create product')

@section('head-extras')
<script src="/js/create-product.js"></script>
@endsection

@section('header')
@include('layouts.product.navigation')
@endsection

@section('content')
<main class="main main--center">
<!-- @if ($errors->any())
	    <div class="alert alert--danger">
	        <ul>
	            @foreach ($errors->all() as $error)
	                <li class="alert__message">{{ $error }}</li>
	            @endforeach
	        </ul>
	    </div>
	@endif -->
	<section class="create-product">
		<h1 class="create-product__header">Add new product</h1>
		<section class="create-product__alert create-product__alert--danger">
			<h2>Oops! Something went wrong.</h2>
			<ul>
				<li>Erreur!</li>
			</ul>
		</section>
		<form method="post" action="/product/create" id="productform" enctype="multipart/form-data" class="form">
			@csrf
	<!-- https://stackoverflow.com/questions/8524087/whats-the-best-method-to-semantically-structure-a-form
	comment: https://stackoverflow.com/a/8524863 -->
			<div class="form__field form__field--inline-block form__field--w50 form__field--va-t">
				<label class="form__label" for="name" data-required>Product name</label>
				<input class="form__input form__input--w100" type="text" id="name" name="name" value="" placeholder="My Product" required>
			</div>
			<!-- Categories as tags -->
			<div class="form__field form__field--inline-block form__field--w50">
				<label class="form__label" for="_categories" data-required>Categories</label>
				<ul class="form__categories form__categories--w100">
					<!-- <li class="form__categories__item">
						<p class="form__category">test</p>
						<i class="form__category__close fal fa-times"></i>
					</li> -->
					<li class="form__categories__input">
						<input class="form__input" type="text" name="_categories" id="_categories" value="" size="1" placeholder="Electronics">
					</li>
				</ul>
			</div>
			<!-- Hidden Categories here -->
			<!-- 
			<input  type="hidden" name="categories[]">
			 -->

			<!-- Description -->
			<div class="form__field">
				<label class="form__label" for="description" data-required>Description</label>
				<textarea class="form__textarea" id="description" name="description"></textarea>
			</div>
			<!-- <input type="text" name="description" id="description" value="dit is een test beschrijving"> -->
			<div class="form__field form__field--w33 form__field--inline-block">
				<label class="form__label" for="listed">Visibility:</label>
				<select class="form__select" name="listed" id="listed" form="productform">
					<option class="form__option" value="1">
						Listed
					</option>
					<option class="form__option" value="0">
						Unlisted
					</option>
				</select>
			</div>
			<div class="form__field form__field--w33 form__field--inline-block">
				<label class="form__label" for="available">Is product available:</label>
				<select class="form__select" name="available" id="available" form="productform">
					<option class="form__option" value="1">
						Yes
					</option>
					<option class="form__option" value="0">
						No
					</option>
				</select>
			</div>
			<div class="form__field form__field--w33 form__field--inline-block">
				<label class="form__label" for="days">Days to loan</label>
				<input type="number" class="form__input form__input--w100" name="days_to_loan" step="1" value="2" min="1" max="365" id="days">
			</div>

			<div class="form__field">
				<ul class="form__images">
					<!-- Images here -->
					 <!-- <li class="form__image">
						<img src="" class="form__image__item">
						<i class="form__image__close fal fa-times"></i>
					</li> -->
					<li class="form__image form__image--add">
						<label class="form__label form__label--image" for="image">Upload image</label>
						<input class="form__input" type="file" name="_img" accept="image/*" id="image" multiple>
					</li>	
				</ul>
			</div>


			<input class="form__submit" type="submit" name="submit">
			<a href="/" class="form__cancel">Cancel</a>
		</form>
	</section>
</main>
@endsection