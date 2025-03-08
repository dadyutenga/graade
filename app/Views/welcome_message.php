<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to CodeIgniter 4 with Shield</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .hero {
            padding: 80px 0;
            background-color: #fff;
            border-bottom: 1px solid #e9ecef;
        }
        .features {
            padding: 60px 0;
        }
        .feature-box {
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            height: 100%;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">My Application</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url_to('dashboard') ?>">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <span class="nav-link">Welcome, <?= $user->username ?></span>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url_to('logout') ?>">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url_to('login') ?>">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= url_to('register') ?>">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-md-8 offset-md-2 text-center">
                    <h1 class="display-4">Welcome to Your Application</h1>
                    <p class="lead">A secure application built with CodeIgniter 4 and Shield Authentication</p>
                    <?php if (!$isLoggedIn): ?>
                        <div class="mt-4">
                            <a href="<?= url_to('login') ?>" class="btn btn-primary me-2">Login</a>
                            <a href="<?= url_to('register') ?>" class="btn btn-outline-primary">Register</a>
                        </div>
                    <?php else: ?>
                        <div class="mt-4">
                            <a href="<?= url_to('dashboard') ?>" class="btn btn-success">Go to Dashboard</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <section class="features">
        <div class="container">
            <h2 class="text-center mb-5">Application Features</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <h3>Secure Authentication</h3>
                        <p>Powered by CodeIgniter Shield, our authentication system provides secure login, registration, and user management.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <h3>User Dashboard</h3>
                        <p>Access your personalized dashboard with all the tools and information you need.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <h3>Responsive Design</h3>
                        <p>Our application is fully responsive and works on all devices, from desktop to mobile.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <p>&copy; <?= date('Y') ?> Your Application. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
