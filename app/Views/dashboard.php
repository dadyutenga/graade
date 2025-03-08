<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ed 100%);
            min-height: 100vh;
        }
        .navbar {
            background: #212529; /* Black matte */
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .card-header {
            background: #212529; /* Black matte */
            color: white;
            border-bottom: none;
            padding: 1rem 1.5rem;
        }
        .btn-primary, .btn-success, .btn-info {
            background: #212529; /* Black matte */
            border: none;
        }
        .btn-primary:hover, .btn-success:hover, .btn-info:hover {
            background: #343a40; /* Slightly lighter black */
        }
        .btn-outline-success, .btn-outline-info, .btn-outline-secondary {
            color: #212529;
            border-color: #212529;
        }
        .btn-outline-success:hover, .btn-outline-info:hover, .btn-outline-secondary:hover {
            background-color: #212529;
            color: white;
        }
        .text-primary, .text-success, .text-info {
            color: #212529 !important;
        }
        footer {
            background: #212529;
            color: white;
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
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url() ?>">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= url_to('dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_to('grades') ?>">Grades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_to('student.results') ?>">Student Results</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Welcome, <?= auth()->user()->username ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_to('logout') ?>">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12 mb-4">
                <h2>Welcome to your dashboard, <?= auth()->user()->username ?>!</h2>
                <p class="lead">Manage your application from here.</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-person-circle fs-1 mb-3"></i>
                        <h4 class="card-title">Your Profile</h4>
                        <p class="card-text">Email: <?= auth()->user()->email ?></p>
                        <p class="card-text">
                            <strong>Groups:</strong> 
                            <?= implode(', ', auth()->user()->getGroups()) ?>
                        </p>
                        <a href="#" class="btn btn-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-award fs-1 mb-3"></i>
                        <h4 class="card-title">Grades</h4>
                        <p class="card-text">View and manage Tanzania grading system</p>
                        <a href="<?= url_to('grades') ?>" class="btn btn-success">Go to Grades</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-mortarboard-fill fs-1 mb-3"></i>
                        <h4 class="card-title">Student Results</h4>
                        <p class="card-text">View and manage student exam results</p>
                        <a href="<?= url_to('student.results') ?>" class="btn btn-info">View Results</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                   
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">Quick Actions</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="<?= url_to('grades') ?>" class="btn btn-outline-success w-100">
                                    <i class="bi bi-award me-2"></i> View Grades
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="<?= url_to('student.results') ?>" class="btn btn-outline-info w-100">
                                    <i class="bi bi-search me-2"></i> Search Student Results
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="#" class="btn btn-outline-secondary w-100">
                                    <i class="bi bi-gear me-2"></i> Settings
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <footer class="py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">© <?= date('Y') ?> Student Management System. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 