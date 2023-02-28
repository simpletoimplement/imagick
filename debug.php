<?php

for ($colorspace = 0; $colorspace <= 34; $colorspace += 1) {

//Example Imagick::clutImage
// Make a shape
    $draw = new \ImagickDraw();
    $draw->setStrokeOpacity(0);
    $draw->setFillColor('black');
    $points = [
        ['x' => 40 * 3, 'y' => 10 * 5],
        ['x' => 20 * 3, 'y' => 20 * 5],
        ['x' => 70 * 3, 'y' => 50 * 5],
        ['x' => 80 * 3, 'y' => 15 * 5],
    ];

    $draw->polygon($points);
    $imagick = new \Imagick();
    $imagick->newPseudoImage(
        300, 300,
        "xc:white"
    );

    $imagick->drawImage($draw);
    $imagick->blurImage(0, 10);

//Make a gradient
    $draw = new \ImagickDraw();
    $draw->setStrokeOpacity(1);

    $draw->setFillColor('white');
    $draw->point(0, 0);

    $draw->setFillColor('yellow');
    $draw->point(0, 1);

    $draw->setFillColor('red');
    $draw->point(0, 2);

    $gradient = new Imagick();
    $gradient->newPseudoImage(1, 5, 'xc:blue');
    $gradient->drawImage($draw);
    $gradient->setImageFormat('png');

    $gradient->setImageColorspace($colorspace);

//These two are needed for the clutImage to work reliably.
    $imagick->setImageAlphaChannel(\Imagick::ALPHACHANNEL_DEACTIVATE);
    $imagick->transformImageColorspace(\Imagick::COLORSPACE_GRAY);

// $imagick->setImageInterpolateMethod(\Imagick::INTERPOLATE_INTEGER);

//Make the color lookup be smooth
//$gradient->setImageInterpolateMethod(\Imagick::INTERPOLATE_BILINEAR);
//Nearest neighbour uses exact color values from clut
//$gradient->setImageInterpolateMethod(\Imagick::INTERPOLATE_NEARESTNEIGHBOR);

    $imagick->writeImage(__DIR__ . "/clut_drawn_image.png");

    $imagick->clutImageWithInterpolateMethod($gradient, \Imagick::INTERPOLATE_BILINEAR);

    $gradient->writeImage(__DIR__ . "/clut_gradient_image.png");

    $imagick->writeImage(__DIR__ . "/clut_output_" . $colorspace . "_image.png");
}