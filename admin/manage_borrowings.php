<?php
require_once '../config/config.php';
require_once '../src/Database.php';
require_once '../src/Borrowing.php';

use FOBMS\Borrowing;

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$borrowing = new Borrowing();
$error = '';
$success = '';

// Handle book return
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'return') {
    try {
        $borrowingId = filter_input(INPUT_POST, 'borrowing_id', FILTER_VALIDATE_INT);
        if ($borrowing->returnBook($borrowingId)) {
            $success = "Book returned successfully";
        }
    } catch (\Exception $e) {
        $error = $e->getMessage();
        error_log("Error returning book: " . $e->getMessage());
    }
}

// Get all borrowings
try {
    $filters = [];
    if (!empty($_GET['status'])) {
        $filters['status'] = $_GET['status'];
    }
    if (!empty($_GET['overdue'])) {
        $filters['overdue'] = true;
    }
    $borrowings = $borrowing->getAllBorrowings($filters);
} catch (\Exception $e) {
    $error = "Failed to fetch borrowings";
    error_log("Error fetching borrowings: " . $e->getMessage());
    $borrowings = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Borrowings - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <h2>Manage Borrowings</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
        <?php endif; ?>

        <!-- Filter -->
        <div class="row mb-4">
            <div class="col-md-6">
                <form method="GET" class="d-flex">
                    <select name="status" class="form-select me-2">
                        <option value="">All Status</option>
                        <option value="active" <?php echo ($_GET['status'] ?? '') === 'active' ? 'selected' : ''; ?>>Active</option>
                        <option value="returned" <?php echo ($_GET['status'] ?? '') === 'returned' ? 'selected' : ''; ?>>Returned</option>
                    </select>
                    <div class="form-check me-2">
                        <input type="checkbox" class="form-check-input" id="overdue" name="overdue" value="1" <?php echo isset($_GET['overdue']) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="overdue">Show Overdue Only</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Filter</button>
                </form>
            </div>
        </div>

        <!-- Borrowings Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrowings as $borrow): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($borrow['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($borrow['title']); ?></td>
                        <td><?php echo htmlspecialchars($borrow['borrow_date']); ?></td>
                        <td><?php echo htmlspecialchars($borrow['due_date']); ?></td>
                        <td><?php echo $borrow['return_date'] ? htmlspecialchars($borrow['return_date']) : '-'; ?></td>
                        <td>
                            <?php if ($borrow['return_date']): ?>
                                <span class="badge bg-success">Returned</span>
                            <?php elseif (strtotime($borrow['due_date']) < time()): ?>
                                <span class="badge bg-danger">Overdue</span>
                            <?php else: ?>
                                <span class="badge bg-primary">Active</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!$borrow['return_date']): ?>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="return">
                                <input type="hidden" name="borrowing_id" value="<?php echo $borrow['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-success">Return</button>
                            </form>
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