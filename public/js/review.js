let stars;
window.addEventListener('load', function() {
	console.log("LOADED review.js");

	stars = document.querySelectorAll('.review__input--star');
	console.log(stars);
	stars.forEach(function(star) {
		star.addEventListener('click', clickedStar.bind(star, [...stars].indexOf(star)));
	});
});

function clickedStar(index) {
	console.log(index);
	stars.forEach(function(star) {
		if ([...stars].indexOf(star) > index) {
			star.checked = false;
		} else {
			star.checked = true;
		}
	});
}