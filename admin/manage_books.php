<?php
require_once '../config/config.php';
require_once '../src/Database.php';
require_once '../src/Book.php';

use FOBMS\Book;

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$book = new Book();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $bookData = [
                        'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                        'author' => filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING),
                        'isbn' => filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_STRING),
                        'category' => filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING),
                        'quantity' => filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT)
                    ];
                    
                    if ($book->create($bookData)) {
                        $success = "Book added successfully";
                    }
                    break;

                case 'update':
                    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                    $bookData = [
                        'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                        'author' => filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING),
                        'isbn' => filter_input(INPUT_POST, 'isbn', FILTER_SANITIZE_STRING),
                        'category' => filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING),
                        'quantity' => filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT)
                    ];
                    
                    if ($book->update($id, $bookData)) {
                        $success = "Book updated successfully";
                    }
                    break;

                case 'delete':
                    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                    if ($book->delete($id)) {
                        $success = "Book deleted successfully";
                    }
                    break;
            }
        }
    } catch (\Exception $e) {
        $error = $e->getMessage();
        error_log("Book management error: " . $e->getMessage());
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Manage Books</h2>

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
            <div class="col-md-6 text-end">
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    Add New Book
                </button>
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
                        <th>Quantity</th>
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
                        <td><?php echo htmlspecialchars($book['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($book['available']); ?></td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editBookModal"
                                    data-book='<?php echo htmlspecialchars(json_encode($book)); ?>'>
                                Edit
                            </button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this book?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $book['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include 'includes/book_modals.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 