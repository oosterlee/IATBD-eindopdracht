<header>
	<nav class="nav">
		<ul class="nav__items">
			<li class="nav__item nav__item--left {{ Request::path() == '/' ? 'active' : '' }}">
				<a class="nav__link" href="/">Time2Share</a>
			</li>
			<li class="nav__item {{ Request::path() == 'products' ? 'active' : '' }}">
				<a class="nav__link" href="/products">Products</a>
			</li>
			@auth
			<li class="nav__item {{ Request::path() == 'products/' . auth()->user()->id ? 'active' : '' }}">
				<a class="nav__link" href="/products/{{ auth()->user()->id }}">My Products</a>
			</li>
			<li class="nav__item {{ Request::path() == 'product/create' ? 'active' : '' }}">
				<a class="nav__link" href="/product/create">Add new product</a>
			</li>
			<li class="nav__item nav__item--right">
				<form method="POST" action="/logout">
					@csrf
					<a class="nav__link js--logout" href="/login">Logout</a>
				</form>
			</li>
			@endauth
			@guest
			<li class="nav__item nav__item--right">
				<a class="nav__link" href="/login">Login</a>
			</li>
			@endguest
		</ul>
	</nav>
</header>