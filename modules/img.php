<?php

define('IMG_TYPES', 'jpeg|png|gif');

function img($width, $height, $ext = 'png', $red = 0, $green = 0, $blue = 0) {
    $img = @imagecreatetruecolor($width, $height);

    switch ($ext) {
        case 'gif':
            $color = @imagecolorallocate($img, $red, $green, $blue);
            @imagefill($img, 0, 0, $color);
            @imagecolortransparent($img, $color);
            break;
        case 'png':
            @imagesavealpha($img, true);
            $color = imagecolorallocatealpha($img, $red, $green, $blue, 127);
            @imagefill($img, 0, 0, $color);
            //@imagecolortransparent($img, $color);
            //@imagealphablending($img, false);
            break;
        default:
            $color = @imagecolorallocate($img, $red, $green, $blue);
            @imagefill($img, 0, 0, $color);
    }

    return $img;
}

function imgBlend($img, $imgOverlay, $options = null) {
    $options = (object)$options;
    $x = isset($options->percentX) ? round((imagesx($img) - imagesx($imgOverlay)) * $options->percentX / 100) : (int)$options->x;
    $y = isset($options->percentY) ? round((imagesy($img) - imagesy($imgOverlay)) * $options->percentY / 100) : (int)$options->y;
    $repeatX = isset($options->repeatX) ? $options->repeatX : false;
    $repeatY = isset($options->repeatY) ? $options->repeatY : false;
    $opacity = isset($options->opacity) ? $options->opacity : 100;

    if ($repeatX) while ($x > 0) $x -= imagesx($imgOverlay);
    if ($repeatY) while ($y > 0) $y -= imagesy($imgOverlay);

    $xCount = $repeatX ? ceil((-$x + imagesx($img)) / imagesx($imgOverlay)) : 1;
    $yCount = $repeatY ? ceil((-$y + imagesy($img)) / imagesy($imgOverlay)) : 1;

    $oldY = $y;
    for ($i = 0; $i < $xCount; $i++) {
        for ($j = 0; $j < $yCount; $j++) {
            if ($opacity == 100) {
                //@imagesavealpha($img, true);
                @imagecopy($img, $imgOverlay, $x, $y, 0, 0, imagesx($imgOverlay), imagesy($imgOverlay));
            } else {
                @imagecopymerge($img, $imgOverlay, $x, $y, 0, 0, imagesx($imgOverlay), imagesy($imgOverlay), $opacity);
            }
            $y += imagesy($imgOverlay);
        }
        $x += imagesx($imgOverlay);
        $y = $oldY;
    }

    return $img;
}

function imgBlendFile($file, $fileOverlay, $options, $fileOutput = null, $quality = 100) {
    $img = imgGetFile($file);
    $imgOverlay = imgGetFile($fileOverlay);
    imgBlend($img, $imgOverlay, $options);

    if (empty($fileOutput)) $fileOutput = $file;
    imgSave($img, $fileOutput, $quality);
    @imagedestroy($img);
    @imagedestroy($imgOverlay);

    return true;
}

function imgCrop(&$img, $width = null, $height = null, $x = null, $y = null) {
    if (!(isset($width) || isset($height)))
        return false;
    elseif (isset($width))
        $height = imagesy($img);
    elseif (isset($height))
        $width = imagesx($img);

    if (!is_integer($x)) $x = round((imagesx($img) - $width) / 2);
    if (!is_integer($y)) $y = round((imagesy($img) - $height) / 2);

    $img_output = img($width, $height);
    @imagecopy($img_output, $img, 0, 0, $x, $y, $width, $height);
    @imagedestroy($img);
    $img = $img_output;

    return array($width, $height);
}

function imgFill(&$img, $width, $height, $cropX = null, $cropY = null) {
    if ((imagesx($img) / imagesy($img)) > ($width / $height)) {
        imgResize($img, null, $height);
        imgCrop($img, $width, null, $cropX, $cropY);
    } else {
        imgResize($img, $width);
        imgCrop($img, null, $height, $cropX, $cropY);
    }
    return array($width, $height);
}

function imgFillFile($file, $width, $height, $fileOutput = null, $quality = 100) {
    $img = imgGetFile($file);
    imgFill($img, $width, $height);

    if (empty($fileOutput)) $fileOutput = $file;
    imgSave($img, $fileOutput, $quality);
    @imagedestroy($img);

    return true;
}

function imgGetBlended($img, $imgOverlay, $options = null) {
    $imgBlend = imgGetCopy($img);    
    imgBlend($imgBlend, $imgOverlay, $options);    
    return $imgBlend;
}

function imgGetCopy($img) {
    $imgCopy = img(imagesx($img), imagesy($img));
    @imagecopy($imgCopy, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
    return $imgCopy;
}

function imgGetTransparent($img, $percent = 0) {
    $imgOpacity = img(imagesx($img), imagesy($img));
    $white = imagecolorallocate($imgOpacity, 255, 255, 255);
    @imagecolortransparent($imgOpacity, $white);
    @imagefilledrectangle($imgOpacity, 0, 0, imagesx($img), imagesy($img), $white);
    @imagecopymerge($imgOpacity, $img, 0, 0, 0, 0, imagesx($img), imagesy($img), $percent);

    return $imgOpacity;
}

function imgGetFile($file, &$width = null, &$height = null, &$type = null) {
    list($width, $height, $type) = @getimagesize($file);
    $types = array('', 'gif', 'jpeg', 'png');
    $ext = $types[$type];

    $img = null;

    if ($ext) {
        $func = 'imagecreatefrom'.$ext;

        $img = @$func($file);

        if ($ext == 'png') {
            @imagesavealpha($img, true);
        }

        // Applying image rotation, saved in EXIF
        $exif = @exif_read_data($file);
        if(!empty($exif['Orientation'])) {
            switch($exif['Orientation']) {
                case 3:
                    $img = @imagerotate($img, 180, 0);
                    break;
                case 6:
                    $img = @imagerotate($img, -90, 0);
                    list($width, $height) = array($height, $width);
                    break;
                case 8:
                    $img = @imagerotate($img, 90, 0);
                    list($width, $height) = array($height, $width);
                    break;
            }
        }

    }

    return $img;
}

function imgSizeGetResized($width, $height, $widthMax = null, $heightMax = null) {
    if (!empty($widthMax) && !empty($heightMax) && ($width > $widthMax || $height > $heightMax)) {
        $height = round($widthMax / $width * $height);

        if ($height > $heightMax) {
            $width = round($heightMax / $height * $widthMax);
            $height = $heightMax;
        } else
            $width = $widthMax;
    } elseif (!empty($widthMax) && $width > $widthMax) {
        $height = round($widthMax / $width * $height);
        $width = $widthMax;
    }
    elseif (!empty($heightMax) && $height > $heightMax) {
        $width = round($heightMax / $height * $width);
        $height = $heightMax;
    }
    return array($width, $height);
}

function imgGetSize($file) {
    // Getting image size
    list($width, $height) = @getimagesize($file);

    // Applying image rotation, saved in EXIF
    $exif = @exif_read_data($file);
    if($exif['Orientation'] == 6 || $exif['Orientation'] == 8)
        list($width, $height) = array($height, $width);

    return array($width, $height);
}

function imgInclude(&$img, $width, $height) {
    $imgInclude = img($width, $height, 'png', 255, 255, 255);
    imgLimit($img, $width, $height);
    $img = imgGetBlended($imgInclude, $img, array('percentX' => 50, 'percentY' => 50));

    return array($width, $height);
}

function imgIncludeFile($file, $width, $height, $fileOutput = null, $quality = 100) {
    $img = imgGetFile($file);
    imgInclude($img, $width, $height);

    if (empty($fileOutput)) $fileOutput = $file;
    imgSave($img, $fileOutput, $quality);
    @imagedestroy($img);

    return true;
}

function imgSave($img, $file, $quality = 100) {
    $ext = pathinfo($file, PATHINFO_EXTENSION);

    if ($quality > 100) $quality = 100;

    switch ($ext) {
        case 'jpg':
        case 'jpeg':
            $func = 'imagejpeg';
            break;
        case 'gif':
            $func = 'imagegif';
            $quality = null;
            break;
        case 'png':
            $func = 'imagepng';
            $quality = round($quality / 100 * 9);
            break;
    }

    @mkdir(dirname($file), 0777, true);
    @$func($img, $file, $quality);
}

function imgLimit(&$img, $widthMax = null, $heightMax = null) {
    $width = imagesx($img);
    $height = imagesy($img);
    // Checking if image dimentions are lower, than max size
    if (($width < $widthMax || empty($widthMax)) && ($height < $heightMax || empty($heightMax))) {
        return array($width, $height);
    } else {
        return imgResize($img, $widthMax, $heightMax);
    }
}

function imgLimitFile($file, $widthMax = null, $heightMax = null, $fileOutput = null, $quality = 100) {
    // Checking if image dimentions are lower, than max size
    list($width, $height) = imgGetSize($file);
    if (($width < $widthMax || empty($widthMax)) && ($height < $heightMax || empty($heightMax))) {
        if (!empty($fileOutput) && $file != $fileOutput) copy($file, $fileOutput);
        return array($width, $height);
    }

    $img = imgGetFile($file);
    list($widthMax, $heightMax) = imgResize($img, $widthMax, $heightMax);

    if (empty($fileOutput)) $fileOutput = $file;
    imgSave($img, $fileOutput, $quality);
    @imagedestroy($img);

    return array($widthMax, $heightMax);
}

function imgResize(&$img, $widthMax = null, $heightMax = null) {
    if (!(isset($widthMax) || isset($heightMax))) return false;
    list($width, $height) = imgSizeGetResized(imagesx($img), imagesy($img), $widthMax, $heightMax);

    $imgOutput = img($width, $height);
    @imagecopyresampled($imgOutput, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
    @imagedestroy($img);
    $img = $imgOutput;

    return array($width, $height);
}

function imgResizeFile($file, $width_max = null, $height_max = null, $file_output = null, $quality = 100) {
    // Checking if image is already resized to that size
    list($width, $height) = imgGetSize($file);
    list($width_new, $height_new) = imgSizeGetResized($width, $height, $width_max, $height_max);
    if ($width == $width_new && $height == $height_new) {
        if (!empty($file_output) && $file != $file_output) copy($file, $file_output);
        return array($width, $height);
    }

    $img = imgGetFile($file);
    list($width_max, $height_max) = imgResize($img, $width_max, $height_max);

    if (empty($file_output)) $file_output = $file;
    imgSave($img, $file_output, $quality);
    @imagedestroy($img);

    return array($width_max, $height_max);
}

function imgSpriteFiles($files, $spriteFile, $width = null, $height = null, $method = null, $quality = 100) {
    $ext_sprite = pathinfo($spriteFile, PATHINFO_EXTENSION);

    $methods = array('resize', 'crop', 'fill', 'include', 'stretch');
    if (!empty($width) || !empty($height)) $method = $methods[array_search($method, $methods)];
    else $method = null;

    $width = intval($width);
    $height = intval($height);

    // Removing sprite file itself from the files array
    $files = array_diff($files, array($spriteFile));

    $fileWidth = 0;
    $fileHeight = 0;
    $spriteWidth = 0;
    $spriteHeight = 0;
    foreach ($files as $file) {
        list($fileWidth, $fileHeight) = imgGetSize($file);

        switch ($method) {
            case 'resize':
                list($fileWidth, $fileHeight) = imgSizeGetResized($fileWidth, $fileHeight, $width, $height);
                break;
            case 'crop':
            case 'fill':
            case 'include':
            case 'stretch':
                $fileWidth = $width;
                $fileHeight = $height;
        }

        $spriteWidth += $fileWidth;
        if ($fileHeight > $spriteHeight) $spriteHeight = $fileHeight;
    }

    $map = null;
    $img_sprite = img($spriteWidth, $spriteHeight, $ext_sprite);
    $offsetX = 0;
    $offsetY = 0;
    foreach ($files as $file) {
        $img = imgGetFile($file, $fileWidth, $fileHeight);

        if (!empty($method)) {
            $func = 'img' . $method;
            list($fileWidth, $fileHeight) = @$func($img, $width, $height);
        }

        @imagecopy($img_sprite, $img, $offsetX, $offsetY, 0, 0, $fileWidth, $fileHeight);
        @imagedestroy($img);

        $item = new stdClass();
        $item->file = $file;
        $item->width = $fileWidth;
        $item->height = $fileHeight;
        $item->offsetX = $offsetX;
        $item->offsetY = $offsetY;
        $map[] = $item;
        $offsetX += $fileWidth;
    }

    imgSave($img_sprite, $spriteFile, $quality);
    @imagedestroy($img_sprite);

    return $map;
}

function imgStretch(&$img, $width = null, $height = null) {
    if (!(isset($width) || isset($height)))
        return false;
    elseif (!isset($width))
        $width = imagesx($img);
    elseif (!isset($height))
        $height = imagesy($img);

    $imgOutput = img($width, $height);
    @imagecopyresampled($imgOutput, $img, 0, 0, 0, 0, $width, $height, imagesx($img), imagesy($img));
    @imagedestroy($img);
    $img = $imgOutput;

    return array($width, $height);
}
