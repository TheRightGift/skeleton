<?php
require __DIR__ . '/vendor/autoload.php';

// Use the direct class instead of the facade
use SimpleSoftwareIO\QrCode\Generator;

try {
    echo "Creating QR code...\n";
    
    // Test the QrCode generation
    $qrCode = new Generator();
    $result = $qrCode->size(300)
        ->format('svg')  // Use SVG instead of PNG
        ->errorCorrection('M')
        ->margin(1)
        ->generate('https://example.com');

    // Write to a file for inspection
    $filename = __DIR__ . '/test_qrcode.svg';
    file_put_contents($filename, $result);

    echo "QR Code generation successful! Created 'test_qrcode.svg'\n";
} catch (\Exception $e) {
    echo "Error generating QR code: " . $e->getMessage() . "\n";
}
