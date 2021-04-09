window.addEventListener('load', function() {
	console.log("Edit-Product");

	if (_images) {
		_images.forEach(function(image) {
			loadXHR("/image/" + image).then(blob => {
				addNewImage({ target: { files: [blob] } });
			});
		});
	}

	if (_categories) {
		_categories.forEach(function(category) {
			addCategory(category, { target: { value: "" } });
		});
	}
});

function loadXHR(url) {

    return new Promise(function(resolve, reject) {
        try {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", url);
            xhr.responseType = "blob";
            xhr.onerror = function() {reject("Network error.")};
            xhr.onload = function() {
                if (xhr.status === 200) {resolve(xhr.response)}
                else {reject("Loading error:" + xhr.statusText)}
            };
            xhr.send();
        }
        catch(err) {reject(err.message)}
    });
}