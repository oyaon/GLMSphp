<?php
require_once 'config/config.php';
require_once 'src/Database.php';
require_once 'src/Book.php';
require_once 'src/Borrowing.php';

use FOBMS\Book;
use FOBMS\Borrowing;

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$book = new Book();
$borrowing = new Borrowing();
$error = '';
$success = '';

// Handle book borrowing
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'borrow') {
    try {
        $bookId = filter_input(INPUT_POST, 'book_id', FILTER_VALIDATE_INT);
        if ($borrowing->borrowBook($_SESSION['user_id'], $bookId)) {
            $success = "Book borrowed successfully";
        }
    } catch (\Exception $e) {
        $error = $e->getMessage();
        error_log("Error borrowing book: " . $e->getMessage());
    }
}

// Get all books
try {
    $filters = [];
    if (!empty($_GET['search'])) {
        $filters['title'] = $_GET['search'];
    }
    if (!empty($_GET['category'])) {
        $filters['category'] = $_GET['category'];
    }
    $books = $book->getAll($filters);
} catch (\Exception $e) {
    $error = "Failed to fetch books";
    error_log("Error fetching books: " . $e->getMessage());
    $books = [];
}

// Get user's current borrowings
try {
    $userBorrowings = $borrowing->getUserBorrowings($_SESSION['user_id']);
} catch (\Exception $e) {
    error_log("Error fetching user borrowings: " . $e->getMessage());
    $userBorrowings = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Books - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Available Books</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <input type="text" name="search" class="form-control me-2" placeholder="Search books..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <select name="category" class="form-select me-2">
                        <option value="">All Categories</option>
                        <option value="Fiction" <?php echo ($_GET['category'] ?? '') === 'Fiction' ? 'selected' : ''; ?>>Fiction</option>
                        <option value="Non-Fiction" <?php echo ($_GET['category'] ?? '') === 'Non-Fiction' ? 'selected' : ''; ?>>Non-Fiction</option>
                        <option value="Reference" <?php echo ($_GET['category'] ?? '') === 'Reference' ? 'selected' : ''; ?>>Reference</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        <!-- Books Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Author</th>
                        <th>ISBN</th>
                        <th>Category</th>
                        <th>Available</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['title']); ?></td>
                        <td><?php echo htmlspecialchars($book['author']); ?></td>
                        <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                        <td><?php echo htmlspecialchars($book['category']); ?></td>
                        <td><?php echo htmlspecialchars($book['available']); ?></td>
                        <td>
                            <?php if ($book['available'] > 0): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="borrow">
                                <input type="hidden" name="book_id" value="<?php echo $book['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-primary">Borrow</button>
                            </form>
                            <?php else: ?>
                            <button class="btn btn-sm btn-secondary" disabled>Not Available</button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- User's Current Borrowings -->
        <h3 class="mt-5">Your Current Borrowings</h3>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($userBorrowings as $borrow): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($borrow['title']); ?></td>
                        <td><?php echo htmlspecialchars($borrow['borrow_date']); ?></td>
                        <td><?php echo htmlspecialchars($borrow['due_date']); ?></td>
                        <td>
                            <?php if ($borrow['return_date']): ?>
                                <span class="badge bg-success">Returned</span>
                            <?php elseif (strtotime($borrow['due_date']) < time()): ?>
                                <span class="badge bg-danger">Overdue</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Active</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 