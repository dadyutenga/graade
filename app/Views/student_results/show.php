<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
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
        .badge {
            background: #212529;
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
                        <a class="nav-link" href="<?= url_to('dashboard') ?>">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= url_to('grades') ?>">Grades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= url_to('student.results') ?>">Student Results</a>
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
        <div class="row mb-4">
            <div class="col-md-12">
                <a href="<?= url_to('student.results') ?>" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to Search
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-person-badge me-2"></i>Student Information
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Name:</strong> <?= $student['firstname'] . ' ' . $student['lastname'] ?></p>
                                <p><strong>ID:</strong> <?= $student['id'] ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Class:</strong> <?= $student['class'] ?></p>
                                <p><strong>Class ID:</strong> <?= $student['class_id'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-collection me-2"></i>Exam Groups
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($examGroups)): ?>
                            <p class="text-muted">No exam groups found for this student.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach ($examGroups as $group): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $group['exam_group'] ?>
                                        <span class="badge rounded-pill">ID: <?= $group['id'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="bi bi-clipboard-data me-2"></i>Exam Results
                        </h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($examResults)): ?>
                            <p class="text-muted">No exam results found for this student.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Marks</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($examResults as $result): ?>
                                            <tr>
                                                <td><?= $result['subject'] ?></td>
                                                <td><?= $result['get_marks'] ?></td>
                                                <td>
                                                    <?php
                                                    $marks = $result['get_marks'];
                                                    if ($marks >= 80) echo 'A';
                                                    elseif ($marks >= 70) echo 'B+';
                                                    elseif ($marks >= 60) echo 'B';
                                                    elseif ($marks >= 50) echo 'C';
                                                    elseif ($marks >= 40) echo 'D';
                                                    else echo 'F';
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
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