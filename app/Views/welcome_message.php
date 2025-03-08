<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Welcome' ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
        }
        .hero-section {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.8)), url('https://source.unsplash.com/random/1200x800/?school');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 0;
        }
        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }
        .navbar {
            background: #212529; /* Black matte */
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            padding: 2rem;
            height: 100%;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background: #212529; /* Black matte */
            border: none;
        }
        .btn-primary:hover {
            background: #343a40; /* Slightly lighter black */
        }
        .text-primary, .text-success, .text-info {
            color: #212529 !important;
        }
        footer {
            background: #212529;
            color: white;
        }
        .feature-section {
            padding: 5rem 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">Student Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_to('login') ?>">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-4">Student Management System</h1>
            <p class="lead mb-5">A comprehensive solution for managing student information, exam results, and grades.</p>
            <div>
                <a href="<?= url_to('login') ?>" class="btn btn-primary btn-lg px-5 py-3">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Login to System
                </a>
            </div>
        </div>
    </section>

    <section class="feature-section">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="feature-icon">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <h3>Student Results</h3>
                        <p>Access and manage student exam results with ease. Track performance across different subjects and exams.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="feature-icon">
                            <i class="bi bi-award"></i>
                        </div>
                        <h3>Grading System</h3>
                        <p>Implement the Tanzania grading system for fair and standardized assessment of student performance.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="feature-icon">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <h3>Secure Access</h3>
                        <p>Role-based access control ensures that users can only access the information they're authorized to see.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4">
        <div class="container text-center">
            <p class="mb-0">© <?= date('Y') ?> Student Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
