<?php
require_once 'config/config.php';
require_once 'src/Database.php';
require_once 'src/Book.php';

use FOBMS\Book;

$db = get_db_connection();
$book = new Book();

// Get special offers
$special_offers = [];
$offers_query = "SELECT * FROM special_offers WHERE is_active = TRUE AND start_date <= CURRENT_DATE() AND end_date >= CURRENT_DATE()";
$result = $db->query($offers_query);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $special_offers[] = $row;
    }
}

// Get featured books
try {
    $featured_books = $book->getAll(['available' => true]);
    $featured_books = array_slice($featured_books, 0, 4); // Get only 4 books
} catch (\Exception $e) {
    error_log("Error fetching featured books: " . $e->getMessage());
    $featured_books = [];
}
?>

<!-- Hero Section -->
<div class="hero bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Welcome to Our Library</h1>
                <p class="lead">Discover thousands of books, from classics to contemporary bestsellers.</p>
                <a href="books.php" class="btn btn-light btn-lg">Browse Books</a>
            </div>
            <div class="col-md-6">
                <img src="assets/images/library-hero.jpg" alt="Library" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<!-- Special Offers Section -->
<div class="container my-5">
    <h2 class="text-center mb-4">Special Offers</h2>
    <div class="row">
        <?php if (!empty($special_offers)): ?>
            <?php foreach ($special_offers as $offer): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($offer['title']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($offer['description']); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-success"><?php echo $offer['discount_percent']; ?>% OFF</span>
                            <small class="text-muted">Valid until: <?php echo date('M d, Y', strtotime($offer['end_date'])); ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No active special offers at the moment. Check back soon!
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Featured Books Section -->
<div class="container my-5">
    <h2 class="text-center mb-4">Featured Books</h2>
    <div class="row">
        <?php if (!empty($featured_books)): ?>
            <?php foreach ($featured_books as $book): ?>
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($book['title']); ?></h5>
                        <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($book['author']); ?></h6>
                        <p class="card-text">
                            <small class="text-muted">Category: <?php echo htmlspecialchars($book['category']); ?></small><br>
                            <small class="text-muted">Available: <?php echo $book['available']; ?> copies</small>
                        </p>
                        <a href="books.php" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info text-center">
                    No featured books available at the moment.
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Features Section -->
<div class="bg-light py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <i class="fas fa-book fa-3x mb-3 text-primary"></i>
                <h4>Wide Selection</h4>
                <p>Access thousands of books across various genres and categories.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-clock fa-3x mb-3 text-primary"></i>
                <h4>Easy Borrowing</h4>
                <p>Simple and quick book borrowing process with flexible return dates.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-percent fa-3x mb-3 text-primary"></i>
                <h4>Special Offers</h4>
                <p>Regular discounts and special offers for our members.</p>
            </div>
        </div>
    </div>
</div> 