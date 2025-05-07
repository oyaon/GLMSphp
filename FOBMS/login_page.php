<?php include ("admin/db-connect.php"); ?>
<?php include ("header.php"); ?>
<?php include ("top-navbar.php"); ?>

<div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Login</h2>

                        <form action="login_submit.php" method="POST">
                            <div class="form-group mb-2">
                                <label for="email">Email:</label>
                                <input type="email" name="email" class="form-control" id="email" placeholder="Enter email" required>
                            </div>

                            <div class="form-group mb-2">
                                <label for="password">Password:</label>
                                <input type="password" name="password" class="form-control" id="password" placeholder="Enter password" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Login</button>
                        </form>

                        <p class="mt-3 text-center">Don't have an account? <a href="registration_page.php">Sign up here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include ("footer.php"); ?>