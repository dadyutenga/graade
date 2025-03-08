<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Results</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url() ?>">My Application</a>
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
                        <a class="nav-link" href="<?= url_to('products') ?>">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= url_to('student.results') ?>">Student Results</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="nav-link">Welcome, <?= $user->username ?></span>
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
                <a href="<?= url_to('student.results') ?>" class="btn btn-secondary">← Back to Search</a>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Student Information</h3>
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
                        <h3 class="card-title">Exam Groups</h3>
                    </div>
                    <div class="card-body">
                        <?php if (empty($examGroups)): ?>
                            <p class="text-muted">No exam groups found for this student.</p>
                        <?php else: ?>
                            <ul class="list-group">
                                <?php foreach ($examGroups as $group): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <?= $group['exam_group'] ?>
                                        <span class="badge bg-primary rounded-pill">ID: <?= $group['id'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Exam Results</h3>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($examResults as $result): ?>
                                            <tr>
                                                <td><?= $result['subject'] ?></td>
                                                <td><?= $result['get_marks'] ?></td>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 