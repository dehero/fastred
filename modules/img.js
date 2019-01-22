window.imgSizeGetResized = function(width, height, widthMax, heightMax) {
	if (varIsNotEmpty(widthMax) && varIsNotEmpty(heightMax) && (width > widthMax || height > heightMax)) {		
		height = Math.round(widthMax / width * height);

		if (height > heightMax) {
			width = Math.round(heightMax / height * widthMax);
			height = heightMax;
		} else {
			width = widthMax;
		}
	} else if (varIsNotEmpty(widthMax) && width > widthMax) {
		height = Math.round(widthMax / width * height);
		width = widthMax;
	} else if (varIsNotEmpty(heightMax) && height > heightMax) {
		width = Math.round(heightMax / height * width);
		height = heightMax;
	}
    return [width, height];
}
