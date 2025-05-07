<?php
require_once '../config/config.php';
require_once '../src/Database.php';
require_once '../src/SpecialOffer.php';

use FOBMS\SpecialOffer;

session_start();

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$offer = new SpecialOffer();
$error = '';
$success = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $offerData = [
                        'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                        'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                        'discount_percent' => filter_input(INPUT_POST, 'discount_percent', FILTER_VALIDATE_INT),
                        'coupon_code' => filter_input(INPUT_POST, 'coupon_code', FILTER_SANITIZE_STRING),
                        'usage_limit' => filter_input(INPUT_POST, 'usage_limit', FILTER_VALIDATE_INT),
                        'min_books' => filter_input(INPUT_POST, 'min_books', FILTER_VALIDATE_INT),
                        'category_restriction' => filter_input(INPUT_POST, 'category_restriction', FILTER_SANITIZE_STRING),
                        'start_date' => filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING),
                        'end_date' => filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING),
                        'is_active' => isset($_POST['is_active'])
                    ];
                    
                    if ($offer->create($offerData)) {
                        $success = "Special offer added successfully";
                    }
                    break;

                case 'update':
                    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                    $offerData = [
                        'title' => filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING),
                        'description' => filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING),
                        'discount_percent' => filter_input(INPUT_POST, 'discount_percent', FILTER_VALIDATE_INT),
                        'coupon_code' => filter_input(INPUT_POST, 'coupon_code', FILTER_SANITIZE_STRING),
                        'usage_limit' => filter_input(INPUT_POST, 'usage_limit', FILTER_VALIDATE_INT),
                        'min_books' => filter_input(INPUT_POST, 'min_books', FILTER_VALIDATE_INT),
                        'category_restriction' => filter_input(INPUT_POST, 'category_restriction', FILTER_SANITIZE_STRING),
                        'start_date' => filter_input(INPUT_POST, 'start_date', FILTER_SANITIZE_STRING),
                        'end_date' => filter_input(INPUT_POST, 'end_date', FILTER_SANITIZE_STRING),
                        'is_active' => isset($_POST['is_active'])
                    ];
                    
                    if ($offer->update($id, $offerData)) {
                        $success = "Special offer updated successfully";
                    }
                    break;

                case 'delete':
                    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                    if ($offer->delete($id)) {
                        $success = "Special offer deleted successfully";
                    }
                    break;
            }
        }
    } catch (\Exception $e) {
        $error = $e->getMessage();
        error_log("Offer management error: " . $e->getMessage());
    }
}

// Get all special offers
try {
    $filters = [];
    if (!empty($_GET['search'])) {
        $filters['title'] = $_GET['search'];
    }
    if (!empty($_GET['category'])) {
        $filters['category'] = $_GET['category'];
    }
    $offers = $offer->getAll($filters);
} catch (\Exception $e) {
    $error = "Failed to fetch offers";
    error_log("Error fetching offers: " . $e->getMessage());
    $offers = [];
}

// Get book categories for dropdown
$categories = ['Fiction', 'Non-Fiction', 'Reference'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Special Offers - Library Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manage Special Offers</h2>
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addOfferModal">
                Add New Offer
            </button>
        </div>

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
                    <input type="text" name="search" class="form-control me-2" placeholder="Search offers..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                    <select name="category" class="form-select me-2">
                        <option value="">All Categories</option>
                        <?php foreach ($categories as $category): ?>
                        <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($_GET['category'] ?? '') === $category ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </div>

        <!-- Special Offers Table -->
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Discount</th>
                        <th>Coupon Code</th>
                        <th>Usage</th>
                        <th>Validity</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($offers as $offer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($offer['title']); ?></td>
                        <td><?php echo $offer['discount_percent']; ?>%</td>
                        <td><?php echo htmlspecialchars($offer['coupon_code']); ?></td>
                        <td>
                            <?php if ($offer['usage_limit']): ?>
                                <?php echo $offer['used_count']; ?>/<?php echo $offer['usage_limit']; ?>
                            <?php else: ?>
                                <?php echo $offer['used_count']; ?> (Unlimited)
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php echo date('M d, Y', strtotime($offer['start_date'])); ?> -
                            <?php echo date('M d, Y', strtotime($offer['end_date'])); ?>
                        </td>
                        <td>
                            <?php if ($offer['is_active']): ?>
                                <span class="badge bg-success">Active</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactive</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editOfferModal"
                                    data-offer='<?php echo htmlspecialchars(json_encode($offer)); ?>'>
                                Edit
                            </button>
                            <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $offer['id']; ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Offer Modal -->
    <div class="modal fade" id="addOfferModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Special Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="discount_percent" class="form-label">Discount Percentage</label>
                            <input type="number" class="form-control" id="discount_percent" name="discount_percent" min="1" max="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="coupon_code" class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" id="coupon_code" name="coupon_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="usage_limit" class="form-label">Usage Limit (optional)</label>
                            <input type="number" class="form-control" id="usage_limit" name="usage_limit" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="min_books" class="form-label">Minimum Books Required</label>
                            <input type="number" class="form-control" id="min_books" name="min_books" min="1" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="category_restriction" class="form-label">Category Restriction (optional)</label>
                            <select class="form-select" id="category_restriction" name="category_restriction">
                                <option value="">No Restriction</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" checked>
                            <label class="form-check-label" for="is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Offer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Offer Modal -->
    <div class="modal fade" id="editOfferModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Special Offer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Title</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_discount_percent" class="form-label">Discount Percentage</label>
                            <input type="number" class="form-control" id="edit_discount_percent" name="discount_percent" min="1" max="100" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_coupon_code" class="form-label">Coupon Code</label>
                            <input type="text" class="form-control" id="edit_coupon_code" name="coupon_code" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_usage_limit" class="form-label">Usage Limit (optional)</label>
                            <input type="number" class="form-control" id="edit_usage_limit" name="usage_limit" min="1">
                        </div>
                        <div class="mb-3">
                            <label for="edit_min_books" class="form-label">Minimum Books Required</label>
                            <input type="number" class="form-control" id="edit_min_books" name="min_books" min="1" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_category_restriction" class="form-label">Category Restriction (optional)</label>
                            <select class="form-select" id="edit_category_restriction" name="category_restriction">
                                <option value="">No Restriction</option>
                                <?php foreach ($categories as $category): ?>
                                <option value="<?php echo htmlspecialchars($category); ?>">
                                    <?php echo htmlspecialchars($category); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit_start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="edit_start_date" name="start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="edit_end_date" name="end_date" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="edit_is_active" name="is_active">
                            <label class="form-check-label" for="edit_is_active">Active</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Offer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Handle edit modal data
        document.getElementById('editOfferModal').addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const offer = JSON.parse(button.getAttribute('data-offer'));
            
            document.getElementById('edit_id').value = offer.id;
            document.getElementById('edit_title').value = offer.title;
            document.getElementById('edit_description').value = offer.description;
            document.getElementById('edit_discount_percent').value = offer.discount_percent;
            document.getElementById('edit_coupon_code').value = offer.coupon_code;
            document.getElementById('edit_usage_limit').value = offer.usage_limit;
            document.getElementById('edit_min_books').value = offer.min_books;
            document.getElementById('edit_category_restriction').value = offer.category_restriction || '';
            document.getElementById('edit_start_date').value = offer.start_date;
            document.getElementById('edit_end_date').value = offer.end_date;
            document.getElementById('edit_is_active').checked = offer.is_active == 1;
        });

        // Validate date ranges
        function validateDates(form) {
            const startDate = new Date(form.start_date.value);
            const endDate = new Date(form.end_date.value);
            
            if (endDate < startDate) {
                alert('End date must be after start date');
                return false;
            }
            return true;
        }

        // Add form validation
        document.querySelector('#addOfferModal form').onsubmit = function() {
            return validateDates(this);
        };

        // Edit form validation
        document.querySelector('#editOfferModal form').onsubmit = function() {
            return validateDates(this);
        };
    </script>
</body>
</html> 