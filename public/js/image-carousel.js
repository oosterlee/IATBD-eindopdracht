let carousel;
let activeImage;

window.addEventListener('load', function() {
	carousel = document.querySelector('.product__images-wrapper');
	activeImage = document.querySelector('.product__image-wrapper img');

	if (!carousel && !activeImage) return;
	const images = carousel.querySelectorAll('.product__images .product__image-item img');
	if (!images) return;
	images.forEach(function(image) {
		image.addEventListener('click', changeImage);
	});
});

function changeImage(e) {
	activeImage.src = e.target.src;
}