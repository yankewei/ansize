<?php

const ANSI_BASIC_BASE  = 16;
const ANSI_FOREGROUND  = "38";
const ANSI_RESET       = "\x1b[0m";

function toAnsiCode(int $r, int $g, int $b): string {
    $code = rgbToAnsi256($r, $g, $b);
    return "\033[". ANSI_FOREGROUND .";5;". strval($code) ."m";
}

function createResourceFromImage($imageName) {
    $type = exif_imagetype($imageName);
    $allowedTypes = array(
        1,  // [] gif
        2,  // [] jpg
        3,  // [] png
        6   // [] bmp
    );
    if (!in_array($type, $allowedTypes)) {
        return false;
    }
    switch ($type) {
        case 1 :
            $resource = imageCreateFromGif($imageName);
            break;
        case 2 :
            $resource = imageCreateFromJpeg($imageName);
            break;
        case 3 :
            $resource = imageCreateFromPng($imageName);
            break;
        case 6 :
            $resource = imageCreateFromBmp($imageName);
            break;
    }
    return $resource;
}

/**
 * convert rgb value to an available for ansi
 * @link https://stackoverflow.com/questions/15682537/ansi-color-specific-rgb-sequence-bash
 * @param int $r
 * @param int $g
 * @param int $b
 * @return int
 */
function rgbToAnsi256(int $r, int $g, int $b): int {
    if ($r === $g && $g === $b) {
        if ($r < 8) {
            return 16;
        }

        if ($r > 248) {
            return 231;
        }

        return round((($r - 8) / 247) * 24) + 232;
    }
    return ANSI_BASIC_BASE + (36 * round($r/255 * 5)) + (6 * round($g/255 * 5)) + round($b/255 * 5);
}


$imageName = $argv[1];
$width = $argv[2];

if (!file_exists($imageName)) {
    printf("%s is not exists", $imageName);
    return;
}

$imageResource = createResourceFromImage($imageName);
$imageResource = imagescale($imageResource, $width);

if ($imageResource === false) {
    printf("Unsupported file type");
    return;
}


for ($y = 0; $y < imagesy($imageResource); $y++) {
    for ($x = 0; $x < imagesx($imageResource); $x++) {
        $rgb = imagecolorat($imageResource, $x, $y);
        $colors = imagecolorsforindex($imageResource, $rgb);
        $string = toAnsiCode($colors['red'], $colors['green'], $colors['blue']);
        if ($string !== '') {
            printf($string);
        }
        $char = strval(random_int(0,1));
        $string .= $char;
        if ($string !== ANSI_RESET) {
            $char = strval(random_int(0,1));
            printf($char);
        } else {
            printf("");
        }
    }

    printf("\n");
}

