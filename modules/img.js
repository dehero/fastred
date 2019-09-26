'use strict';

exports.img = function(width, height, color) {
	var canvas = document.createElement('canvas');
	var context = canvas.getContext('2d');
	
    canvas.width = width;
	canvas.height = height;
	context.fillStyle = color;
    context.fillRect(0, 0, width, height);

	return canvas;
};

exports.imgExtToMimeType = function(ext) {
    switch(ext) {        
        case 'jpg':
        case 'jpeg':    return 'image/jpeg';        
        case 'gif':     return 'image/gif';
        default:        return 'image/png';
    }
};

exports.imgSizeGetResized = function(width, height, widthMax, heightMax) {
	fastredRequire('var');
	
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
};

exports.imgToUrl = function(img, mimeType, quality) {
	mimeType = mimeType || 'image/png';
	quality = quality || 100;

	return img.toDataURL(mimeType, quality);
};