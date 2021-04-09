let category = null;
let search = null;

window.addEventListener('load', function() {
	const categorySelect = document.querySelector("#categories");
	const searchInput = document.querySelector("#search");
	const form = document.querySelector('.form.form--flex');
	if (!form) return;
	form.addEventListener('submit', function(e) {
		e.preventDefault();
	});

	categorySelect.addEventListener('change', function(e) {
		if (e.target.selectedIndex == 0) category = null;
		else category = e.target.options[e.target.selectedIndex].value;
		filter(search, category);
	});

	searchInput.addEventListener('keydown', function(e) {
		if (e.target.value == "") search = null;
		else search = e.target.value;
		filter(search, category);
	});
	searchInput.addEventListener('keyup', function(e) {
		if (e.target.value == "") search = null;
		else search = e.target.value;
		filter(search, category);
	});
});

function filter(search, category) {
	const products = document.querySelectorAll('.products__item');
	products.forEach(function(product) {
		product.style.display = "flex";
	});

	products.forEach(function(product) {
		if (search !== null && !product.innerText.toLowerCase().includes(search.toLowerCase())) product.style.display = "none";
		if (category !== null && !product.getAttribute('data-categories').includes(category)) product.style.display = "none";
	});
}