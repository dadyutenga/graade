<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results Search</title>
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
        .btn-primary {
            background: #212529; /* Black matte */
            border: none;
        }
        .btn-primary:hover {
            background: #343a40; /* Slightly lighter black */
        }
        .btn-secondary {
            background: #6c757d;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
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
            <a class="navbar-brand" href="/">Student Management System</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/student-results">Student Results</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/grades">Grades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <i class="bi bi-search me-2"></i>
                        <h4 class="mb-0">Search Student Results</h4>
                    </div>
                    <div class="card-body">
                        <?php if (session()->has('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <?= session('error') ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <form action="/student-results" method="get">
                            <div class="mb-3">
                                <label for="student_id" class="form-label">
                                    <i class="bi bi-person-badge me-2"></i>Student ID
                                </label>
                                <input type="number" class="form-control form-control-lg" id="student_id" name="student_id" required>
                                <div class="form-text">Enter the student ID to view their exam results.</div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-search me-2"></i>Search
                            </button>
                        </form>
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