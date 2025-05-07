<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Database.php';
require_once __DIR__ . '/../src/SpecialOffer.php';

use FOBMS\SpecialOffer;

// Test data
$testOffer = [
    'title' => 'Test Offer',
    'description' => 'This is a test offer',
    'discount_percent' => 20,
    'coupon_code' => 'TEST20',
    'usage_limit' => 100,
    'min_books' => 1,
    'category_restriction' => 'Fiction',
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d', strtotime('+30 days')),
    'is_active' => true
];

function runTest($name, $callback) {
    echo "\n=== Testing $name ===\n";
    try {
        $result = $callback();
        echo "✓ Test passed\n";
        return $result;
    } catch (\Exception $e) {
        echo "✗ Test failed: " . $e->getMessage() . "\n";
        return null;
    }
}

try {
    $offer = new SpecialOffer();
    
    // Test creating an offer
    $id = runTest('offer creation', function() use ($offer, $testOffer) {
        echo "Creating offer with data:\n";
        var_dump($testOffer);
        $id = $offer->create($testOffer);
        if (!$id) {
            throw new \Exception("Failed to create offer");
        }
        return $id;
    });

    if (!$id) {
        throw new \Exception("Cannot proceed with tests: offer creation failed");
    }

    // Test retrieving the offer
    runTest('offer retrieval', function() use ($offer, $id, $testOffer) {
        $retrievedOffer = $offer->getById($id);
        if (!$retrievedOffer) {
            throw new \Exception("Failed to retrieve offer");
        }
        if ($retrievedOffer['title'] !== $testOffer['title']) {
            throw new \Exception("Retrieved offer title does not match");
        }
        echo "Retrieved offer:\n";
        var_dump($retrievedOffer);
    });

    // Test updating the offer
    runTest('offer update', function() use ($offer, $id) {
        $updateData = [
            'title' => 'Updated Test Offer',
            'description' => 'This is an updated test offer',
            'discount_percent' => 25,
            'coupon_code' => 'TEST25',
            'usage_limit' => 50,
            'min_books' => 2,
            'category_restriction' => 'Non-Fiction',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+15 days')),
            'is_active' => true
        ];
        if (!$offer->update($id, $updateData)) {
            throw new \Exception("Failed to update offer");
        }
        $updatedOffer = $offer->getById($id);
        if ($updatedOffer['title'] !== $updateData['title']) {
            throw new \Exception("Update was not successful");
        }
        echo "Updated offer:\n";
        var_dump($updatedOffer);
    });

    // Test coupon validation
    runTest('coupon validation', function() use ($offer) {
        $validation = $offer->validateCoupon('TEST25', 2, 'Non-Fiction');
        if (!$validation['valid']) {
            throw new \Exception("Valid coupon was rejected: " . $validation['message']);
        }
        echo "Coupon validation result:\n";
        var_dump($validation);
    });

    // Test invalid coupon
    runTest('invalid coupon', function() use ($offer) {
        $validation = $offer->validateCoupon('INVALID', 1, 'Fiction');
        if ($validation['valid']) {
            throw new \Exception("Invalid coupon was incorrectly accepted");
        }
        echo "Invalid coupon was correctly rejected: " . $validation['message'] . "\n";
    });

    // Test category restriction
    runTest('category restriction', function() use ($offer) {
        $validation = $offer->validateCoupon('TEST25', 2, 'Fiction');
        if ($validation['valid']) {
            throw new \Exception("Category restriction was not enforced");
        }
        echo "Category restriction was correctly enforced: " . $validation['message'] . "\n";
    });

    // Test minimum books requirement
    runTest('minimum books requirement', function() use ($offer) {
        $validation = $offer->validateCoupon('TEST25', 1, 'Non-Fiction');
        if ($validation['valid']) {
            throw new \Exception("Minimum books requirement was not enforced");
        }
        echo "Minimum books requirement was correctly enforced: " . $validation['message'] . "\n";
    });

    // Test usage limit
    runTest('usage limit', function() use ($offer, $id) {
        // Set usage limit to 1 and used count to 1
        $updateData = [
            'title' => 'Updated Test Offer',
            'description' => 'This is an updated test offer',
            'discount_percent' => 25,
            'coupon_code' => 'TEST25',
            'usage_limit' => 1,
            'min_books' => 2,
            'category_restriction' => 'Non-Fiction',
            'start_date' => date('Y-m-d'),
            'end_date' => date('Y-m-d', strtotime('+15 days')),
            'is_active' => true
        ];
        $offer->update($id, $updateData);
        
        // Simulate a usage
        $offer->incrementUsage($id);
        
        // Try to use the coupon
        $validation = $offer->validateCoupon('TEST25', 2, 'Non-Fiction');
        if ($validation['valid']) {
            throw new \Exception("Usage limit was not enforced");
        }
        echo "Usage limit was correctly enforced: " . $validation['message'] . "\n";
    });

    // Clean up
    runTest('cleanup', function() use ($offer, $id) {
        if (!$offer->delete($id)) {
            throw new \Exception("Failed to delete test offer");
        }
        echo "Test offer deleted successfully\n";
    });

} catch (\Exception $e) {
    echo "\nError in test suite: " . $e->getMessage() . "\n";
} 