<?php
// Create models directory if it doesn't exist
$modelsDir = __DIR__ . '/public/models';
if (!file_exists($modelsDir)) {
    mkdir($modelsDir, 0777, true);
}

// List of model files to download
$files = [
    'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights/tiny_face_detector_model-weights_manifest.json',
    'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights/tiny_face_detector_model-shard1',
    'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights/face_landmark_68_model-weights_manifest.json',
    'https://raw.githubusercontent.com/justadudewhohacks/face-api.js/master/weights/face_landmark_68_model-shard1'
];

// Download each file
foreach ($files as $url) {
    $filename = basename($url);
    $filepath = $modelsDir . '/' . $filename;
    
    echo "Downloading $filename... ";
    
    $fileContent = @file_get_contents($url);
    if ($fileContent !== false) {
        file_put_contents($filepath, $fileContent);
        echo "Done!\n";
    } else {
        echo "Failed to download $filename\n";
    }
}

echo "\nAll files have been downloaded to: $modelsDir\n";
echo "You can now access them at: /models/\n";
?>
