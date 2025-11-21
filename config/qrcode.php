<?php

return [
    'format' => 'svg', // or 'png' if you prefer
    'size' => 300,
    'margin' => 2,
    'errorCorrection' => 'H', // Highest error correction
    'backColor' => [255, 255, 255, 0],
    'foreColor' => [0, 0, 0, 0],
    'style' => 'square',
    'eye' => 'square',
    'image' => [
        'driver' => 'gd', // Force GD driver
    ],
];
