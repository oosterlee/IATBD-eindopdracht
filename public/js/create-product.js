let formData = new FormData();
window.addEventListener('load', function() {
	const logoutBtn = document.querySelector('.js--logout');
	if (logoutBtn) {
		logoutBtn.addEventListener('click', function(e) {
			e.preventDefault();
			this.closest('form').submit();
		});
	}

	document.querySelector('.form__image--add .form__input').addEventListener('change', function(e) {
		addNewImage(e);
	});

	document.querySelector('.form__categories__input .form__input').addEventListener('keydown', function(e) {
		if (e.keyCode == 13) {
			e.preventDefault();
			addCategory(e.target.value.trim(), e.target);
		}
	});

	document.querySelector('.form__categories__input .form__input').addEventListener('blur', function(e) {
		if (e.target.value.trim() != "") {
			addCategory(e.target.value.trim(), e.target);
		}
	});

	// document.querySelector('.form__categories__input .form__input').addEventListener('keyup', function(e) {
	// 	if (e.keyCode == 13) console.log("Enter!");
	// 	e.preventDefault();
	// 	console.log(e);
	// });

	document.querySelector('.form').addEventListener('submit', onSubmit);
});

function addCategory(category, target) {
	console.log("Add category", category);
	const form = document.querySelector('.form');
	const input = document.createElement('input');

	input.setAttribute('type', "hidden");
	input.setAttribute('name', "categories[]");
	input.setAttribute('value', category);

	const categories = document.querySelector('.form__categories');

	const lc = newListCategory(category);

	lc.querySelector('.form__category__close').addEventListener('click', e => removeCategory(e, input));

	categories.insertBefore(lc, categories.children[categories.children.length-1]);
	target.value = "";

	form.appendChild(input);
}

function removeCategory(event, input) {
	// let formCategories = formData.getAll('categories[]');
	// console.log(formCategories);
	// formData.delete('categories[]');
	// formCategories.splice(formCategories.indexOf(category), 1);
	// formCategories.forEach(item => formData.append('categories[]', item, item.name));
	// console.log(formData.getAll('categories[]'));
	input.remove();
	event.target.parentElement.remove();
}

function newListCategory(text) {
	// <li class="form__categories__item">
	// 	<p class="form__category">test</p>
	// 	<i class="form__category__close fal fa-times"></i>
	// </li>
	const li = document.createElement('li');
	const category = document.createElement('p');
	const icon = document.createElement('i');

	li.classList.add("form__categories__item");

	category.classList.add("form__category");

	category.innerText = text;

	icon.classList.add("form__category__close");
	icon.classList.add("fal");
	icon.classList.add("fa-times");

	li.appendChild(category);
	li.appendChild(icon);

	return li;
}

function onSubmit(e) {
	e.preventDefault();
	console.log(e);
	const form = e.target;
	let req = new XMLHttpRequest();
	req.addEventListener('load', function(e) {
		console.log(e);

		if (new URL(e.target.responseURL).pathname.includes('product/')) {
			// return window.location.href = e.target.responseURL;
			console.log("window.location.href =", e.target.responseURL);
		}
	});
	req.addEventListener('error', function(e) {
		console.error(e);
	});


	req.open(form.method, form.action);
	// if (form.enctype) {
		// console.log('Content-Type', form.enctype);
		// req.setRequestHeader('Content-Type', form.enctype);
	// }

	const inputs = form.querySelectorAll('input, textarea, select');
	console.log(inputs);
	formData.delete('categories[]');

	for (let i = 0; i < inputs.length; i++) {
		if (inputs[i].type == "file") continue;
		console.log(inputs[i].name, inputs[i].value);
		formData.append(inputs[i].name, inputs[i].value);
	}
	console.log('POST', form.action);
	req.send(formData);

	console.log(formData);
}

function addNewImage(e) {
	if (!e.target) return;

	const files = e.target.files;
	const ul = document.querySelector('.form__images');

	for (let i = 0; i < files.length; i++) {
		const fileReader = new FileReader();
		fileReader.addEventListener('load', (function(file) {
			const nli = newListImage();
			console.log(this, file);
			nli.style.backgroundImage = 'url(' + this.result + ')';
			ul.insertBefore(nli, ul.children[ul.children.length-1]); // Insert before last item
			nli.querySelector('.form__image__close').addEventListener('click', e => removeImage(e, file));
			console.log(formData);
			formData.append('image[]', file, file.name);
			// console.log(nli.querySelector('input').files);
			// nli.querySelector('input').files[0] = file;
		}).bind(fileReader, files[i]));

		fileReader.readAsDataURL(files[i]);
	}

}

function removeImage(event, file) {
	let formImages = formData.getAll('image[]');
	console.log(formImages);
	formData.delete('image[]');
	formImages.splice(formImages.indexOf(file), 1);
	formImages.forEach(item => formData.append('image[]', item, item.name));
	console.log(formData.getAll('image[]'));
	event.target.parentElement.remove();
}

function newListImage() {
	// <li class="form__image">
	// 	<input type="hidden" name="image[]" accept="image/*">
	// 	<i class="form__image__close fal fa-times"></i>
	// </li>
	const li = document.createElement('li');
	// const input = document.createElement('input');
	const icon = document.createElement('i');

	li.classList.add('form__image');

	// input.setAttribute('type', "hidden");
	// input.setAttribute('name', "image[]");

	icon.classList.add('form__image__close');
	icon.classList.add('fal');
	icon.classList.add('fa-times');

	// li.appendChild(input);
	li.appendChild(icon);

	return li;
}