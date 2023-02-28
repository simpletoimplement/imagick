<?php



// Create the source image and get the dimension of it.
$imagick = new \Imagick(realpath(__DIR__ . "/Biter_500.png"));

// Separate the 3 channels to individual images.
$channel1 = clone $imagick;
$channel1->separateImageChannel(Imagick::CHANNEL_RED);
$channel2 = clone $imagick;
$channel2->separateImageChannel(Imagick::CHANNEL_GREEN);
$channel3 = clone $imagick;
$channel3->separateImageChannel(Imagick::CHANNEL_BLUE);


//$channel1->setImageColorspace(Imagick::COLORSPACE_SRGB);
//$channel2->setImageColorspace(Imagick::COLORSPACE_SRGB);
//$channel3->setImageColorspace(Imagick::COLORSPACE_SRGB);


// Create an empty canvas that we will use to recombine the separate images.
$canvas = new Imagick();
$canvas->newPseudoImage(
    $imagick->getImageWidth(),
    $imagick->getImageHeight(),
    "canvas:black"
);

//$canvas->setImageColorspace(Imagick::COLORSPACE_RGB);
// Copy the individual channels into the canvas.
$canvas->compositeImage($channel1, Imagick::COMPOSITE_COPYRED, 0, 0);
$canvas->compositeImage($channel2, Imagick::COMPOSITE_COPYGREEN, 0, 0);
$canvas->compositeImage($channel3, Imagick::COMPOSITE_COPYBLUE, 0, 0);

//$canvas->transformImageColorspace(Imagick::COLORSPACE_SRGB);

$canvas->writeImage(__DIR__ . "/output_php.png");
