<div class="container">
	<nav class="navbar navbar-expand-lg bg-body-tertiary py-4">
		<div class="container-fluid">
			<a class="navbar-brand" href="index.php">Book Storage</a>

			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
			aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>

			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav me-auto mb-2 mb-lg-0">
					<li class="nav-item">
						<a class="nav-link active" aria-current="page" href="index.php">Home</a>
					</li>

					<li class="nav-item">
						<a class="nav-link" href="all-books.php">All Books</a>
					</li>
				</ul>

				<ul class="navbar-nav mb-2 mb-lg-0">
					<!-- <li class="nav-item">
						<a href="search-books.php" class="btn btn-outline-success">Search</a>
					</li> -->

					<!-- <li class="nav-item">
						<a href="cart-page.php" class="btn"><i class="fa-solid fa-cart-plus"></i></a>
					</li> -->

					<?php if( isset($_SESSION['email']) && !empty($_SESSION['email']) ): ?>
						<li class="nav-item">
							<a class="nav-link" href="borrow_history.php">Borrow history</a>
						</li>

						<li class="nav-item">
							<a class="btn btn-outline-danger" href="logout_page.php">Logout</a>
						</li> 					
					<?php else: ?>
						<li class="nav-item">
							<a class="nav-link" href="login_page.php">Login</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="registration_page.php">Register</a>
						</li>						
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
</div>