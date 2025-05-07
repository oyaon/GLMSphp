<?php include ("admin/db-connect.php"); ?>
<?php include ("header.php"); ?>
<?php include ("top-navbar.php"); ?>
<!-- Registration -->
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h2 class="text-center mb-4">Register</h2>
                    <form action="admin/registration_submit.php" method="POST">
                        <div class="form-group mb-2">
                            <label for="firstname">First Name:</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter first name" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="lastname">Last Name:</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter last name" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="username">User Name:</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter user name" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="confirm_password">Confirm Password:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Re-enter password" required>
                        </div>
                        <p class="text-bold">*Make sure to pay 100Tk as an entry fee while borrowing the first book</p>
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </form>
                    <p class="mt-3 text-center">Already registered? <a href="login_page.php">Login here</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<!--  -->

<?php include ("footer.php"); ?>