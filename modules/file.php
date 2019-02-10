<?php

// Removing cached file and directory change date
// to make functions work properly
clearstatcache();

function fileArrFromUploadedFiles(&$files) {
    fastredRequire('obj');

    $result = array();

    if (is_array($files['name'])) {
        $count = count($files['name']);

        for ($i = 0; $i < $count; $i++) {
            $file = obj();
            $file->name = $files['name'][$i];
            $file->type = $files['type'][$i];
            $file->path = $files['tmp_name'][$i];
            $file->error = $files['error'][$i];
            $file->size = $files['size'][$i];
            $result[] = $file;
        }
    } else {
        $file = obj();
        $file->name = $files['name'];
        $file->type = $files['type'];
        $file->path = $files['tmp_name'];
        $file->error = $files['error'];
        $file->size = $files['size'];
        $result[] = $file;
    }

    return $result;
}

function fileGetChanged($filepath) {
    return @filemtime($filepath);
}

function fileGetExt($filepath) {
    return pathinfo($path, PATHINFO_EXTENSION);
}

function fileGetName($filepath) {
    return basename($filepath);
}

function fileArrGetLastChanged($arr) {
    $result = 0;
    foreach($arr as $filepath) {
        $changed = @filemtime($filepath);
        if ($changed > $result) $result = $changed;
    }
    return $result;
}

function fileExists($filepath) {
    return file_exists($filepath);
}

function fileSizeToStr($size, $precision = 2) {
    $units = array('1-byte', '1-kb', '1-mb', '1-gb', '1-tb', '1-pb');
    $size = max($size, 0);

    $pow = floor(($size ? log($size) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $size /= pow(1024, $pow);

    fastredRequire('locale');

    return localeGetStr($units[$pow], $pow > 0 ? localeFloatToStr($size, $precision) : $size);
}