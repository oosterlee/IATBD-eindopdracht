window.addEventListener('load', function() {
	const logoutBtn = document.querySelector('.js--logout');
	if (logoutBtn) {
		logoutBtn.addEventListener('click', function(e) {
			e.preventDefault();
			this.closest('form').submit();
		});
	}
});