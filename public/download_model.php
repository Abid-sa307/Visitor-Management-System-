<?php
$url = 'https://github.com/justadudewhohacks/face-api.js/raw/master/weights/ssd_mobilenetv1_model-shard1';
$target = __DIR__ . '/models/ssd_mobilenetv1_model.bin';

// Download the file
$data = file_get_contents($url);
if ($data === false) {
    die("Failed to download the file");
}

// Save the file
$result = file_put_contents($target, $data);
if ($result === false) {
    die("Failed to save the file");
}

echo "File downloaded successfully to: " . $target . "\n";
echo "File size: " . filesize($target) . " bytes\n";
?>
