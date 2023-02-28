<?php


try {
    set_time_limit(4);
    $imagick = new Imagick(__DIR__ . "/broken-image.jpg");

    echo "Width: " . $imagick->getImageWidth() . ", ";
    echo "Height: " . $imagick->getImageHeight() . ", ";
    echo "Failed to throw exception\n";
    exit(2);
}
catch (Exception $e) {
    echo "Good exception: " . $e->getMessage();
    exit(0);
}